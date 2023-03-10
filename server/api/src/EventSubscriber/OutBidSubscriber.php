<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Bid;
use App\Entity\BidLog;
use App\Entity\UserBid;
use App\Enum\UserBidStatus;
use App\Repository\UserBidRepository;
use App\Repository\BidLogRepository;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;

final class OutBidSubscriber implements EventSubscriberInterface
{
    private $userBidRepository;
    private $bidLogRepository;
    private $security;
    private $entityManager;

    public function __construct(BidLogRepository $bidLogRepository, Security $security, UserBidRepository $userBidRepository, EntityManagerInterface $entityManager, private MailerInterface $mailer)
    {
        $this->userBidRepository = $userBidRepository;
        $this->bidLogRepository = $bidLogRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['outBid', EventPriorities::PRE_WRITE],
        ];
    }

    public function outBid(ViewEvent $event): void
    {
        $bid = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$bid instanceof Bid || Request::METHOD_PATCH !== $method) {
            return;
        }

        $request = $event->getRequest();
        ['price' => $price] = $request->toArray();
        $this->checkUser();
        $this->checkSeller($bid);
        $this->checkPrice($price, $bid);
        $this->updateLastUserBidEntry($bid);
        $this->checkUserBid($bid);
        $this->setOutBid($bid, $price);
        $this->sendEmailOutbidWinner($this->security->getUser(), $bid);
    }

    private function updateLastUserBidEntry(Bid $bid)
    {
        $lastUserBid = $this->userBidRepository->findOneBy(['bid' => $bid, 'status' => UserBidStatus::WINNING]);

        if (!$lastUserBid) {
            return;
        }

        $lastUserBid->setStatus(UserBidStatus::DEFAULT);
        $this->entityManager->persist($lastUserBid);
        $this->entityManager->flush();

        $this->sendEmailOutbidLoser($lastUserBid->getBidder(), $bid);
    }


    private function checkUser()
    {
        if (!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        return;
    }

    private function checkSeller(Bid $bid)
    {
        if ($this->security->getUser() === $bid->getSeller()) {
            throw new \Exception('Seller cannot outbid');
        }
    }

    private function checkPrice($price, $bid)
    {
        if (!$price) {
            throw new \Exception('price is required');
        }
        if ($price <= $bid->getCurrentPrice()) {
            throw new \Exception('Price is lower or equal than current price');
        }
        return;
    }

    private function checkUserBid(Bid $bid)
    {
        $userBid = $this->userBidRepository->findOneBy(['bidder' => $this->security->getUser(), 'bid' => $bid]);

        if (!$userBid) {
            $userBid = new UserBid();
            $userBid->setBid($bid);
            $userBid->setBidder($this->security->getUser());
        }

        $userBid->setStatus(UserBidStatus::WINNING);
        $this->entityManager->persist($userBid);
        $this->entityManager->flush();
        return;
    }

    private function setOutBid(Bid $bid, $price)
    {
        $bid->setCurrentPrice($price);
        $bidLog = new BidLog();
        $bidLog->setBid($bid);
        $bidLog->setBidder($this->security->getUser());
        $bidLog->setPrice($price);
        $this->entityManager->persist($bidLog);
        $this->entityManager->flush();

        return;
    }

    private function sendEmailOutbidWinner($user, $bid)
    {
        try {
            $email = (new TemplatedEmail())
                ->from("no-reply@mlp.com")
                ->to($user->getEmail())
                ->subject('My Little Poacher - Votre demande a ??t?? entendu')
                ->htmlTemplate('emails/outBidWinner.html.twig')
                ->context([
                    'user' => $user,
                    'bid' => $bid
                ]);
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new \Exception('Error sending email');
        }
    }

    private function sendEmailOutbidLoser($user, $bid)
    {
        try {
            $email = (new TemplatedEmail())
                ->from("no-reply@mlp.com")
                ->to($user->getEmail())
                ->subject('My Little Poacher - Votre demande a ??t?? entendu')
                ->htmlTemplate('emails/outBidLoser.html.twig')
                ->context([
                    'user' => $user,
                    'bid' => $bid
                ]);
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new \Exception('Error sending email');
        }
    }
}

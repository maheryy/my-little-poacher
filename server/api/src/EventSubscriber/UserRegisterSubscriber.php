<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\UserRegisterToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class UserRegisterSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private MailerInterface $mailer, private ParameterBagInterface $params)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userRegister', EventPriorities::POST_WRITE]
        ];
    }

    public function userRegister(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($user instanceof User && $method === Request::METHOD_POST) {
            $token = $this->generateToken($user);

            $this->sendEmail($user, $token);
            $this->insertToken($user, $token);
        }
    }

    private function sendEmail(User $user, string $token): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from('from@example.com')
                ->to($user->getEmail())
                ->subject('My Little Poacher - Account verification')
                ->htmlTemplate('emails/registration.html.twig')
                ->context([
                    'user' => $user,
                    'url' => "{$this->params->get('app_client_url')}/login?token={$token}"
                ]);
            $this->mailer->send($email);
        } catch(\Exception $e) {
            throw new \Exception('Error sending email');
        }
    }

    private function insertToken(User $user, string $token): void
    {
        try {
            $userRegisterToken = new UserRegisterToken();
            $userRegisterToken->setToken($token);
            $userRegisterToken->setActive(true);
            $userRegisterToken->setAccount($user);

            $this->entityManager->persist($userRegisterToken);
            $this->entityManager->flush();
        } catch(\Exception $e) {
            throw new \Exception('Error inserting token');
        }
    }

    private function generateToken($user) {
        return uniqid() . '-' . sha1($user->getEmail());
    }
}

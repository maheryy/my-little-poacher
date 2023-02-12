<?php

namespace App\EventSubscriber;

use App\Entity\UserSeller;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\Security\Core\Security;


final class UserSellerRoleSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(private MailerInterface $mailer, Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userSellerPatch', EventPriorities::POST_VALIDATE]
        ];
    }

    public function userSellerPatch(ViewEvent $event): void
    {
        $userSeller = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($userSeller instanceof UserSeller && $method === Request::METHOD_PATCH) {
            
            $user = $userSeller->getSeller();
            $this->checkUser($user);
            $roles = $userSeller->getSeller()->getRoles();
            $roles[] = 'ROLE_SELLER'; 
            $userSeller->getSeller()->setRoles($roles);
            $this->sendEmailSeller($userSeller->getSeller());

        }
    }

    private function checkUser($user)
    {
        if (!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if (!$user instanceof User) {
            throw new \Exception('User not found');
        }
        return;
    }

    private function sendEmailSeller(User $user): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from("no-reply@mlp.com")
                ->to($user->getEmail())
                ->subject('My Little Poacher - Du nouveau concernant votre demande')
                ->htmlTemplate('emails/welcomeSeller.html.twig')
                ->context([
                    'user' => $user,
                ]);
            $this->mailer->send($email);
        } catch(\Exception $e) {
            throw new \Exception('Error sending email');
        }
    }

}



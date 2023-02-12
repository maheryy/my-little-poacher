<?php
//créer un subscriber sur UserSeller qui set un User juste avant un POST

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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


final class UserSellerCreateSubscriber implements EventSubscriberInterface
{
    private $security;


    public function __construct(private MailerInterface $mailer, Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userSellerCreate', EventPriorities::PRE_WRITE]
        ];
    }

    public function userSellerCreate(ViewEvent $event): void
    {
        $userSeller = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($userSeller instanceof UserSeller && $method === Request::METHOD_POST) {
            //fonctions de verification sur les infos formulaire quand il y en aura 
            //$this->AlreadyExist($userSeller);
            $user = $this->security->getUser();
            $this->checkUser($user);
            $this->AlreadyExist($user, $userSeller);
            $userSeller->setSeller($user);
            
        }
    }

    private function AlreadyExist($user, $userSeller)
    {
        
        if(in_array('ROLE_SELLER', $user->getRoles())) {
            throw new BadRequestHttpException('User already has a seller role', null, 400);
        }
        else if($user->getUserSeller()->getPendingRequest() === false) {
            throw new BadRequestHttpException('User already has a pending request', null, 400);
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
}

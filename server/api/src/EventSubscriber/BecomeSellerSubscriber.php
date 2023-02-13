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


final class BecomeSellerSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['becomeSeller', EventPriorities::POST_WRITE]
        ];
    }

    public function becomeSeller(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($user instanceof UserSeller && $method === Request::METHOD_POST) {
            $this->sendEmailSeller($user->getSeller());
            $this->sendEmailAdmin();

        }
    }

    private function sendEmailSeller(User $user): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from("no-reply@mlp.com")
                ->to($user->getEmail())
                ->subject('My Little Poacher - Votre demande a été entendu')
                ->htmlTemplate('emails/becomeSeller.html.twig')
                ->context([
                    'user' => $user,
                ]);
            $this->mailer->send($email);
        } catch(\Exception $e) {
            throw new \Exception('Error sending email');
        }
    }

    private function sendEmailAdmin(): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from("no-reply@mlp.com")
                ->to('admin@admin.com')
                ->subject('My Little Poacher - Votre demande a été entendu')
                ->htmlTemplate('emails/validateSeller.html.twig')
                ->context([ ]);
            $this->mailer->send($email);
        } catch(\Exception $e) {
            throw new \Exception('Error sending email');
        }
    }
}
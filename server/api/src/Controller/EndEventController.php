<?php

namespace App\Controller;

use App\Entity\Event;
use App\Enum\EventStatus;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class EndEventController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Event $data):Event
    {
        if($data->getCreator() !== $this->security->getUser()){
            throw new \Exception('The user is not the creator of the event.');
        }
        $data->setStatus(EventStatus::DONE);
        return $data;
    }
}
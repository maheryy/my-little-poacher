<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Enum\BidStatus;
use App\Repository\BidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class EndBidController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Bid $data): Bid
    {
        if($data->getSeller() !== $this->security->getUser()){
            throw new \Exception('The user is not valide.');
        }
        $data->setStatus(BidStatus::DONE);
        return $data;
    }
}


<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Repository\BidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class EndBidController extends AbstractController
{
    private $security;
    private $bidRepository;

    public function __construct(Security $security, BidRepository $bidRepository)
    {
        $this->security = $security;
        $this->bidRepository = $bidRepository;
    }

    public function __invoke(Bid $data, Request $request): Bid
    {
        if($data->getSeller() !== $this->security->getUser()){
            throw new \Exception('The user is not valide.');
        }
        $data->setStatus(1);
        return $data;
    }
}


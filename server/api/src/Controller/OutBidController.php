<?php

namespace App\Controller;

use App\Entity\BidLog;
use App\Entity\Bid;
use App\Repository\BidLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class OutBidController extends AbstractController
{
    private $bidLogRepository;
    private $security;

    public function __construct(BidLogRepository $bidLogRepository, Security $security)
    {
        $this->bidLogRepository = $bidLogRepository;
        $this->security = $security;
    }
    public function __invoke(Bid $bid, Request $request): Bid
    {
        ['price' => $price] = $request->toArray();

        if(!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if (!$price) {
            throw new \Exception('price is required');
        }
        if($this->security->getUser() === $bid->getSeller()) {
            throw new \Exception('Seller cannot outbid');
        }
        //dd($request->get('price'), $bid->getCurrentPrice());
        if($price <= $bid->getCurrentPrice()) {
            throw new \Exception('Price is lower or equal than current price');
        }

        $bid->setCurrentPrice($price);
        $bidLog = new BidLog();
        $bidLog->setBid($bid);
        $bidLog->setBidder($this->security->getUser());
        $bidLog->setPrice($price);
        $this->bidLogRepository->save($bidLog);

        return $bid;
    }
}

<?php

namespace App\Controller;

use App\Entity\BidLog;
use App\Entity\Bid;
use App\Entity\UserBid;
use App\Repository\BidLogRepository;
use App\Repository\UserBidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class OutBidController extends AbstractController
{
    private $userBidRepository;
    private $bidLogRepository;
    private $security;

    public function __construct(BidLogRepository $bidLogRepository, Security $security, UserBidRepository $userBidRepository)
    {
        $this->userBidRepository = $userBidRepository;
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
        if($price <= $bid->getCurrentPrice()) {
            throw new \Exception('Price is lower or equal than current price');
        }

        $userBid = $this->userBidRepository->findOneBy(['bidder' => $this->security->getUser(), 'bid' => $bid]);
        if(!$userBid) {
            $userBid = new UserBid();
            $userBid->setBid($bid);
            $userBid->setBidder($this->security->getUser());
            $userBid->setStatus('current');
            $this->userBidRepository->save($userBid);
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

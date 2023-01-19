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
    public function __invoke(Bid $data, Request $request): Bid
    {
        if(!$this->security->getUser()) {
            throw new \Exception('User is not connected');
        }
        if($this->security->getUser() === $data->getSeller()) {
            throw new \Exception('Seller cannot outbid');
        }
        if($data->getCurrentPrice() < $request->get('price')) {
            $data->setCurrentPrice($request->get('price'));
            $bidLog = new BidLog();
            $bidLog->setBid($data);
            $bidLog->setBidder($this->security->getUser());
            $bidLog->setPrice($request->get('price'));
            $this->bidLogRepository->save($bidLog);
        } else {
            throw new \Exception('Price is lower than current price');
        }

        return $data;
    }
}
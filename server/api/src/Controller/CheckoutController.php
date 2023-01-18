<?php

namespace App\Controller;

use App\Repository\BidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends AbstractController
{
    #[Route('/checkout/session', name: 'checkout_session', methods: ['POST'])]
    public function createCheckoutSession(Request $request, BidRepository $bidRepository): JsonResponse
    {
        try {
            ['bids' => $bidIds] = $request->toArray();
            if (!$bidIds || !count($bidIds)) {
                throw new \Exception("No items to purchase");
            }
            /*
                // Functionnal code but waiting for some fixtures
                $items = array_filter(array_map(function ($id) use($bidRepository) {
                    if ($bid = $bidRepository->find($id)) {
                        return [
                            'price_data' => [
                                'currency' => 'eur',
                                'product_data' => [
                                    'name' => $bid['name'],
                                ],
                                'unit_amount' => $bid['price'] * 100,
                            ],
                            'quantity' => 1,
                        ];
                    }
                    return null;
                }, $bidIds));
            */

            $items = [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "T-shirt",
                        ],
                        'unit_amount' => 35 * 100,
                    ],
                    'quantity' => 1,
                ]
            ];

            Stripe::setApiKey($this->getParameter('stripe_secret'));
            $session = Session::create([
                'line_items' => $items,
                'mode' => 'payment',
                'success_url' => $this->getParameter('app_front_url') . "/checkout/success?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => $this->getParameter('app_front_url') . "/cart",
            ]);
            return new JsonResponse(["session_id" => $session->id]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => [
                "message" => $e->getMessage()
            ]]);
        }
    }

    #[Route('/checkout/success', name: 'checkout_session', methods: ['POST'])]
    public function registerCheckout(Request $request, BidRepository $bidRepository): JsonResponse
    {
        ['session_id' => $sessionId] = $request->toArray();

        try {
            Stripe::setApiKey($this->getParameter('stripe_secret'));
            $session = Session::retrieve($sessionId);
            
            // TODO : handle payment registration after success
            $session->line_items;
            $session->customer;
            $session->amount_total;
            $session->created;
            $session->invoice;

            return new JsonResponse(["success" => true]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => [
                "message" => $e->getMessage()
            ]]);
        }
    }
}

<?php

namespace App\Controller;

use App\Enum\TicketStatus;
use App\Repository\BidRepository;
use App\Repository\TicketRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends AbstractController
{
    #[Route('/checkout/bids/session', name: 'checkout_session_bids', methods: ['POST'])]
    public function createBidCheckoutSession(Request $request, BidRepository $bidRepository): Response
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
                'success_url' => $this->getParameter('app_client_url') . "/checkout/bids?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => $this->getParameter('app_client_url') . "/cart",
            ]);
            return new JsonResponse(["redirect_url" => $session->url]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => [
                "message" => $e->getMessage()
            ]], 400);
        }
    }

    #[Route('/checkout/bids/success', name: 'checkout_success_bids', methods: ['POST'])]
    public function saveBidCheckout(Request $request, BidRepository $bidRepository): JsonResponse
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
            ]], 400);
        }
    }

    #[Route('/checkout/tickets/session', name: 'checkout_session_tickets', methods: ['POST'])]
    public function createTicketCheckoutSession(Request $request, TicketRepository $ticketRepository): Response
    {
        try {
            ['ticket' => $ticketId] = $request->toArray();


            if (!$ticketId || !($ticket = $ticketRepository->find($ticketId))) {
                throw new \Exception("No item to purchase");
            }

            if ($ticket->getStatus() !== TicketStatus::PENDING) {
                throw new \Exception("This ticket has expired");
            }

            $items = [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $ticket->getEvent()->getName(),
                        ],
                        'unit_amount' => $ticket->getEvent()->getPrice() * 100,
                    ],
                    'quantity' => 1,
                ]
            ];

            Stripe::setApiKey($this->getParameter('stripe_secret'));
            $session = Session::create([
                'line_items' => $items,
                'mode' => 'payment',
                'success_url' => $this->getParameter('app_client_url') . "/checkout/tickets?session_id={CHECKOUT_SESSION_ID}&ticket={$ticket->getId()}",
                'cancel_url' => $this->getParameter('app_client_url') . "/tickets",
            ]);
            return new JsonResponse(["redirect_url" => $session->url]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => [
                "message" => $e->getMessage()
            ]], 400);
        }
    }

    #[Route('/checkout/tickets/success', name: 'checkout_success_tickets', methods: ['POST'])]
    public function saveTicketCheckout(Request $request, TicketRepository $ticketRepository): JsonResponse
    {
        ['session_id' => $sessionId, 'ticket' => $ticketId] = $request->toArray();

        try {
            $ticket = $ticketRepository->find($ticketId);

            if (!$ticket) {
                throw new \Exception("Ticket not found");
            }

            if ($ticket->getStatus() === TicketStatus::CONFIRMED) {
                throw new \Exception("This ticket has already been confirmed");
            }

            // Quick check if the session is valid
            Stripe::setApiKey($this->getParameter('stripe_secret'));
            Session::retrieve($sessionId);

            $ticket->setStatus(TicketStatus::CONFIRMED);
            $ticket->setToken($this->generateToken());
            $ticket->setPaidAt(new DateTimeImmutable());
            $ticketRepository->save($ticket, true);

            return new JsonResponse(["success" => true]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => [
                "message" => $e->getMessage()
            ]], 400);
        }
    }

    private function generateToken()
    {
        return uniqid() . bin2hex(random_bytes(5));
    }
}

<?php

namespace App\Controller;
use App\Entity\Cart;
use App\Service\Mail\EmailQueueService;
use App\Handler\Delivery\PurchaseHandler;
use App\Handler\Delivery\GetOrdersHandler;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;    
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DeliveryController extends AbstractController
{
    protected EntityManagerInterface $em; 
    protected EmailQueueService $email_service;
    public function __construct(EntityManagerInterface $entityManager,
                                EmailQueueService $email_service)
    {
            $this->email_service = $email_service;
            $this->em = $entityManager;
    }
    #[Route('/delivery/{cart_uuid}/order', methods: ['POST'])]
    public function addOrder($cart_uuid, Request $request): JsonResponse
    {   
        $this->em->beginTransaction();
        $cart = $this->em->getRepository(Cart::class)
                     ->findOneBy(["uuid"=>Uuid::fromString($cart_uuid)]);
        $purchase_handler = new PurchaseHandler($this->em,$this->email_service);
        try{
            $message =$purchase_handler->purchaseCart($cart);
            $this->em->flush();
            $this->em->commit();
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
        return new JsonResponse($message);
    }
    #[Route('/delivery/orders', methods: ['GET'])]
    public function goOrders() {
       $handler = new GetOrdersHandler($this->em);
       $response=new Response($handler->getOrders());
       return $response;
   }
}
   

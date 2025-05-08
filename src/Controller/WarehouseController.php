<?php

namespace App\Controller;
use App\DTO\ItemRequest;
use App\Handler\Warehouse\GetItemsHandler;
use App\Handler\Warehouse\GetWarehouseHandler;
use App\Handler\Warehouse\PutItemHandler;
use App\Handler\Warehouse\PostItemHandler;
use App\Handler\Warehouse\DeleteItemHandler;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
#$uri = parse_url($_SERVER['REQUEST_URI']);
#print_r($uri);
class WarehouseController extends AbstractController
{
    protected EntityManagerInterface $em; 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
   #[Route('/warehouse', methods: ['GET'])]
   public function goWarehouses() {
       $handler = new GetWarehouseHandler($this->em);
       $response=new Response($handler->getWarehouses());
       return $response;
   }
   #[Route('/warehouse/{uuid}/items', methods: ['GET'])]
   public function goItems(string $uuid) {
       $handler = new GetItemsHandler($this->em);
       $response=new Response($handler->getItems($uuid));
       return $response;
   }
   #[Route('/warehouse/item', methods: ['PUT'])]
    public function updateItem(#[MapRequestPayload] ItemRequest $request): JsonResponse
    {   
        $handler = new PutItemHandler($this->em);
        $uuid = Uuid::fromString($request->uuid);
        $info = $request->info;
        $location_uuid = $request->location_uuid;
        $status = $request->status;
        $accessible = $request->accessible;
        $handler->putItem($uuid,$info,$location_uuid,$status,$accessible);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'info' => $info,
            'location_uuid' => $location_uuid,
            'status' => $status,
            'accessible' => $accessible
        ]);
    }
    #[Route('/warehouse/item', methods: ['POST'])]
    public function addItem(#[MapRequestPayload] ItemRequest $request): JsonResponse
    {   
        $handler = new PostItemHandler($this->em);
        $product_uuid = $request->product_uuid;
        $info = $request->info;
        $location_uuid = $request->location_uuid;
        $status = $request->status;
        $accessible = $request->accessible;
        $handler->postItem($info,$product_uuid, $status,$location_uuid, $accessible);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'product_uuid' => $product_uuid,
            'info' => $info,
            'location_uuid' => $location_uuid,
            'status' => $status,
            'accessible' => $accessible
        ]);
    }
    #[Route('/warehouse/item', methods: ['DELETE'])]
    public function removeItem(#[MapRequestPayload] ItemRequest $request): JsonResponse
    {   
        $handler = new DeleteItemHandler($this->em);
        $uuid = Uuid::fromString($request->uuid);
        $handler->deleteItem($uuid);
        return new JsonResponse([
            'message' => 'Data received successfully',
            'uuid' => $uuid
        ]);
    }
   
}



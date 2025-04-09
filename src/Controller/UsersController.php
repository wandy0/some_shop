<?php

namespace App\Controller;
use App\Handler\Users\PostUserHandler;
use App\Handler\Users\PutUserHandler;
use App\Handler\Users\GetUserHandler;
use App\Handler\Users\GetCartHandler;
use App\Handler\Users\GetProductInCartHandler;
use App\Handler\Users\DeleteUserHandler;
use App\Handler\Users\PostCartHandler;
use App\Handler\Users\DeleteCartHandler;
use App\Handler\Users\PostProductInCartHandler;
use App\Handler\Users\DeleteProductInCartHandler;
use App\Handler\Users\PutProductInCartHandler;
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
class UsersController extends AbstractController
    {
        protected EntityManagerInterface $em; 
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->em = $entityManager;
        }
        #[Route('/user/{name}', methods: ['GET'])]
        public function goUser(string $name) {
           $handler = new GetUserHandler($this->em);
           $response=new Response($handler->getAccount($name));
           return $response;
        }
        #[Route('/user/{uuid}/carts', methods: ['GET'])]
        public function goCarts(string $uuid) {
           $uuid = Uuid::fromString($uuid);
           $handler = new GetCartHandler($this->em);
           $response=new Response($handler->getCart($uuid));
           return $response;
        }
        #[Route('/user/cart/{uuid}', methods: ['GET'])]
        public function goProductInCarts(string $uuid) {
           $uuid = Uuid::fromString($uuid);
           $handler = new GetProductInCartHandler($this->em);
           $response=new Response($handler->getProducts($uuid));
           return $response;
        }
        #[Route('/user/new', methods: ['POST'])]
        public function addProduct(Request $request): JsonResponse
            {   
                $handler = new PostUserHandler($this->em);
                $data = json_decode($request->getContent(), true);
         
                $info = $data['info'] ?? null;
                $handler->postUser($info);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'info' => $info
                ]);
            }
        #[Route('/user/update', methods: ['PUT'])]
        public function updateUser(Request $request): JsonResponse
            {   
                $handler = new PutUserHandler($this->em);
                $data = json_decode($request->getContent(), true);
                $uuid = Uuid::fromString($data["uuid"]);
                $info = $data['info'] ?? null;
                $handler->putUser($uuid,$info);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'info' => $info,
                ]);
            }
        #[Route('/user/delete', methods: ['DELETE'])]
        public function removeUser(Request $request): JsonResponse
            {   
                $handler = new DeleteUserHandler($this->em);
                $data = json_decode($request->getContent(), true);
                $uuid = Uuid::fromString($data["uuid"]);
                $handler->deleteUser($uuid);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'uuid' => $uuid
                ]);
            }
        #[Route('/user/{uuid}/cart', methods: ['POST'])]
        public function addCart(string $uuid)
            {
                $uuid = Uuid::fromString($uuid);
                $handler = new PostCartHandler($this->em);
         
                $handler->postCart($uuid);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'uuid' => $uuid
                ]);
            }
        #[Route('/user/{uuid}/cart', methods: ['DELETE'])]
        public function removeCart(string $uuid): JsonResponse
            {   
                $uuid = Uuid::fromString($uuid);
                $handler = new DeleteCartHandler($this->em);
                $handler->deleteCart($uuid);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'uuid' => $uuid
                ]);
            }
        #[Route('/user/cart/{uuid}', methods: ['POST'])]
        public function addProductsToCart(Request $request,string $uuid)
            {
                $uuid = Uuid::fromString($uuid);
                $handler = new PostProductInCartHandler($this->em);
                $data = json_decode($request->getContent(), true);
                $product_uuid = Uuid::fromString($data["product_uuid"]);
                $amount = $data["amount"];
                $handler->addProduct($uuid,$product_uuid,$amount);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'uuid' => $uuid,
                    'product_uuid' => $product_uuid,
                    'amount' => $amount
                ]);
            }
        #[Route('/user/cart/{uuid}', methods: ['DELETE'])]
        public function removeProductsFromCart(Request $request,string $uuid)
            {
                $uuid = Uuid::fromString($uuid);
                $handler = new DeleteProductInCartHandler($this->em);
                $data = json_decode($request->getContent(), true);
                $product_uuid = Uuid::fromString($data["product_uuid"]);
                $handler->removeProduct($uuid,$product_uuid);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'uuid' => $uuid,
                    'product_uuid' => $product_uuid
                ]);
            }
        #[Route('/user/cart/{uuid}', methods: ['PUT'])]
        public function putProductInCart(Request $request,string $uuid)
            {
                $uuid = Uuid::fromString($uuid);
                $handler = new PutProductInCartHandler($this->em);
                $data = json_decode($request->getContent(), true);
                $product_uuid = Uuid::fromString($data["product_uuid"]);
                $amount = $data["amount"];
                $handler->putProduct($uuid,$product_uuid,$amount);
                return new JsonResponse([
                    'message' => 'Data received successfully',
                    'uuid' => $uuid,
                    'product_uuid' => $product_uuid,
                    'amount' => $amount
                ]);
            }
    }


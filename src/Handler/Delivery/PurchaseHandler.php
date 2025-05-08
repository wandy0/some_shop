<?php

namespace App\Handler\Delivery;
use App\Handler\Warehouse\PutItemHandler;
use App\Service\Mail\EmailQueueService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\OrderStatus;
use App\Entity\Order;
use App\Entity\Cart;
class PurchaseHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager,
            private EmailQueueService $email_service)
    {
        $this->entityManager = $entityManager;
    }

    public function purchaseCart(Cart $cart) : array
    {
        $updatehandler = new PutItemHandler($this->entityManager);
        $user=$cart->getUser();
        $cartUuid = $cart->getUuid();
        $balance=$user->getBalance();
        $items_condition = $this->getItemsCondition($cartUuid);
        if ($items_condition['is_fully_in_stock']){
            if ($items_condition['total_price']<= $balance){
                $user->setBalance($balance-$items_condition['total_price']);
                $balance=$user->getBalance();
                
                $items_condition["items"]= json_decode($items_condition["items"],true);
                foreach ($items_condition["items"] as $items){
                    for($i=0;$i<$items["demanded_amount"];$i++){
                        $uuid= $items["items"]["uuids"][$i];
                        $updatehandler->putItem($uuid,status: 2,accessible: false, flush:false);
                    }
                }
                $recipient = $user->getInfo()["mail"] ?? null;
                if ($recipient){
                    $this->email_service->queueEmail($recipient,"your purchase",
                            "you made an order");
                }
                $order = new Order;
                $order->setCart($cart);
                $order->setStaus($this->entityManager
                      ->getRepository(OrderStatus::class)
                      ->find(1));
                $this->entityManager->persist($order);

                return ["message"=> 'ok',
                    "total_price"=>$items_condition['total_price'],
                    "balance"=> $balance];
            }
            else{
                return ["message"=> 'not enough money',
                    "total_price"=>$items_condition['total_price'],
                    "balance"=> $balance];
            }
        }
        else{
            return ["message"=> 'not enough items'];
        }
    }
    private function getItemsCondition($cartUuid)
    {
        $sql = "
                WITH available_items as (
                    SELECT uuid, product_uuid
                    FROM items
                    WHERE accessible = true
                    FOR UPDATE
                ), item_count as (
                    SELECT array_agg(uuid) as uuids,available_items.product_uuid, COUNT(*) as ic
                    FROM available_items
                    LEFT JOIN products_in_carts pic ON pic.product_uuid = available_items.product_uuid
                    GROUP BY available_items.product_uuid
                )
                SELECT
                SUM(pic.amount * pr.price) AS total_price,
                MIN(
                    CASE
                        WHEN COALESCE(item_count.ic, 0) >= pic.amount
                        THEN 1
                        ELSE 0
                    END
                ) AS is_fully_in_stock,
                json_agg(
                    json_build_object(
                        'items',item_count,
                        'demanded_amount', pic.amount
                    )
                ) AS items
                FROM products_in_carts pic
                LEFT JOIN item_count ON item_count.product_uuid = pic.product_uuid
                INNER JOIN products p ON p.uuid = pic.product_uuid
                INNER JOIN prices pr ON pr.uuid = p.price_uuid
                WHERE pic.cart_uuid = :cart_uuid
                ";
        $result = $this->entityManager->getConnection()->executeQuery($sql, [
            'cart_uuid' => $cartUuid])->fetchAssociative();
        return $result;
    }
}


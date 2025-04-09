<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'products_in_carts')]
class ProductInCart
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_uuid', referencedColumnName: 'uuid')]
    private Product $product;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cart::class)]
    #[ORM\JoinColumn(name: 'cart_uuid', referencedColumnName: 'uuid')]
    private Cart $cart;

    #[ORM\Column(type: 'integer')]
    private int $amount;

    public function getProduct(): Product
    {
        return $this->product;
    }

     public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}


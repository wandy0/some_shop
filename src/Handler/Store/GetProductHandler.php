<?php
namespace App\Handler\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Ramsey\Uuid\Uuid;


class GetProductHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProduct($uuid)
    {
        $uuid = Uuid::fromString($uuid);
        $product = $this->entityManager
                ->getRepository(Product::class)
                ->findOneBy(["uuid"=>$uuid]);
        if ($product){
            return json_encode(["uuid"=>$uuid,
                                "info"=>$product->getInfo(),
                                "category"=>$product->getCategory()->getUuid()]);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}
?>

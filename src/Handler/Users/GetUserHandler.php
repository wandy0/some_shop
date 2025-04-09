<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class GetUserHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAccount(string $name)
    {
        $user = $this->entityManager->createQueryBuilder()
                     ->select("u")
                     ->from(User::class,'u')
                     ->where("JSON_GET_FIELD_AS_TEXT(u.info,'name') = :name")
                     ->setParameter("name",$name)
                     ->getQuery()
                     ->getOneOrNullResult();
        if ($user){
            return json_encode(["uuid"=>$user->getUuid(),
                                "info"=>$user->getInfo(),]);
        }
        else{
            return json_encode(["error"=>404]);
        }
    }
}

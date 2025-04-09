<?php
namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class PutUserHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function putUser( $uuid,
                               $info=[]) : void
    {
        $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(["uuid"=>$uuid]);
        if($info){
            $user->setInfo($info);
        }   
        $this->entityManager->flush();
    }
}


<?php

namespace App\Handler\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;

class DeleteUserHandler extends AbstractController{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteUser($uuid) : void
    {
        $user = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(["uuid"=>$uuid]);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}

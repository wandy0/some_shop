<?php

namespace App\Service\Mail;

use App\Entity\EmailMessage;
use App\DTO\MailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\EntityManagerInterface;

class EmailQueueService
{
    public function __construct(private EntityManagerInterface $em,
                                private MessageBusInterface $bus) {}

public function queueEmail(string $to, string $subject, string $body): void
    {
        $email = new EmailMessage($to, $subject, $body);
        $this->em->persist($email);
        $this->em->flush();
        $this->bus->dispatch(new MailMessage($email->getId()));
    }
}


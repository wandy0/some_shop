<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "email_messages")]
class EmailMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $recipient;

    #[ORM\Column(length: 255)]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(length: 20)]
    private string $status = 'pending'; // 'pending', 'sent', 'failed'

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $sentAt = null;

    public function __construct(string $recipient, string $subject, string $body)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->body = $body;
        $this->createdAt = new \DateTime();
    }
    
    public function getId(): int {
        return $this->id;
    }
    public function getRecipient(): string {
        return $this->recipient;
    }
    public function getSubject(): string {
        return $this->subject;
    }
    public function getBody(): string {
        return $this->body;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function getCreatedAt(): \DateTimeInterface {
        return $this->createdAt;
    }
    public function getSentAt(): \DateTimeInterface {
        return $this->sentAt;
    }
    public function setRecipient(String $recipient) {
        $this->recipient = $recipient;
    }
    public function setSubject(String $subject) {
        $this->subject = $subject;
    }
    public function setBody(String $body) {
        $this->body = $body;
    }
    public function setStatus(String $status) {
        $this->status = $status;
    }
    public function setCreatedAt(\DateTimeInterface $createdat)  {
        $this->createdAt = $createdat;
    }
    public function setSentAt(\DateTimeInterface $sentat)  {
        $this->sentAt = $sentat;
    }
}


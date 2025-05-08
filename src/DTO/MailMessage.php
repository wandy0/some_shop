<?php
namespace App\DTO;

class MailMessage
{
    public function __construct(private int $mail_id) {}

    public function getMailId(): int
    {
        return $this->mail_id;
    }
}

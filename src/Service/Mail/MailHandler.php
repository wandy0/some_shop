<?php
namespace App\Service\Mail;

use App\DTO\MailMessage;
use App\Entity\EmailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MailHandler
{
    private $repository;
    public function __construct(private EntityManagerInterface $em,
            private MailerInterface $mailer) {
        $this->repository=$em->getRepository(EmailMessage::class);
    }

    public function __invoke(MailMessage $mail): void
    {
        $mail = $this->repository->find($mail->getMailId());
        if (!$mail || $mail->getStatus() !== 'pending') {
            return;
        }

        try {
            $email = (new Email())
                ->from('MS_Dzu6tV@test-p7kx4xww5jmg9yjr.mlsender.net')
                ->to($mail->getRecipient())
                ->subject($mail->getSubject())
                ->text($mail->getBody());

            $this->mailer->send($email);
            $mail->setStatus('sent');
            $mail->setSentAt(new \DateTime());
        } catch (\Throwable $e) {
            $mail->setStatus('failed');
        }

        $this->em->flush();
    }
}


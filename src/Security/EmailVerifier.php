<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
  * Handles email verification for creation user accounts
  *
  * This service sends and handles email confirmations when creating a new account
  */
class EmailVerifier
{
    /**
     * @param VerifyEmailHelperInterface $verifyEmailHelper Generates and validates signed URLs
     * @param MailerInterface            $mailer            Sends templated emails
     * @param EntityManagerInterface     $entityManager     Persists user changes
     */
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Sends a confirmation email to the user
     *
     * @param string         $verifyEmailRouteName Route name for handling email confirmations
     * @param User           $user                 The user to send the email to
     * @param TemplatedEmail $email                A prepared email template
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            (string) $user->getEmail()
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * Validates an email confirmation request and marks the user as verified
     *
     * @param Request $request The email confirmation request send to the users email
     * @param User    $user    The user to mark as verified
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), (string) $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

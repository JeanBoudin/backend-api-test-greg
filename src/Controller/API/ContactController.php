<?php

// src/Controller/ContactController.php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ContactEntry;

class ContactController extends AbstractController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/api/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;
        $message = $data['message'] ?? null;
        $recaptchaResponse = $data['recaptchaToken'] ?? null;

        if (!$name || !$email || !$message || !$recaptchaResponse) {
            return $this->json(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        // Vérification du jeton reCAPTCHA
        $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => '6LfvKhQqAAAAAMDGrM3_jk9kivkCccCS3PhMUG38', // Remplacez par votre clé secrète reCAPTCHA
                'response' => $recaptchaResponse
            ]
        ]);

        $responseData = $response->toArray();

        if (!$responseData['success']) {
            return $this->json(['error' => 'Invalid reCAPTCHA'], Response::HTTP_BAD_REQUEST);
        }

        // Persister les données en base de données
        $contactEntry = new ContactEntry();
        $contactEntry->setUsername($name)
            ->setEmail($email)
            ->setMessage($message)
            ->setCreatedAt(new \DateTimeImmutable());
        $em->persist($contactEntry);
        $em->flush();

        try {
            // Envoi des e-mails
            // Email à vous-même
            $emailToMe = (new Email())
                ->from('candidature@gregdilon.fr')
                ->to('candidature@gregdilon.fr')
                ->subject('Nouveau message de contact')
                ->text("Nom: $name\nEmail: $email\nMessage: $message");

            $mailer->send($emailToMe);

            // Email de retour à l'utilisateur
            $emailToUser = (new Email())
                ->from('candidature@gregdilon.fr')
                ->to($email)
                ->subject('Merci pour votre message')
                ->text("Bonjour $name,\n\nMerci pour votre message. Nous vous répondrons dès que possible.\n\nCordialement,\nVotre équipe");

            $mailer->send($emailToUser);

            return $this->json(['status' => 'Emails sent'], Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->json(['status' => 'Failed to send emails', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller for the Security
 *
 * Handles the login requests
 *
 * @extends AbstractController
 */
class SecurityController extends AbstractController
{
    /**
     * Renders the login page
     * @param AuthenticationUtils $authenticationUtils The authentication utils
     * @return Response with the rendering of the login page
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('secuSrity/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}

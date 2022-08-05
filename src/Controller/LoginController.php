<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route ("/redirect/", name="redirect")
     */

    public function onAuthenticationSuccess(Request $request)
    {
        $user = $this->getUser();
        if($user != 'anon'){
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return $this->redirectToRoute('admin_home');
            }
            if (in_array('ROLE_USER', $user->getRoles(), true)) {
                return $this->redirectToRoute('profile_home');
            }
        }
        return $this->redirectToRoute('home');
    }
}

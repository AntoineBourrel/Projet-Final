<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileUserController extends AbstractController
{
    /**
     * @Route("/profile/user-profil/{id}", name="profile_user_profil")
     */
    public function userProfil($id, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        return $this->render('profile/user-profil.html.twig',[
            'user' => $user
        ]);
    }
}
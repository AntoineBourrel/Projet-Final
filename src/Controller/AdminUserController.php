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

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user-list/", name="admin_user_list")
     */
    public function userList(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        return $this->render('Admin/user-list.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user-insert/", name="admin_user_insert")
     */
    public function userInsert(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();
        // Création d'un formulaire lié à la table User via ses paramètres lié à l'instance d'Article
        $form = $this->createForm(UserType::class, $user);
        // On donne la variable form une instance de Request pour que le formulaire puisse
        // récupérer les données et les traiter automatiquement
        $form->handleRequest($request);
        // Si le formulaire à été posté et que les données sont valides, on envoie sur la base de données
        if($form->isSubmitted() && $form->isValid()){

            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $roles = $form->get('roles')->getData();
            if($roles){
                $user->setRoles(["ROLE_ADMIN"]);
            } else{
                $user->setRoles(["ROLE_USER"]);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "Vous avez bien ajouté l'utilisateur");

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user-insert.html.twig',[
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user-delete/{id}", name="admin_user_delete")
     */
    public function userDelete($id, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        if(!is_null($user))
        {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', "Vous avez bien supprimé l'utilisateur ");
            return $this->redirectToRoute('admin_user_list');
        }
        $this->addFlash('error', 'Admin introuvable');
        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("/admin/user-update/{id}", name="admin_user_update")
     */
    public function userUpdate($id, UserRepository $userRepository,EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = $userRepository->find($id);
        // Création d'un formulaire lié à la table User via ses paramètres lié à l'instance d'Article
        $form = $this->createForm(UserType::class, $user);
        // On donne la variable form une instance de Request pour que le formulaire puisse
        // récupérer les données et les traiter automatiquement
        $form->handleRequest($request);
        // Si le formulaire à été posté et que les données sont valides, on envoie sur la base de données
        if($form->isSubmitted() && $form->isValid()){

            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $roles = $form->get('roles')->getData();
            if($roles){
                $user->setRoles(["ROLE_ADMIN"]);
            } else{
                $user->setRoles(["ROLE_USER"]);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "Vous avez bien modifié l'utilisateur");

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user-update.html.twig',[
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }
}
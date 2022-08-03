<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\User;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileOffreController extends AbstractController
{
    /**
     * @Route("/profile/offre/", name="profile_offre_choice")
     */
    public function offreChoice()
    {
        return $this->render('profile/offre.html.twig');
    }

    /**
     * @Route("/profile/offre-list-magic/", name="profile_offre_list_magic")
     */
    public function offreListMagic(OffreRepository $offreRepository)
    {
        $offresMagic = $offreRepository->findBy(array('game' => 'Magic the Gathering'));
        return $this->render('profile/offre-list-magic.html.twig', [
            'offres' => $offresMagic
        ]);
    }

    /**
     * @Route("/profile/offre-list-pokemon/", name="profile_offre_list_pokemon")
     */
    public function offreListPokemon(OffreRepository $offreRepository)
    {
        $offresPokemon = $offreRepository->findBy(array('game' => 'Pokemon'));
        return $this->render('profile/offre-list-pokemon.html.twig', [
            'offres' => $offresPokemon
        ]);
    }

    /**
     * @Route("/profile/offre-list-yugioh/", name="profile_offre_list_yugioh")
     */
    public function offreListYugioh(OffreRepository $offreRepository)
    {
        $offresYugioh = $offreRepository->findBy(array('game' => 'Yu-Gi-Oh'));
        return $this->render('profile/offre-list-yugioh.html.twig', [
            'offres' => $offresYugioh
        ]);
    }

    /**
     * @Route("/profile/offre-show/{id}", name="profile_offre_show")
     */
    public function offreShow($id, OffreRepository $offreRepository)
    {
        $offre = $offreRepository->find($id);
        return $this->render('profile/offre-show.html.twig', [
            'offre' => $offre
        ]);
    }

    /**
     * @Route("/profile/offre-insert", name="profile_offre_insert")
     */
    public function offreInsert(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $offre = new Offre();
        $offre->setPublishedAt(new \DateTime('now'));
        $user = $this->getUser();
        $offre->setUser($user);
        // Création d'un formulaire lié à la table Offre via ses paramètres lier à l'instance de Book
        $form = $this->createForm(OffreType::class, $offre);

        // On donne la variable form une instance de Request pour que le formulaire puisse
        // récupérer les données et les traiter automatiquement
        $form->handleRequest($request);

        // Si le formulaire à été posté et que les données sont valides, on envoie sur la base de données
        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            $image->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            $offre->setImage($newFilename);

            $entityManager->persist($offre);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien ajouté votre Offre');
        }
        return $this->render('profile/offre-insert.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/offre-delete/{id}", name="profile_offre_delete")
     */
    public function offreDelete($id, EntityManagerInterface $entityManager, OffreRepository  $offreRepository)
    {
        $offre = $offreRepository->find($id);
        if(!is_null($offre))
        {
            $entityManager->remove($offre);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien supprimé votre offre');
            return $this->redirectToRoute('profile_home');
        }
        $this->addFlash('error', 'Offre introuvable');
        return $this->redirectToRoute('profile_home');
    }

    /**
     * @Route("/profile/offre-update/{id}", name="profile_offre_update")
     */
    public function offreUpdate($id, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, OffreRepository $offreRepository)
    {
        $offre = $offreRepository->find($id);
        $offre->setPublishedAt(new \DateTime('now'));
        $user = $this->getUser();
        $offre->setUser($user);
        // Création d'un formulaire lié à la table Offre via ses paramètres lier à l'instance de Book
        $form = $this->createForm(OffreType::class, $offre);

        // On donne la variable form une instance de Request pour que le formulaire puisse
        // récupérer les données et les traiter automatiquement
        $form->handleRequest($request);

        // Si le formulaire à été posté et que les données sont valides, on envoie sur la base de données
        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            $image->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            $offre->setImage($newFilename);

            $entityManager->persist($offre);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien ajouté votre Offre');
        }
        return $this->render('profile/offre-insert.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\DemandeType;
use App\Form\OffreType;
use App\Repository\DemandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileDemandeController extends AbstractController
{
    /**
     * @Route("/profile/demande/", name="profile_demande_choice")
     */
    public function demandeChoice()
    {
        return $this->render('profile/demande.html.twig');
    }

    /**
     * @Route("/profile/demandee-list-magic/", name="profile_demande_list_magic")
     */
    public function demandeListMagic(DemandeRepository $demandeRepository)
    {
        $demandesMagic = $demandeRepository->findBy(array('game' => 'Magic the Gathering'));
        return $this->render('profile/demande-list-magic.html.twig', [
            'demandes' => $demandesMagic
        ]);
    }

    /**
     * @Route("/profile/demande-list-pokemon/", name="profile_demande_list_pokemon")
     */
    public function demandeListPokemon(DemandeRepository $demandeRepository)
    {
        $demandesPokemon = $demandeRepository->findBy(array('game' => 'Pokemon'));
        return $this->render('profile/demande-list-pokemon.html.twig', [
            'demandes' => $demandesPokemon
        ]);
    }

    /**
     * @Route("/profile/demande-list-yugioh/", name="profile_demande_list_yugioh")
     */
    public function demandeListYugioh(DemandeRepository $demandeRepository)
    {
        $demandesYugioh = $demandeRepository->findBy(array('game' => 'Yu-Gi-Oh'));
        return $this->render('profile/demande-list-yugioh.html.twig', [
            'demandes' => $demandesYugioh
        ]);
    }

    /**
     * @Route("/profile/demande-show/{id}", name="profile_demande_show")
     */
    public function demandeShow($id, DemandeRepository $demandeRepository)
    {
        $demande = $demandeRepository->find($id);

        return $this->render('profile/demande-show.html.twig', [
            'demande' => $demande
        ]);
    }

    /**
     * @Route("/profile/demande-insert/", name="profile_demande_insert")
     */
    public function demandeInsert(EntityManagerInterface $entityManager, Request $request)
    {
        $demande = new Demande();
        $demande->setPublishedAt(new \DateTime('now'));
        $user = $this->getUser();
        $demande->setUser($user);
        // Création d'un formulaire lié à la table Article via ses paramètres lié à l'instance d'Article
        $form = $this->createForm(DemandeType::class, $demande);

        // On donne la variable form une instance de Request pour que le formulaire puisse
        // récupérer les données et les traiter automatiquement
        $form->handleRequest($request);

        // Si le formulaire à été posté et que les données sont valides, on envoie sur la base de données
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($demande);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien ajouté votre demande');
        }

        return $this->render('profile/demande-insert.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/demande-update/{id}", name="profile_demande_update")
     */
    public function demandeUpdate($id, EntityManagerInterface $entityManager, Request $request, DemandeRepository $demandeRepository)
    {
        $demande = $demandeRepository->find($id);
        $demande->setPublishedAt(new \DateTime('now'));
        $user = $this->getUser();
        $demande->setUser($user);
        // Création d'un formulaire lié à la table Article via ses paramètres lié à l'instance d'Article
        $form = $this->createForm(DemandeType::class, $demande);

        // On donne la variable form une instance de Request pour que le formulaire puisse
        // récupérer les données et les traiter automatiquement
        $form->handleRequest($request);

        // Si le formulaire à été posté et que les données sont valides, on envoie sur la base de données
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($demande);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien modifié votre demande');
        }

        return $this->render('profile/demande-update.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/demande-delete/{id}", name="profile_demande_delete")
     */
    public function demandeDelete($id, EntityManagerInterface $entityManager, DemandeRepository $demandeRepository)
    {
        $demande = $demandeRepository->find($id);
        if(!is_null($demande))
        {
            $entityManager->remove($demande);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien supprimé votre demande');
            return $this->redirectToRoute('profile_home');
        }
        $this->addFlash('error', 'Demande introuvable');
        return $this->redirectToRoute('profile_home');
    }
}
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

class AdminDemandeController extends AbstractController
{
    /**
     * @Route("/admin/demande/", name="admin_demande_choice")
     */
    public function demandeChoice()
    {
        return $this->render('admin/demande.html.twig');
    }

    /**
     * @Route("/admin/demandee-list-magic/", name="admin_demande_list_magic")
     */
    public function demandeListMagic(DemandeRepository $demandeRepository)
    {
        $demandesMagic = $demandeRepository->findBy(array('game' => 'Magic the Gathering'));
        return $this->render('admin/demande-list-magic.html.twig', [
            'demandes' => $demandesMagic
        ]);
    }

    /**
     * @Route("/admin/demande-list-pokemon/", name="admin_demande_list_pokemon")
     */
    public function demandeListPokemon(DemandeRepository $demandeRepository)
    {
        $demandesPokemon = $demandeRepository->findBy(array('game' => 'Pokemon'));
        return $this->render('admin/demande-list-pokemon.html.twig', [
            'demandes' => $demandesPokemon
        ]);
    }

    /**
     * @Route("/admin/demande-list-yugioh/", name="admin_demande_list_yugioh")
     */
    public function demandeListYugioh(DemandeRepository $demandeRepository)
    {
        $demandesYugioh = $demandeRepository->findBy(array('game' => 'Yu-Gi-Oh'));
        return $this->render('admin/demande-list-yugioh.html.twig', [
            'demandes' => $demandesYugioh
        ]);
    }

    /**
     * @Route("/admin/demande-show/{id}", name="admin_demande_show")
     */
    public function demandeShow($id, DemandeRepository $demandeRepository)
    {
        $demande = $demandeRepository->find($id);

        return $this->render('admin/demande-show.html.twig', [
            'demande' => $demande
        ]);
    }

    /**
     * @Route("/admin/demande-insert/", name="admin_demande_insert")
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

        return $this->render('admin/demande-insert.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/demande-update/{id}", name="admin_demande_update")
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

        return $this->render('admin/demande-update.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/demande-delete/{id}", name="admin_demande_delete")
     */
    public function demandeDelete($id, EntityManagerInterface $entityManager, DemandeRepository $demandeRepository)
    {
        $demande = $demandeRepository->find($id);
        if(!is_null($demande))
        {
            $entityManager->remove($demande);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien supprimé votre demande');
            return $this->redirectToRoute('admin_demande_choice');
        }
        $this->addFlash('error', 'Demande introuvable');
        return $this->redirectToRoute('admin_demande_choice');
    }
}
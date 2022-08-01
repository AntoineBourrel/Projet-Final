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

class FrontDemandeController extends AbstractController
{
    /**
     * @Route("/demande/", name="demande_choice")
     */
    public function demandeChoice()
    {
        return $this->render('front/demande.html.twig');
    }

    /**
     * @Route("/demandee-list-magic/", name="demande_list_magic")
     */
    public function demandeListMagic(DemandeRepository $demandeRepository)
    {
        $demandesMagic = $demandeRepository->findBy(array('game' => 'Magic the Gathering'));
        return $this->render('front/demande-list-magic.html.twig', [
            'demandes' => $demandesMagic
        ]);
    }

    /**
     * @Route("/demande-list-pokemon/", name="demande_list_pokemon")
     */
    public function demandeListPokemon(DemandeRepository $demandeRepository)
    {
        $demandesPokemon = $demandeRepository->findBy(array('game' => 'Pokemon'));
        return $this->render('front/demande-list-pokemon.html.twig', [
            'demandes' => $demandesPokemon
        ]);
    }

    /**
     * @Route("/demande-list-yugioh/", name="demande_list_yugioh")
     */
    public function demandeListYugioh(DemandeRepository $demandeRepository)
    {
        $demandesYugioh = $demandeRepository->findBy(array('game' => 'Yu-Gi-Oh'));
        return $this->render('front/demande-list-yugioh.html.twig', [
            'demandes' => $demandesYugioh
        ]);
    }

    /**
     * @Route("/demande-show/{id}", name="demande_show")
     */
    public function demandeShow($id, DemandeRepository $demandeRepository)
    {
        $demande = $demandeRepository->find($id);

        return $this->render('front/demande-show.html.twig', [
            'demande' => $demande
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\User;
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
    public function offreListMagic(DemandeRepository $demandeRepository)
    {
        $demandesMagic = $demandeRepository->findBy(array('game' => 'Magic the Gathering'));
        return $this->render('admin/demande-list-magic.html.twig', [
            'demandes' => $demandesMagic
        ]);
    }

    /**
     * @Route("/admin/demande-list-pokemon/", name="admin_demande_list_pokemon")
     */
    public function offreListPokemon(DemandeRepository $demandeRepository)
    {
        $demandesPokemon = $demandeRepository->findBy(array('game' => 'Pokemon'));
        return $this->render('admin/demande-list-pokemon.html.twig', [
            'demandes' => $demandesPokemon
        ]);
    }

    /**
     * @Route("/admin/demande-list-yugioh/", name="admin_demande_list_yugioh")
     */
    public function offreListYugioh(DemandeRepository $demandeRepository)
    {
        $demandesYugioh = $demandeRepository->findBy(array('game' => 'Yu-Gi-Oh'));
        return $this->render('admin/demande-list-yugioh.html.twig', [
            'demandes' => $demandesYugioh
        ]);
    }
}
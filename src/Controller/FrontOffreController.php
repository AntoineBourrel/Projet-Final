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

class FrontOffreController extends AbstractController
{
    /**
     * @Route("/offre/", name="offre_choice")
     */
    public function offreChoice()
    {
        return $this->render('front/offre.html.twig');
    }

    /**
     * @Route("/offre-list-magic/", name="offre_list_magic")
     */
    public function offreListMagic(OffreRepository $offreRepository)
    {
        $offresMagic = $offreRepository->findBy(array('game' => 'Magic the Gathering'));
        return $this->render('front/offre-list-magic.html.twig', [
            'offres' => $offresMagic
        ]);
    }

    /**
     * @Route("/offre-list-pokemon/", name="offre_list_pokemon")
     */
    public function offreListPokemon(OffreRepository $offreRepository)
    {
        $offresPokemon = $offreRepository->findBy(array('game' => 'Pokemon'));
        return $this->render('front/offre-list-pokemon.html.twig', [
            'offres' => $offresPokemon
        ]);
    }

    /**
     * @Route("/offre-list-yugioh/", name="offre_list_yugioh")
     */
    public function offreListYugioh(OffreRepository $offreRepository)
    {
        $offresYugioh = $offreRepository->findBy(array('game' => 'Yu-Gi-Oh'));
        return $this->render('front/offre-list-yugioh.html.twig', [
            'offres' => $offresYugioh
        ]);
    }

    /**
     * @Route("/offre-show/{id}", name="offre_show")
     */
    public function offreShow($id, OffreRepository $offreRepository)
    {
        $offre = $offreRepository->find($id);
        return $this->render('front/offre-show.html.twig', [
            'offre' => $offre
        ]);
    }
}
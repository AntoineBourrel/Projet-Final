<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\DemandeType;
use App\Form\OffreType;
use App\Repository\DemandeRepository;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SearchController extends AbstractController
{
    //méthode de recherche d'un titre dans la bdd
    /**
     * @Route("/admin/search", name="admin_search")
     */
    public function search(Request $request, OffreRepository $offreRepository, DemandeRepository $demandeRepository){
        // Récupération valeur GET dans l'URL
        $search = $request->query->get('search');

        // je vais créer une méthode dans le BookRepository
        // qui trouve un livre en fonction d'un mot dans son titre
        $offres = $offreRepository->searchByWord($search);
        $demandes = $demandeRepository->searchByWord($search);


        // Renvoie vers le fichier twig
        return $this->render('admin/search.html.twig', [
            'offres' => $offres,
            'demandes' =>$demandes
        ]);
    }
}

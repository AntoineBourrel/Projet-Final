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

class AdminOffreController extends AbstractController
{
    /**
     * @Route("/admin/offre-list/", name="admin_offre_list")
     */
    public function offreList(OffreRepository $offreRepository)
    {
        $offres = $offreRepository->findAll();
        return $this->render('admin/offre-list.html.twig', [
            'offres' => $offres
        ]);
    }

    /**
     * @Route("/admin/offre-show/{id}", name="admin_offre_show")
     */
    public function offreShow($id, OffreRepository $offreRepository)
    {
        $offre = $offreRepository->find($id);
        return $this->render('admin/offre-show.html.twig', [
            'offre' => $offre
        ]);
    }

    /**
     * @Route("/admin/offre-insert", name="admin_offre_insert")
     */
    public function offreInsert(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $offre = new Offre();
        $offre->setPublishedAt(new \DateTime('now'));
        $user = $this->getUser();
        $offre->setUser($user);
        // Création d'un formulaire lié à la table Article via ses paramètres lié à l'instance de Book
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
        return $this->render('Admin/offre-insert.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }
}
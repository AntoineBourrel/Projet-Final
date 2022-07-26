<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminArticleController extends AbstractController
{
    /**
     * @Route("/admin/article-list/", name="admin_article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->render('Admin/article-list.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/article-show/{id}", name="admin_article_show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        return $this->render('Admin/article-show.html.twig',[
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin/article-insert/", name="admin_article_insert")
     */
    public function articleInsert(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $article = new Article();
        $article->setPublishedAt(new \DateTime('now'));
        $user = $this->getUser();
        $article->setUser($user);
        // Création d'un formulaire lié à la table Article via ses paramètres lié à l'instance de Book
        $form = $this->createForm(ArticleType::class, $article);

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

            $article->setImage($newFilename);

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien ajouté votre Article');
        }
        return $this->render('Admin/article-insert.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article-delete/{id}", name="admin_article_delete")
     */
    public function articleDelete($id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        if(!is_null($article))
        {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien supprimé votre article');
            return $this->redirectToRoute('admin_article_list');
        }
        $this->addFlash('error', 'Article introuvable');
        return $this->redirectToRoute('admin_article_list');
    }

    /**
     * @Route ("/admin/article-update/{id}", name="admin_article_update")
     */
    public function articleUpdate($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger)
    {
        $article = $articleRepository->find($id);
        $article->setPublishedAt(new \DateTime('now'));
        // Création d'un formulaire lié à la table Article via ses paramètres lié à l'instance de Book
        $form = $this->createForm(ArticleType::class, $article);

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

            $article->setImage($newFilename);

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien modifié votre Article');
        }
        return $this->render('Admin/article-update.html.twig', [
            // Utilisation de la méthode createView pour créer la view du formulaire
            'form' => $form->createView()
        ]);
    }
}
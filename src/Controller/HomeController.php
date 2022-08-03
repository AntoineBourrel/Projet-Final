<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function frontHome(ArticleRepository $articleRepository){
        $lastArticles = $articleRepository->findBy([], ['id' => 'DESC'],3);
        return $this->render('front/home.html.twig', [
            'lastArticles' => $lastArticles
        ]);
    }

    /**
     * @Route("/admin/", name="admin_home")
     */
    public function adminHome(ArticleRepository $articleRepository){
        $lastArticles = $articleRepository->findBy([], ['id' => 'DESC'],3);
        return $this->render('admin/home.html.twig', [
            'lastArticles' => $lastArticles
        ]);
    }

    /**
     * @Route("/profile/", name="profile_home")
     */
    public function userHome(ArticleRepository $articleRepository){
        $lastArticles = $articleRepository->findBy([], ['id' => 'DESC'],3);
        return $this->render('profile/home.html.twig', [
            'lastArticles' => $lastArticles
        ]);
    }
}
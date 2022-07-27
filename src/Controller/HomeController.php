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
     * @Route("/admin/", name="admin_home")
     */
    public function home(ArticleRepository $articleRepository){
        $lastArticles = $articleRepository->findBy([], ['id' => 'DESC'],3);
        return $this->render('admin/home.html.twig', [
            'lastArticles' => $lastArticles
        ]);
    }
}
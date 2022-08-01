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


class FrontArticleController extends AbstractController
{
    /**
     * @Route("/article-list/", name="article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->render('front/article-list.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article-show/{id}", name="article_show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        return $this->render('front/article-show.html.twig', [
            'article' => $article
        ]);
    }
}
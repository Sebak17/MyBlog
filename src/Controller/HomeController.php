<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        $articles = $this->getDoctrine()->getRepository(Article::class)->getLastArticles(6);

        $test = $this->getParameter('kernel.project_dir');

        echo $test;

        return $this->render('home/home.html.twig', [
            'menuActive' => '1',
            'articles'   => $articles,
        ]);
    }

    /**
     * @Route("/artykul/{id}/{title}", name="article")
     */
    public function article($id, $title)
    {
        // TODO CHECK ID

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if ($article == null) {
            return $this->redirectToRoute('home');
        }

        if ($title != $article->generateURL(true)) {
            return $this->redirectToRoute('home');
        }

        if ($article->getStatus() != 'VISIBLE') {
            return $this->redirectToRoute('home');
        }

        return $this->render('home/article.html.twig', [
            'article' => $article,
        ]);
    }

}

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

        return $this->render('home/home.html.twig', [
            'menuActive' => '1',
            'articles'   => $articles,
        ]);
    }

    /**
     * @Route("/tag/{tag}", name="articlesListByTag")
     */
    public function articlesListByTag($tag)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findByTag($tag, false, true);

        return $this->render('home/articlesListByTag.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/artykuly", name="articlesList")
     */
    public function articlesList()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->getLastArticles(10);

        return $this->render('home/articlesList.html.twig', [
            'menuActive' => '2',
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/omnie", name="aboutMe")
     */
    public function aboutMe()
    {
        return $this->render('home/aboutMe.html.twig', [
            'menuActive' => '3',
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

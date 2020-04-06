<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\ViewCounterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $links_context = file_get_contents($this->getParameter('storage') . 'links.json');
        $linksList = json_decode($links_context, true);


        $siteInfo = json_decode(file_get_contents($this->getParameter('storage') . 'site_info.json'), true);

        $articles = $this->getDoctrine()->getRepository(Article::class)->getLastArticles(6);

        return $this->render('home/home.html.twig', [
            'menuActive' => '1',
            'siteInfo' => $siteInfo,
            'articles'   => $articles,
            'linksList' => $linksList,
        ]);
    }

    /**
     * @Route("/tag/{tag}", name="articlesListByTag")
     */
    public function articlesListByTag($tag)
    {
        $links_context = file_get_contents($this->getParameter('storage') . 'links.json');
        $linksList = json_decode($links_context, true);

        $articles = $this->getDoctrine()->getRepository(Article::class)->findByTag($tag, false, true);

        return $this->render('home/articlesListByTag.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'linksList' => $linksList,
        ]);
    }

    /**
     * @Route("/artykuly", name="articlesList")
     */
    public function articlesList()
    {
        $links_context = file_get_contents($this->getParameter('storage') . 'links.json');
        $linksList = json_decode($links_context, true);

        $articles = $this->getDoctrine()->getRepository(Article::class)->getLastArticles(10);

        return $this->render('home/articlesList.html.twig', [
            'menuActive' => '2',
            'articles' => $articles,
            'linksList' => $linksList,
        ]);
    }


    /**
     * @Route("/omnie", name="aboutMe")
     */
    public function aboutMe()
    {
        $links_context = file_get_contents($this->getParameter('storage') . 'links.json');
        $linksList = json_decode($links_context, true);

        $aboutMeText = file_get_contents($this->getParameter('storage') . 'about_me.data');

        return $this->render('home/aboutMe.html.twig', [
            'menuActive' => '3',
            'aboutMeText' => $aboutMeText,
            'linksList' => $linksList,
        ]);
    }


    /**
     * @Route("/artykul/{id}/{title}", name="article", requirements={"id"="[0-9]"})
     */
    public function article($id, $title, ViewCounterService $viewCounterService)
    {

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

        $viewCounterService->addViewToArticle($article);

        $links_context = file_get_contents($this->getParameter('storage') . 'links.json');
        $linksList = json_decode($links_context, true);

        return $this->render('home/article.html.twig', [
            'article' => $article,
            'linksList' => $linksList,
        ]);
    }

}

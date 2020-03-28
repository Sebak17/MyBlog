<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* @Route("/panel", name="panel_")
* @IsGranted("ROLE_ADMIN")
*/
class PanelController extends AbstractController
{


    /**
     * @Route("/", name="main")
     */
    public function main()
    {
        $summary = array();

        $summary['articlesAll'] = count($this->getDoctrine()->getRepository(Article::class)->findAll());
        $summary['articlesMonth'] = count($this->getDoctrine()->getRepository(Article::class)->findByMonth());

        return $this->render('panel/main.html.twig', [
            'summary' => $summary,
        ]);
    }

    /**
     * @Route("/artykuly", name="articles")
     */
    public function articles()
    {
        return $this->render('panel/articles.html.twig', []);
    }

    /**
     * @Route("/artykuly/dodaj", name="article_add")
     */
    public function article_add()
    {
        return $this->render('panel/article/add.html.twig', []);
    }

    /**
     * @Route("/artykuly/edytuj/{id}", name="article_edit")
     */
    public function article_edit($id)
    {
        // TODO CHECK ID
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if($article == null) {
            return $this->render('panel/article/edit.html.twig', []);
        }

        return $this->render('panel/article/edit.html.twig', [
            'article' => $article,
        ]);
    }
    

    /**
     * @Route("/statystyki", name="statistics")
     */
    public function statistics()
    {
        return $this->render('panel/statistics.html.twig', []);
    }


    /**
     * @Route("/ustawienia", name="settings")
     */
    public function settings()
    {
        return $this->render('panel/settings.html.twig', []);
    }
}

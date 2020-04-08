<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ViewCounter;
use App\Service\ViewCounterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;

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

        $summary['viewsAll'] = count($this->getDoctrine()->getRepository(ViewCounter::class)->findAll());
        $summary['viewsMonth'] = count($this->getDoctrine()->getRepository(ViewCounter::class)->findByMonth());


        $viewsPerMonth = $this->getDoctrine()->getRepository(ViewCounter::class)->getViewsPerMonth();

        return $this->render('panel/main.html.twig', [
            'summary' => $summary,
            'viewsPerMonth' => $viewsPerMonth,
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
            return $this->redirectToRoute('panel_articles');
        }

        return $this->render('panel/article/edit.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/artykuly/statystyki/{id}", name="article_stats")
     */
    public function article_stats($id, ViewCounterService $viewCounterService)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if($article == null) {
            return $this->redirectToRoute('panel_articles');
        }

        $viewsPerMonth = $viewCounterService->getViewsPerMonthByArticle($article);

        $article->setStatusName($this->getParameter('article.status')[$article->getStatus()]);

        return $this->render('panel/article/stats.html.twig', [
            'article' => $article,
            'viewsPerMonth' => $viewsPerMonth,
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

    /**
     * @Route("/ustawienia/informacje", name="settings_info")
     */
    public function settings_info()
    {
        $siteInfo = json_decode(file_get_contents($this->getParameter('storage') . 'site_info.json'), true);

        return $this->render('panel/settings/info.html.twig', [
            'siteInfo' => $siteInfo,
        ]);
    }

    /**
     * @Route("/ustawienia/omnie", name="settings_aboutme")
     */
    public function settings_aboutme()
    {
        $aboutMeText = file_get_contents($this->getParameter('storage') . 'about_me.data');

        return $this->render('panel/settings/aboutme.html.twig', [
            'aboutMeText' => $aboutMeText,
        ]);
    }

    /**
     * @Route("/ustawienia/linki", name="settings_links")
     */
    public function settings_links()
    {
        $links_context = file_get_contents($this->getParameter('storage') . 'links.json');
        $linksList = json_decode($links_context, true);

        return $this->render('panel/settings/links.html.twig', [
            'linksList' => $linksList,
        ]);
    }
}

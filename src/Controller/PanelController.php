<?php

namespace App\Controller;

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
        return $this->render('panel/main.html.twig', []);
    }

    /**
     * @Route("/artykuły", name="articles")
     */
    public function articles()
    {
        return $this->render('panel/articles.html.twig', []);
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

<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\ViewCounter;
use Doctrine\ORM\EntityManagerInterface;

class ViewCounterService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addViewToArticle(Article $article)
    {

        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return;
        }

        $ip = $_SERVER['REMOTE_ADDR'];

        $viewExist = $this->entityManager->getRepository(ViewCounter::class)->findByArticleAndIp($article->getId(), $ip);

        if ($viewExist == null) {

            $obj = new ViewCounter();
            $obj->setIp($ip);
            $obj->setArticle($article);
            $obj->setDate(new \DateTime());

            $this->entityManager->persist($obj);
            $this->entityManager->flush();
        }

    }

}

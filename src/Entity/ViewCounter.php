<?php

namespace App\Entity;

use App\Entity\Article;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="view_counter")
 * @ORM\Entity(repositoryClass="App\Repository\ViewCounterRepository")
 */
class ViewCounter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="ip", type="text", nullable=false)
     */
    private $ip;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Article", cascade={"persist"}, inversedBy="viewCounters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

    public function getId():  ? int
    {
        return $this->id;
    }

    public function getIp() :  ? string
    {
        return $this->ip;
    }

    public function setIp(string $ip) : self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getDate():  ? \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date) : self
    {
        $this->date = $date;

        return $this;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }
}

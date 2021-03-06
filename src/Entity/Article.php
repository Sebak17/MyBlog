<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, columnDefinition="ENUM('VISIBLE', 'INVISIBLE')")
     */
    private $status;

     /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleImage;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $tag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description_short;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="ViewCounter", mappedBy="article")
     */
    protected $viewCounters;

    public function getId():  ? int
    {
        return $this->id;
    }

    public function getStatus() :  ? string
    {
        return $this->status;
    }

    public function setStatus(string $status) : self
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthor() :  ? string
    {
        return $this->author;
    }

    public function setAuthor( ? string $author) : self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle():  ? string
    {
        return $this->title;
    }

    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleImage():  ? string
    {
        return $this->titleImage;
    }

    public function setTitleImage(string $titleImage) : self
    {
        $this->titleImage = $titleImage;

        return $this;
    }

    public function getTag():  ? string
    {
        return $this->tag;
    }

    public function setTag( ? string $tag) : self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getDescriptionShort() :  ? string
    {
        return $this->description_short;
    }

    public function setDescriptionShort( ? string $description_short) : self
    {
        $this->description_short = $description_short;

        return $this;
    }

    public function getText() :  ? string
    {
        return $this->text;
    }

    public function setText( ? string $text) : self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt() :  ? \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at) : self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt():  ? \DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at) : self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    private $status_name;

    public function getStatusName():  ? string
    {
        return $this->status_name;
    }

    public function setStatusName( ? string $status_name) : self
    {
        $this->status_name = $status_name;

        return $this;
    }

    public function generateURL($onlyTitle = false)
    {
        $t = preg_replace('/[^A-Za-z0-9 ]/', '', $this->getTitle());

        $title = implode("-", array_slice(explode(' ', $t), 0, 5));

        if ($onlyTitle) {
            return $title;
        }

        return "/artykul/" . $this->getId() . "/" . $title;
    }

    public function getTitleImageURL() :  ? string
    {
        return "/uploads/images/" . $this->titleImage;
    }

    public function getViewCounters()
    {
        return $this->viewCounters;
    }

}

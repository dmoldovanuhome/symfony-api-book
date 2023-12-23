<?php

namespace App\Dto;

use App\Entity\Book;

class ReadBookDto
{
    private $id;
    private $title;
    private $author;
    private $description;
    private $price;

    public static function to(Book $book) : ReadBookDto
    {
        $dto = new ReadBookDto();
        $dto->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setAuthor($book->getAuthor())
            ->setDescription($book->getDescription())
            ->setPrice($book->getPrice());

        return $dto;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): ReadBookDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): ReadBookDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): ReadBookDto
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): ReadBookDto
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }
}
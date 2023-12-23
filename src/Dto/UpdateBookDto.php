<?php

namespace App\Dto;

use App\Contracts\WriteBookDtoInterface;
use App\Entity\Book;

class UpdateBookDto implements WriteBookDtoInterface
{
    private $title;
    private $author;
    private $description;
    private $price;

    public static function to(Book $book) : UpdateBookDto
    {
        $dto = new UpdateBookDto();
        $dto->setTitle($book->getTitle())
            ->setAuthor($book->getAuthor())
            ->setDescription($book->getDescription())
            ->setPrice($book->getPrice());

        return $dto;
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
    public function setTitle($title): UpdateBookDto
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
    public function setAuthor($author): UpdateBookDto
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
    public function setDescription($description): UpdateBookDto
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
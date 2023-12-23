<?php

namespace App\Factory;

use App\Contracts\WriteBookDtoInterface;
use App\Entity\Book;

class BookFactory
{
    public static function create(array $fields) : Book
    {
        $book = new Book();
        $book->setTitle($fields['title']);
        $book->setAuthor($fields['author']);
        $book->setDescription($fields['description']);
        $book->setPrice($fields['price']);

        return $book;
    }

    public static function fromDto(WriteBookDtoInterface $dto) : Book
    {
        $book = new Book();
        $book->setTitle($dto->getTitle());
        $book->setAuthor($dto->getAuthor());
        $book->setDescription($dto->getDescription());
        $book->setPrice($dto->getPrice());

        return $book;
    }
}
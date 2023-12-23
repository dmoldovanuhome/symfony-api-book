<?php

namespace App\Contracts;

interface WriteBookDtoInterface
{
    public function getTitle();
    public function getAuthor();
    public function getDescription();
    public function getPrice();
}
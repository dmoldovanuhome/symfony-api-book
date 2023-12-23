<?php

namespace App\Exception;

use Symfony\Component\Uid\Uuid;

class BookNotFoundException extends \RuntimeException
{
    public function __construct(Uuid $uuid)
    {
        parent::__construct("Book #" . $uuid . " was not found");
    }
}
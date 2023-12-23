<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    protected $books;

    public function __construct(BookRepository $bookRepository)
    {
        $this->books = $bookRepository;
    }

    /**
     * @Rest\Get("/books", name="books.all")
     * @Rest\QueryParam(name="filter", nullable=true, description="Filter criteria as an array", map=true)
     * @Rest\QueryParam(name="page", nullable=true, description="Page number", default="0", requirements="\d+")
     * @Rest\QueryParam(name="limit", nullable=true, description="Per page", default="20", requirements="\d+")
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $offset = $request->query->get('page') ?? 0;
        $limit = $request->query->get('limit') ?? 20;
        $filter = $request->query->get('filter') ?? [];
        $data = $this->books->findByFilter($filter, $offset, $limit);

        return $this->json($data);
    }
}

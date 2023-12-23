<?php

namespace App\Controller\Api;

use App\Dto\CreateBookDto;
use App\Exception\BookNotFoundException;
use App\Factory\BookFactory;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class BookController extends AbstractController
{
    /** @var BookRepository */
    protected $books;
    /** @var EntityManagerInterface  */
    private $objectManager;

    public function __construct(BookRepository $bookRepository, EntityManagerInterface $objectManager)
    {
        $this->books = $bookRepository;
        $this->objectManager = $objectManager;
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

    /**
     * @Rest\Get("/books/{id}", name="books.get")
     * @param Uuid $id
     * @return JsonResponse
     */
    public function show(Uuid $id) : JsonResponse
    {
        $book = $this->books->findOneBy(['id' => $id]);
        if (!$book) {
            throw new BookNotFoundException($id);
        }

        return $this->json($book);
    }

    /**
     * @Rest\Post("/books", name="books.create")
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $dto = CreateBookDto::hydrate($request);
        $book = BookFactory::fromDto($dto);

        $this->objectManager->persist($book);
        $this->objectManager->flush($book);

        return $this->json([
            'message' => 'Book created',
            'id' => $book->getId(),
        ], Response::HTTP_CREATED);
    }
}

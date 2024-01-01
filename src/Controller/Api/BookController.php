<?php

namespace App\Controller\Api;

use App\Dto\CreateBookDto;
use App\Dto\UpdateBookDto;
use App\Entity\Book;
use App\Exception\BookNotFoundException;
use App\Factory\BookFactory;
use App\Repository\BookRepository;
use App\Validator\CreateBookValidator;
use App\Validator\UpdateBookValidator;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;


/**
 * @Route("/")
 * @OA\Tag(name="books")
 */
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
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of books",
     *     @OA\JsonContent(
     *         ref="#/components/schemas/Book"
     *     ),
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
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
     * @OA\Response(
     *     response=200,
     *     description="Returns one book by UUID",
     * )
     * @OA\Response(
     *     response=404,
     *     description="Book not found"
     * )
     *
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
     * @Security(name="Basic Auth")
     * @OA\Response(
     *     response=201,
     *     description="return UUID of book."
     * )
     * @OA\RequestBody(
     *   description="Create a new book",
     *   required=true,
     *   @OA\JsonContent(ref="#/components/schemas/Book")
     * )
     * @OA\Response(
     *     response=400,
     *     description="Errors of validations, of internal server errors. Return error description"
     * )
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized"
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $dto = CreateBookDto::hydrate($request);

        $validator = new CreateBookValidator($dto);

        if(!$validator->isValid()) {
            return $this->json([
                'errors' => $validator->getErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $book = BookFactory::fromDto($dto);

        $this->objectManager->persist($book);
        $this->objectManager->flush($book);

        return $this->json([
            'message' => 'Book created',
            'id' => $book->getId(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/books/{id}", name="project_edit", methods={"PUT"})
     * @Security(name="Basic Auth")
     * @OA\RequestBody(
     *   description="Update the book",
     *   required=true,
     *   @OA\JsonContent(ref="#/components/schemas/Book")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Return JSON of updated book"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Errors of validations, of internal server errors. Return error description"
     * )
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized"
     * )
     * @param Request $request
     * @param Uuid $id
     * @return JsonResponse
     */
    public function update(Request $request, Uuid $id) : JsonResponse
    {
        $book = $this->books->findOneBy(['id' => $id]);

        if (!$book) {
            throw new BookNotFoundException($id);
        }

        $dto = UpdateBookDto::hydrate($request);

        $validator = new UpdateBookValidator($dto);

        if(!$validator->isValid()) {
            return $this->json([
                'errors' => $validator->getErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $book = $this->books->update($book, $dto);
        $this->objectManager->flush();

        return $this->json($book);
    }

    /**
     * @Rest\Delete("/books/{id}", name="books.delete")
     * @Security(name="Basic Auth")
     * @OA\Response(
     *     response=204,
     *     description="Delete the book by UUID"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Book not found"
     * )
     * @OA\Response(
     *     response=401,
     *     description="Unauthorized"
     * )
     * @param Uuid $id
     * @return JsonResponse
     */
    public function delete(Uuid $id) : JsonResponse
    {
        $book = $this->books->findOneBy(['id' => $id]);

        if (!$book) {
            throw new BookNotFoundException($id);
        }

        $this->books->remove($book);
        $this->objectManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}

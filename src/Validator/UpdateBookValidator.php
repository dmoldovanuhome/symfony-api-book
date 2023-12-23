<?php

namespace App\Validator;

use App\Contracts\WriteBookDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

class UpdateBookValidator
{
    /** @var WriteBookDtoInterface */
    protected $dto;
    /** @var array */
    protected $errors;

    protected $validator;

    public function __construct(WriteBookDtoInterface $dto)
    {
        $this->dto = $dto;
        $this->errors = [];
        $this->validator = Validation::createValidator();
    }

    public function isValid() : bool
    {
        $this->validateTitle();
        $this->validateAuthor();
        $this->validateDescription();
        $this->validatePrice();

        return count($this->errors) === 0;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    protected function validateTitle()
    {
        $titleConstraint = [
            new Assert\NotBlank(['message' => 'Title cannot be empty.']),
            new Assert\Length([
                'min' => 3,
                'max' => 255,
                'minMessage' => 'Title must be at least {{ limit }} characters long.',
                'maxMessage' => 'Title cannot be longer than {{ limit }} characters.',
            ]),
        ];
        $violationList = $this->validator->validate($this->dto->getTitle(), $titleConstraint);

        if (count($violationList) > 0) {
            $this->handleValidationErrors('title', $violationList);
        }
    }

    protected function validateAuthor()
    {
        $authorConstraint = [
            new Assert\NotBlank(['message' => 'Author cannot be empty.']),
            new Assert\Length([
                'min' => 3,
                'max' => 255,
                'minMessage' => 'Author must be at least {{ limit }} characters long.',
                'maxMessage' => 'Author cannot be longer than {{ limit }} characters.',
            ]),
        ];

        $violationList = $this->validator->validate($this->dto->getAuthor(), $authorConstraint);

        if (count($violationList) > 0) {
            $this->handleValidationErrors('author', $violationList);
        }
    }

    protected function validateDescription()
    {
        //it can be optional
        if (empty($this->dto->getDescription())) {
            return;
        }

        //but if is filled
        $descriptionConstraint = [
            new Assert\Length([
                'min' => 10,
                'minMessage' => 'Description must be at least {{ limit }} characters long.',
            ]),
        ];

        $violationList = $this->validator->validate($this->dto->getDescription(), $descriptionConstraint);

        if (count($violationList) > 0) {
            $this->handleValidationErrors('description', $violationList);
        }
    }

    protected function validatePrice()
    {
        $priceConstraint = [
            new Assert\NotBlank(['message' => 'Price cannot be blank.']),
            new Assert\Type(['type' => 'numeric', 'message' => 'Price must be a number.']),
            new Assert\Positive(['message' => 'Price must be greater than 0.']),

        ];

        $violationList = $this->validator->validate($this->dto->getPrice(), $priceConstraint);
        if (count($violationList) > 0) {
            $this->handleValidationErrors('price', $violationList);
        }
    }


    /**
     * @param string $field
     * @param $violationList
     */
    private function handleValidationErrors(string $field, $violationList)
    {
        foreach ($violationList as $violation) {
            $this->errors[$field][] =  $violation->getMessage();
        }
    }
}
<?php

namespace App\Helper\Traits;

use Symfony\Component\HttpFoundation\Request;

trait HydrateStaticTrait
{
    public static function hydrate(Request $request): self
    {
        $dto = new self();
        foreach ($request->request->all() as $key => $value) {
            if (property_exists($dto, $key)) {
                $method = self::transformToSetter($key);
                $dto->$method($value);
            }
        }

        return $dto;
    }

    private static function transformToSetter(string $fieldName): string
    {
        $camelCaseFieldName = str_replace('_', '', ucwords($fieldName, '_'));
        return 'set' . $camelCaseFieldName;
    }
}
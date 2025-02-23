<?php

declare(strict_types=1);

namespace App\Service;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Rules\Optional;

class ValidationService
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     * @return array{error: array<string, string>}|null
     */
    public function validate(array $data, array $rules): ?array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            try {
                $fieldValue = $data[$field] ?? null;
                $data[$field] = $fieldValue;
                v::key($field, $rule)->assert($data);
            } catch (NestedValidationException $e) {
                $errors = array_merge($errors, $e->getMessages());
            }
        }

        return empty($errors) ? null : ['error' => $errors];
    }
}

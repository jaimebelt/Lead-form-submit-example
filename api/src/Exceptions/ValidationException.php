<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    /**
     * @var array<string, mixed>
     */
    private array $errors;

    /**
     * @param string $message
     * @param array<string, mixed> $errors
     * @param Exception|null $previous
     */
    public function __construct(string $message, array $errors = [], Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

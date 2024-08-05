<?php

namespace Exceptions;

use Exception;

class ValidationException extends Exception {
    public string $field;

    public function __construct(string $message, string $field) {
        parent::__construct($message);
        $this->field = $field;
    }
}

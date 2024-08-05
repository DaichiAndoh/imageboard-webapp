<?php

namespace Helpers;

use Exceptions\ValidationException;

class ValidationHelper {
    public static function integer($value, float $min = -INF, float $max = INF): int {
        $value = filter_var($value, FILTER_VALIDATE_INT);
        if ($value === false) {
            throw new ValidationException("The provided value is not a valid integer.");
        }

        if ($value < $min || $value > $max) {
            throw new ValidationException("The provided value is out of the specified range.");
        }

        return $value;
    }
}

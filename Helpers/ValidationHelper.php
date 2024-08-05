<?php

namespace Helpers;

require_once(sprintf("%s/../Constants/FileConstants.php", __DIR__));
require_once(sprintf("%s/../Constants/StringConstants.php", __DIR__));

use Exceptions\ValidationException;

class ValidationHelper {
    public static function integer($value, string $field = "", float $min = -INF, float $max = INF): int {
        $value = filter_var($value, FILTER_VALIDATE_INT);
        if ($value === false) {
            throw new ValidationException("The provided value is not a valid integer.", $field);
        }

        if ($value < $min || $value > $max) {
            throw new ValidationException("The provided value is out of the specified range.", $field);
        }

        return $value;
    }

    public static function str($value, string $field = "", int $minLength = DEFAULT_STRING_MIN_LENGTH, int $maxLength = DEFAULT_STRING_MAX_LENGTH): string {
        if (!is_string($value)) {
            throw new ValidationException("The provided value is not a valid string.", $field);
        }

        $length = strlen($value);
        if ($length < $minLength || $length > $maxLength) {
            throw new ValidationException("The provided value's length is out of the specified range.", $field);
        }

        return $value;
    }

    public static function imageType(string $type, string $field = ""): string {
        $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
        if (!in_array($type, $allowedTypes)) {
            throw new ValidationException("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.", $field);
        };
        return $type;
    }

    public static function fileSize(int $size, string $field = "", int $min = DEFAULT_FILE_MIN_SIZE, int $max = DEFAULT_FILE_MAX_SIZE): int {
        if (!($size >= $min && $size <= $max)) {
            throw new ValidationException("The file size is too large. The maximum allowed size is 3MB.", $field);
        }
        return $size;
    }
}

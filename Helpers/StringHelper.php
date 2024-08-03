<?php

namespace Helpers;

class StringHelper {
    public static function generateRandomStr(): string {
        return uniqid(bin2hex(random_bytes(1)));
    }
}

<?php

namespace Helpers;

class ImageHelper {
    public static function imageTypeToExtension(string $type): string {
        return str_replace('image/', '', $type);
    }

    public static function saveImage(string $uploadedFilePath, string $extension): string {
        $hash = md5(StringHelper::generateRandomStr() . date('Y-m-d H:i:s'));
        $imageHash = sprintf("%s.%s", $hash, $extension);

        self::saveOriginalImage($uploadedFilePath, $imageHash);
        self::saveThumbnailImage($imageHash);

        return $imageHash;
    }

    private static function saveOriginalImage(string $uploadedFilePath, string $imageHash): void {
        $imagePath = sprintf("%s/../Public/images/originals/%s", __DIR__, $imageHash);
        move_uploaded_file($uploadedFilePath, $imagePath);
    }

    private static function saveThumbnailImage(string $imageHash): void {
        $originalImagePath = sprintf("%s/../Public/images/originals/%s", __DIR__, $imageHash);
        $thumbnailImagePath = sprintf("%s/../Public/images/thumbnails/%s", __DIR__, $imageHash);

        $output=null;
        $retval=null;
        $command = sprintf("convert %s -resize 300x300! %s", $originalImagePath, $thumbnailImagePath);
        exec($command, $output, $retval);
    }
}

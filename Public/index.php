<?php
require_once '../vendor/autoload.php';

use Exceptions\ValidationException;
use Exceptions\FileUploadException;
use Exceptions\NotFoundException;

$DEBUG = true;

// ルートの読み込み
$routes = include('../Routing/routes.php');

// リクエストURIを解析してパスだけを取得
$originalPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = $originalPath;

// ルートにパスが存在するかチェック
if (isset($routes[$path])) {
    // コールバックを呼び出してrendererを作成
    try {
        $renderer = $routes[$path]($originalPath);

        // ヘッダーを設定
        foreach ($renderer->getFields() as $name => $value) {
            // ヘッダーの検証
            $sanitized_value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                http_response_code(500);
                if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
                exit;
            }
        }

        print($renderer->getContent());
    }
    catch (Exception $e) {
        http_response_code(500);
        print("Internal error, please contact the admin.<br>");
        if ($DEBUG) print($e->getMessage());
    }
} else {
    http_response_code(404);
    echo "{$originalPath} - 404 Not Found: The requested route was not found on this server.";
}

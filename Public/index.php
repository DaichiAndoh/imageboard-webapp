<?php
require_once '../vendor/autoload.php';

use Exceptions\FileUploadException;
use Exceptions\NotFoundException;
use Exceptions\ValidationException;
use Helpers\Settings;

$DEBUG = Settings::env('DEBUG');

if (preg_match('/\.(?:png|jpg|jpeg|gif|js|css|html)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

// ルートの読み込み
$routes = include('../Routing/routes.php');

// リクエストURIを解析してパスだけを取得
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// ルートにパスが存在するかチェック
$controller = null;
foreach ($routes as $pathPattern => $func) {
    if (preg_match($pathPattern, $path)) {
        $controller = $func;
    }
}

if ($controller !== null) {
    try {
        // コールバックを呼び出してrendererを作成
        $renderer = $controller($path);

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

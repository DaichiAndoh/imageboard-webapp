<?php

namespace Response\Render;

use Response\HTTPRenderer;

class HTMLRenderer implements HTTPRenderer {
    private string $viewFile;
    private array $data;

    public function __construct(string $viewFile, array $data = []) {
        $this->viewFile = $viewFile;
        $this->data = $data;
    }

    public function getFields(): array {
        return [
            'Content-Type' => 'text/html; charset=UTF-8',
        ];
    }

    public function getContent(): string {
        $viewFilePath = $this->getViewFilePath($this->viewFile);

        if (!file_exists($viewFilePath)) {
            throw new Exception("View file {$viewFielPath} does not exist.");
        }

        ob_start();
        extract($this->data);
        require $viewFilePath;
        return $this->getHeader() . ob_get_clean() . $this->getFooter();
    }

    private function getViewFilePath(string $viewFile): string {
        return sprintf("%s/%s/Views/%s.php", __DIR__, '../..', $viewFile);
    }

    private function getHeader(): string {
        ob_start();
        require $this->getViewFilePath('layout/header');
        return ob_get_clean();
    }

    private function getFooter(): string {
        ob_start();
        require $this->getViewFilePath('layout/footer');
        return ob_get_clean();
    }
}

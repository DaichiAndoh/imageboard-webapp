<?php

use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Database\DataAccess\Implementations\PostDAOImpl;

return [
    '/' => function(string $path): HTTPRenderer {
        return new HTMLRenderer('timeline', []);
    },
    '/api/get_all_threads' => function(string $path): HTTPRenderer{
        try {
            $offset = ValidationHelper::integer($_POST['offset'] ?? 0, 0);
            $limit = ValidationHelper::integer($_POST['limit'] ?? 10, 0, 100);
    
            $postDao = new PostDAOImpl();
            $threads = $postDao->getAllThreads($offset, $limit);
            $totalCount = $postDao->getTotalCount();
    
            if ($threads === null) throw new Exception('No threads are available!');
    
            return new JSONRenderer(['success' => 1, 'threads' => $threads, 'totalCount' => $totalCount]);
        } catch (Exception $error) {
            return new JSONRenderer(['success' => 0, 'error' => $error->getMessage()]);
        }
    },
];

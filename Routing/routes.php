<?php

use Database\DataAccess\Implementations\PostDAOImpl;
use Exceptions\ValidationException;
use Helpers\ImageHelper;
use Helpers\ValidationHelper;
use Models\Post;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;

return [
    '/^\/$/' => function(string $path): HTTPRenderer {
        return new HTMLRenderer('timeline', []);
    },
    '/^\/api\/get_all_threads$/' => function(string $path): HTTPRenderer {
        try {
            $offset = ValidationHelper::integer($_POST['offset'] ?? 0, '', 0);
            $limit = ValidationHelper::integer($_POST['limit'] ?? 10, '', 0, 100);

            $postDao = new PostDAOImpl();
            $threads = $postDao->getAllThreads($offset, $limit);
            $totalCount = $postDao->getTotalCountOfThread();

            if ($threads === null) throw new Exception('No threads are available!');

            return new JSONRenderer(['success' => 1, 'threads' => $threads, 'totalCount' => $totalCount]);
        } catch (Exception $error) {
            return new JSONRenderer(['success' => 0, 'error' => $error->getMessage()]);
        }
    },
    '/^\/api\/create_thread$/' => function(string $path): HTTPRenderer {
        try {
            // validate input values
            $subject = ValidationHelper::str($_POST['subject'], 'subject',  1, Post::SUBJECT_MAX_LENGTH);
            $content = ValidationHelper::str($_POST['content'], 'content', 1, Post::CONTENT_MAX_LENGTH);
            $useImage = $_FILES['file']['name'];
            if ($useImage) {
                $fileType = ValidationHelper::imageType($_FILES['file']['type'], "file");
                $fileSize = ValidationHelper::fileSize($_FILES['file']['size'], "file");
            }

            // save image file to storage
            $imageHash = null;
            if ($useImage) {
                $imageHash = ImageHelper::saveImage(
                    $_FILES['file']['tmp_name'],
                    ImageHelper::imageTypeToExtension($_FILES['file']['type']),
                );
            }

            // insert post data to db
            $postDao = new PostDAOImpl();
            $result = $postDao->create(new Post(
                subject: $subject,
                content: $content,
                imageHash: $imageHash,
            ));
            if (!$result) throw new Exception();

            return new JSONRenderer(['success' => 1]);
        } catch (ValidationException $error) {
            return new JSONRenderer(['success' => 0, 'field' => $error->field, 'message' => $error->getMessage()]);
        } catch (Exception $error) {
            return new JSONRenderer(['success' => 0, 'error' => $error->getMessage()]);
        }
    },
    '/^\/thread\/\d+$/' => function(string $path): HTTPRenderer {
        return new HTMLRenderer('thread', []);
    },
    '/^\/api\/get_thread\/\d+$/' => function(string $path): HTTPRenderer {
        try {
            // get thread(post) info
            $pathArray = preg_split('/\//', $path);
            $threadId = $pathArray[3];

            // validate id
            $threadId = ValidationHelper::integer($threadId, '', 0);

            $postDao = new PostDAOImpl();
            $thread = $postDao->getById($threadId);

            return new JSONRenderer(['success' => 1, 'thread' => $thread]);
        } catch (Exception $error) {
            return new JSONRenderer(['success' => 0, 'error' => $error->getMessage()]);
        }
    },
    '/^\/api\/get_replies\/\d+$/' => function(string $path): HTTPRenderer {
        try {
            // get thread(post) info
            $pathArray = preg_split('/\//', $path);
            $threadId = $pathArray[3];

            // validate input values
            $threadId = ValidationHelper::integer($threadId, '', 0);
            $offset = ValidationHelper::integer($_POST['offset'] ?? 0, '', 0);
            $limit = ValidationHelper::integer($_POST['limit'] ?? 10, '', 0, 100);

            $postDao = new PostDAOImpl();
            $replies = $postDao->getReplies($threadId, $offset, $limit);
            $totalCount = $postDao->getTotalCountOfReply($threadId);

            return new JSONRenderer(['success' => 1, 'replies' => $replies, 'totalCount' => $totalCount]);
        } catch (Exception $error) {
            return new JSONRenderer(['success' => 0, 'error' => $error->getMessage()]);
        }
    },
    '/^\/api\/create_reply$/' => function(string $path): HTTPRenderer {
        try {
            // validate input values
            $threadId = ValidationHelper::integer((int)$_POST['threadId'], '', 1);
            $content = ValidationHelper::str($_POST['content'], 'content', 1, Post::CONTENT_MAX_LENGTH);
            $useImage = $_FILES['file']['name'];
            if ($useImage) {
                $fileType = ValidationHelper::imageType($_FILES['file']['type'], "file");
                $fileSize = ValidationHelper::fileSize($_FILES['file']['size'], "file");
            }

            // save image file to storage
            $imageHash = null;
            if ($useImage) {
                $imageHash = ImageHelper::saveImage(
                    $_FILES['file']['tmp_name'],
                    ImageHelper::imageTypeToExtension($_FILES['file']['type']),
                );
            }

            // insert post data to db
            $postDao = new PostDAOImpl();
            $result = $postDao->create(new Post(
                replyToId: $threadId,
                content: $content,
                imageHash: $imageHash,
            ));
            if (!$result) throw new Exception();

            return new JSONRenderer(['success' => 1]);
        } catch (ValidationException $error) {
            return new JSONRenderer(['success' => 0, 'field' => $error->field, 'message' => $error->getMessage()]);
        } catch (Exception $error) {
            return new JSONRenderer(['success' => 0, 'error' => $error->getMessage()]);
        }
    },
];

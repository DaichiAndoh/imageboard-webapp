<?php

namespace Database\DataAccess\Implementations;

use Database\DataAccess\Interfaces\PostDAO;
use Database\DatabaseManager;
use Models\Post;

class PostDAOImpl implements PostDAO {
    public function create(Post $postData): bool {
        if($postData->getPostId() !== null) throw new \Exception('Cannot create a post with an existing ID. id: ' . $postData->getPostId());
        return $this->createOrUpdate($postData);
    }

    public function getById(int $id): ?Post {
        $mysqli = DatabaseManager::getMysqliConnection();
        $post = $mysqli->prepareAndFetchAll("SELECT * FROM posts WHERE post_id = ?", 'i' , [$id])[0] ?? null;

        return $post === null ? null : $this->resultToPost($post);
    }

    public function update(Post $postData): bool {
        if($postData->getPostId() === null) throw new \Exception('Post specified has no ID.');

        $current = $this->getById($postData->getPostId());
        if ($current === null) throw new \Exception(sprintf("Post %s does not exist.", $postData->getPostId()));

        return $this->createOrUpdate($postData);
    }

    public function delete(int $id): bool {
        $mysqli = DatabaseManager::getMysqliConnection();
        return $mysqli->prepareAndExecute("DELETE FROM posts WHERE post_id = ?", 'i', [$id]);
    }

    public function createOrUpdate(Post $postData): bool {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query =
        <<<SQL
            INSERT INTO posts (post_id, reply_to_id, subject, content, image_hash)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            subject = VALUES(subject),
            content = VALUES(content),
        SQL;

        $currentDateTime = date('Y-m-d H:i:s');
        $result = $mysqli->prepareAndExecute(
            $query,
            'iisss',
            [
                $postData->getPostId(),
                $postData->getReplyToId(),
                $postData->getSubject(),
                $postData->getContent(),
                $postData->getImageHash(),
            ],
        );

        if(!$result) return false;

        if ($postData->getPostId() === null){
            $postData->setPostId($mysqli->insert_id);
            $dateTime->setCreatedAt($dateTime->getCreatedAt() ?? date('Y-m-d H:i:s'));
            $dateTime->setUpdatedAt($dateTime->getUpdatedAt() ?? date('Y-m-d H:i:s'));
        } else {
            $dateTime->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        return true;
    }

    public function getAllThreads(int $offset, int $limit): array {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

        return $results === null ? [] : $this->resultsToPosts($results);
    }

    public function getReplies(Post $postData, int $offset, int $limit): array {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts WHERE reply_to_id = ? LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'iii', [$postData->getPostId(), $offset, $limit]);
        return $results === null ? [] : $this->resultsToPosts($results);
    }

    private function resultToPost(array $data): Post {
        return new Post (
            postId: $data['post_id'],
            replyToId: $data['reply_to_id'],
            subject: $data['subject'],
            content: $data['content'],
            imageHash: $data['image_hash'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
        );
    }

    private function resultsToPosts(array $results): array{
        $posts = [];

        foreach($results as $result){
            $posts[] = $this->resultToPost($result);
        }

        return $posts;
    }
}

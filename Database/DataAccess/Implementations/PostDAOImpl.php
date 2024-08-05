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
            content = VALUES(content)
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
            $postData->setCreatedAt($postData->getCreatedAt() ?? date('Y-m-d H:i:s'));
            $postData->setUpdatedAt($postData->getUpdatedAt() ?? date('Y-m-d H:i:s'));
        } else {
            $postData->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        return true;
    }

    public function getTotalCount(): int {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT COUNT(*) AS count FROM posts";

        $result = $mysqli->query($query);

        $row = $result->fetch_assoc();

        return $row['count'];
    }

    public function getAllThreads(int $offset, int $limit): array {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts ORDER BY post_id DESC LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'ii', [$offset, $limit]);

        return $results === null ? [] : $this->resultsToApiData($results);
    }

    public function getReplies(Post $postData, int $offset, int $limit): array {
        $mysqli = DatabaseManager::getMysqliConnection();

        $query = "SELECT * FROM posts WHERE reply_to_id = ? LIMIT ?, ?";

        $results = $mysqli->prepareAndFetchAll($query, 'iii', [$postData->getPostId(), $offset, $limit]);
        return $results === null ? [] : $this->resultsToPosts($results);
    }

    private function resultToPost(array $data): string {
        return new Post (
            content: $data['content'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
            postId: $data['post_id'],
            replyToId: $data['reply_to_id'],
            subject: $data['subject'],
            imageHash: $data['image_hash'],
        );
    }

    private function resultsToPosts(array $results): array{
        $posts = [];

        foreach($results as $result){
            $posts[] = $this->resultToPost($result);
        }

        return $posts;
    }

    private function resultToApiData(array $data): array {
        return [
            'postId' => $data['post_id'],
            'replyToId' => $data['reply_to_id'],
            'subject' => $data['subject'],
            'content' => $data['content'],
            'imageHash' => $data['image_hash'],
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at'],
        ];
    }

    private function resultsToApiData(array $results): array{
        $posts = [];

        foreach($results as $result){
            $posts[] = $this->resultToApiData($result);
        }

        return $posts;
    }
}

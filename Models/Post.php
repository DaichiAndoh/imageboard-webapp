<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Post implements Model {
    use GenericModel;

    public function __construct(
        private string $content,
        private string $createdAt,
        private string $updatedAt,
        private ?int $postId = null,
        private ?int $replyToId = null,
        private ?string $subject = null,
        private ?string $imageHash = null,
    ) {}

    public function getPostId(): ?int {
        return $this->postId;
    }

    public function setPostId(int $postId): void {
        $this->postId = $postId;
    }

    public function getReplyToId(): ?string {
        return $this->replyToId;
    }

    public function setReplyToId(int $replyToId): void {
        $this->replyToId = $replyToId;
    }

    public function getSubject(): ?string {
        return $this->subject;
    }

    public function setSubject(string $subject): void {
        $this->subject = $subject;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getImageHash(): ?string {
        return $this->imageHash;
    }

    public function setImageHash(string $imageHash): void {
        $this->imageHash = $imageHash;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }
}

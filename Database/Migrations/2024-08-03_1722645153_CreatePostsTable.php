<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreatePostsTable implements SchemaMigration {
    public function up(): array {
        // マイグレーションロジックをここに追加
        return [
            "CREATE TABLE posts (
                post_id INT PRIMARY KEY AUTO_INCREMENT,
                reply_to_id INT NULL,
                subject VARCHAR(30) NULL,
                content VARCHAR(150) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )",
        ];
    }

    public function down(): array {
        // ロールバックロジックを追加
        return [
            "DROP TABLE posts",
        ];
    }
}

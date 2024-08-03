<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class UpdatePostsTable implements SchemaMigration {
    public function up(): array {
        // マイグレーションロジックをここに追加
        return [
            "ALTER TABLE posts MODIFY COLUMN content VARCHAR(200) NOT NULL",
            "ALTER TABLE posts ADD COLUMN image_hash VARCHAR(40) NULL AFTER content"
        ];
    }

    public function down(): array {
        // ロールバックロジックを追加
        return [
            "ALTER TABLE posts MODIFY COLUMN content VARCHAR(150) NULL",
            "ALTER TABLE posts DROP COLUMN image_hash"
        ];
    }
}

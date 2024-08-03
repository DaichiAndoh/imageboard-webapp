<?php

namespace Database\Seeds;

use Faker\Factory;
use Database\AbstractSeeder;

class PostsSeeder extends AbstractSeeder {
    // TODO: tableName文字列の割り当て
    protected ?string $tableName = 'posts';

    // TODO: tableColumns配列の割り当て
    protected array $tableColumns = [
        [
            'data_type' => 'int',
            'column_name' => 'post_id',
            'nullable' => false
        ],
        [
            'data_type' => 'int',
            'column_name' => 'reply_to_id',
            'nullable' => true
        ],
        [
            'data_type' => 'string',
            'column_name' => 'subject',
            'nullable' => false
        ],
        [
            'data_type' => 'string',
            'column_name' => 'content',
            'nullable' => false
        ],
        [
            'data_type' => 'string',
            'column_name' => 'image_hash',
            'nullable' => false
        ],
    ];

    public function createRowData(array $imageHashList): array {
        // TODO: createRowData()メソッドの実装
        $faker = Factory::create();

        $rows = [];
        for ($i = 0; $i < count($imageHashList); $i++) {
            // idが奇数であればスレッドポスト, 偶数であれば-1した奇数のスレッドに紐付くリプライポスト
            $postId = $i + 1;
            $replyToId = $postId % 2 === 0 ? $postId - 1 : null;
            $rows[] = [$i + 1, $replyToId, $faker->text(30), $faker->text(200), $imageHashList[$i]];
        }

        return $rows;
    }
}

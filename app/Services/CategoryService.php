<?php

namespace App\Services;

use Google\Cloud\Language\LanguageClient;

class CategoryService
{
    public function categorizePost($text)
    {
        $language = new LanguageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
        ]);

        $response = $language->analyzeEntities($text);
        $entities = $response->entities();

        // エンティティに基づいてジャンルを判断
        foreach ($entities as $entity) {
            if (in_array($entity['type'], ['MUSIC', 'TECHNOLOGY', 'POLITICS'])) {
                return $entity['type'];
            }
        }
        return '未定義';
    }
}

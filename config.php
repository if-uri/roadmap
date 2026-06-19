<?php
declare(strict_types=1);
// roadmap.ifuri.com — GitHub-synced roadmap config (Plesk / PHP 8.3).
return [
    'owner'      => getenv('IFURI_ROADMAP_OWNER') ?: 'if-uri',
    'repo'       => getenv('IFURI_ROADMAP_REPO') ?: 'roadmap',
    'branch'     => getenv('IFURI_ROADMAP_BRANCH') ?: 'main',
    'ttl'        => (int) (getenv('IFURI_ROADMAP_CACHE_TTL') ?: 86400), // sync once/day
    'cache_dir'  => __DIR__ . '/cache',
    'user_agent' => 'ifuri-roadmap/1.0 (+https://roadmap.ifuri.com)',
    'token'      => getenv('IFURI_GITHUB_TOKEN') ?: '',
];

<?php
declare(strict_types=1);

/**
 * Build/validate the roadmap site without network:
 * - manifest.json is valid and every doc file exists,
 * - each doc renders to non-empty HTML via Roadmap::md (local content),
 * - every internal [text](slug.md) link resolves to a manifest slug.
 *
 * Exit 0 when clean, 1 on any error. Used by CI (make test) and locally.
 */

require_once dirname(__DIR__) . '/lib/roadmap.php';

$root = dirname(__DIR__);
$errors = 0;
$manifest = json_decode((string) file_get_contents($root . '/manifest.json'), true);
if (!is_array($manifest['docs'] ?? null)) {
    fwrite(STDERR, "FAIL: manifest.json has no docs array\n");
    exit(1);
}

$slugs = [];
foreach ($manifest['docs'] as $d) {
    $slugs[(string) ($d['slug'] ?? '')] = true;
}

foreach ($manifest['docs'] as $d) {
    $slug = (string) ($d['slug'] ?? '');
    $file = $root . '/' . (string) ($d['file'] ?? '');
    if (!is_file($file)) {
        echo "FAIL: {$slug}: file {$d['file']} missing\n";
        $errors++;
        continue;
    }
    $md = (string) file_get_contents($file);
    $html = Roadmap::md($md);
    if (strlen(trim($html)) < 20) {
        echo "FAIL: {$slug}: rendered HTML is empty\n";
        $errors++;
    }
    // Internal slug.md links must resolve to a manifest slug.
    if (preg_match_all('/\]\(([0-9a-z-]+)\.md\)/i', $md, $m)) {
        foreach (array_unique($m[1]) as $target) {
            if (!isset($slugs[$target])) {
                echo "FAIL: {$slug}: links {$target}.md which is not in the manifest nav\n";
                $errors++;
            }
        }
    }
}

echo count($manifest['docs']) . " doc(s), {$errors} error(s)\n";
exit($errors ? 1 : 0);

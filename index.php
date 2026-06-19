<?php
declare(strict_types=1);
require __DIR__ . '/lib/roadmap.php';
$cfg = require __DIR__ . '/config.php';
$rm = new Roadmap($cfg);
$docs = $rm->docs();
$slug = isset($_GET['doc']) ? preg_replace('/[^a-z0-9-]/', '', (string) $_GET['doc']) : '';
if ($slug === '' && $docs) { $slug = (string) $docs[0]['slug']; }
$doc = $rm->doc($slug);
$body = Roadmap::md($doc['markdown']);
$title = '';
foreach ($docs as $d) { if ($d['slug'] === $slug) { $title = $d['title']; } }
$ghDir = sprintf('https://github.com/%s/%s', $cfg['owner'], $cfg['repo']);
$ghFile = $ghDir . '/blob/' . $cfg['branch'] . '/' . $slug . '.md';
$synced = date('Y-m-d H:i', $doc['synced_at']) . ' UTC';
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES); }
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?: 'roadmap') ?> · ifURI roadmap</title>
<meta name="description" content="ifURI / urirun roadmap, synced from GitHub.">
<meta name="theme-color" content="#1E1B4B">
<link rel="icon" href="https://ifuri.com/assets/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <a class="brand" href="?">ifURI <span>roadmap</span></a>
  <nav>
    <a href="https://ifuri.com/">ifuri.com</a>
    <a href="https://docs.ifuri.com/">Docs</a>
    <a href="https://examples.ifuri.com/">Examples</a>
    <a href="<?= e($ghDir) ?>">GitHub</a>
  </nav>
</header>
<div class="wrap">
  <aside>
    <?php foreach ($docs as $d): ?>
      <a href="?doc=<?= e($d['slug']) ?>"<?= $d['slug'] === $slug ? ' class="active"' : '' ?>><?= e($d['title']) ?></a>
    <?php endforeach; ?>
  </aside>
  <main>
    <?= $body ?>
    <p class="synced">Synced from <a href="<?= e($ghFile) ?>">GitHub</a> · last update <?= e($synced) ?> · source: <?= e($doc['source']) ?>. Edit on GitHub; this page refreshes once a day.</p>
  </main>
</div>
<footer>ifURI roadmap · synced from <a href="<?= e($ghDir) ?>"><?= e($cfg['owner'] . '/' . $cfg['repo']) ?></a> · <a href="https://ifuri.com/">ifuri.com</a></footer>
<script src="https://ifuri.com/assets/ifuri-ecobar.js" defer></script>
</body>
</html>

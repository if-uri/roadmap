<?php
declare(strict_types=1);

/**
 * Roadmap: serve roadmap markdown synced from the GitHub repo, cached on Plesk.
 *
 * - Nav comes from the local manifest.json (deployed with the site).
 * - Each doc's content is fetched from GitHub raw and cached for `ttl` seconds
 *   (default 1 day), so editing on GitHub updates the site once per day with no
 *   redeploy. The deployed *.md acts as an offline fallback.
 */
final class Roadmap
{
    /** @var array<string,mixed> */
    private array $cfg;

    public function __construct(array $cfg)
    {
        $this->cfg = $cfg;
        if (!is_dir($cfg['cache_dir'])) {
            @mkdir($cfg['cache_dir'], 0775, true);
        }
    }

    /** @return array<int,array{slug:string,file:string,title:string}> */
    public function docs(): array
    {
        $m = json_decode((string) @file_get_contents(__DIR__ . '/../manifest.json'), true);
        return is_array($m['docs'] ?? null) ? $m['docs'] : [];
    }

    public function title(): string
    {
        $m = json_decode((string) @file_get_contents(__DIR__ . '/../manifest.json'), true);
        return (string) ($m['title'] ?? 'roadmap');
    }

    /** @return array{markdown:string,synced_at:int,source:string} */
    public function doc(string $slug): array
    {
        $entry = null;
        foreach ($this->docs() as $d) {
            if (($d['slug'] ?? '') === $slug) { $entry = $d; break; }
        }
        if ($entry === null) {
            return ['markdown' => "# Not found\n\nNo such roadmap document.", 'synced_at' => time(), 'source' => 'none'];
        }
        $file = (string) $entry['file'];
        $cacheMd = $this->cfg['cache_dir'] . '/' . $slug . '.md';
        $cacheAt = $this->cfg['cache_dir'] . '/' . $slug . '.at';

        $synced = is_file($cacheAt) ? (int) file_get_contents($cacheAt) : 0;
        $fresh = is_file($cacheMd) && (time() - $synced) < (int) $this->cfg['ttl'];

        if ($fresh) {
            return ['markdown' => (string) file_get_contents($cacheMd), 'synced_at' => $synced, 'source' => 'cache'];
        }

        $raw = $this->fetchRaw($file);
        if ($raw !== null) {
            @file_put_contents($cacheMd, $raw);
            @file_put_contents($cacheAt, (string) time());
            return ['markdown' => $raw, 'synced_at' => time(), 'source' => 'github'];
        }
        // GitHub unreachable: stale cache, then the deployed file.
        if (is_file($cacheMd)) {
            return ['markdown' => (string) file_get_contents($cacheMd), 'synced_at' => $synced, 'source' => 'cache-stale'];
        }
        $local = __DIR__ . '/../' . $file;
        if (is_file($local)) {
            return ['markdown' => (string) file_get_contents($local), 'synced_at' => time(), 'source' => 'local'];
        }
        return ['markdown' => "# $slug\n\nContent unavailable.", 'synced_at' => time(), 'source' => 'none'];
    }

    private function fetchRaw(string $file): ?string
    {
        $url = sprintf('https://raw.githubusercontent.com/%s/%s/%s/%s',
            rawurlencode((string) $this->cfg['owner']),
            rawurlencode((string) $this->cfg['repo']),
            rawurlencode((string) $this->cfg['branch']),
            str_replace('%2F', '/', rawurlencode($file)));
        $headers = ['User-Agent: ' . $this->cfg['user_agent'], 'Accept: text/plain'];
        if (!empty($this->cfg['token'])) {
            $headers[] = 'Authorization: Bearer ' . $this->cfg['token'];
        }
        $ctx = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 8, 'header' => implode("\r\n", $headers), 'ignore_errors' => true]]);
        $data = @file_get_contents($url, false, $ctx);
        if ($data === false || $data === '') {
            return null;
        }
        // crude status check via $http_response_header
        if (isset($http_response_header[0]) && !preg_match('/\s2\d\d\s/', $http_response_header[0])) {
            return null;
        }
        return $data;
    }

    /** Minimal, safe Markdown -> HTML. */
    public static function md(string $text): string
    {
        $lines = preg_split('/\R/', $text);
        $out = [];
        $n = count($lines);
        $i = 0;
        $inl = static function (string $s): string {
            $s = htmlspecialchars($s, ENT_QUOTES);
            // [text](slug.md) -> ?doc=slug ; [text](url) -> url
            $s = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', static function ($m) {
                $href = $m[2];
                if (preg_match('/^([0-9a-z-]+)\.md$/i', $href, $mm)) {
                    $href = '?doc=' . rawurlencode($mm[1]);
                }
                return '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '">' . $m[1] . '</a>';
            }, $s);
            $s = preg_replace('/`([^`]+)`/', '<code>$1</code>', $s);
            $s = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $s);
            return $s;
        };
        while ($i < $n) {
            $ln = $lines[$i];
            if (str_starts_with($ln, '```')) {
                $i++; $buf = [];
                while ($i < $n && !str_starts_with($lines[$i], '```')) { $buf[] = htmlspecialchars($lines[$i], ENT_QUOTES); $i++; }
                $i++;
                $out[] = '<pre><code>' . implode("\n", $buf) . '</code></pre>';
                continue;
            }
            if (preg_match('/^(#{1,6})\s+(.*)$/', $ln, $m)) {
                $l = strlen($m[1]);
                $out[] = "<h$l>" . $inl($m[2]) . "</h$l>";
                $i++; continue;
            }
            if (isset($lines[$i + 1]) && str_starts_with(trim($ln), '|') && preg_match('/^\s*\|?[\s:|-]+\|?\s*$/', $lines[$i + 1])) {
                $head = array_map('trim', explode('|', trim($ln, " |")));
                $i += 2; $rows = [];
                while ($i < $n && str_starts_with(trim($lines[$i]), '|')) { $rows[] = array_map('trim', explode('|', trim($lines[$i], " |"))); $i++; }
                $t = '<table><thead><tr>';
                foreach ($head as $c) { $t .= '<th>' . $inl($c) . '</th>'; }
                $t .= '</tr></thead><tbody>';
                foreach ($rows as $r) { $t .= '<tr>'; foreach ($r as $c) { $t .= '<td>' . $inl($c) . '</td>'; } $t .= '</tr>'; }
                $out[] = $t . '</tbody></table>';
                continue;
            }
            if (preg_match('/^\s*[-*]\s+/', $ln)) {
                $items = [];
                while ($i < $n && preg_match('/^\s*[-*]\s+(.*)$/', $lines[$i], $m)) { $items[] = $inl($m[1]); $i++; }
                $out[] = '<ul><li>' . implode('</li><li>', $items) . '</li></ul>';
                continue;
            }
            if (preg_match('/^\s*\d+\.\s+/', $ln)) {
                $items = [];
                while ($i < $n && preg_match('/^\s*\d+\.\s+(.*)$/', $lines[$i], $m)) { $items[] = $inl($m[1]); $i++; }
                $out[] = '<ol><li>' . implode('</li><li>', $items) . '</li></ol>';
                continue;
            }
            if (trim($ln) === '') { $i++; continue; }
            $para = [$ln]; $i++;
            while ($i < $n && trim($lines[$i]) !== '' && !preg_match('/^(#{1,6}\s|```|\s*[-*]\s|\s*\d+\.\s|\s*\|)/', $lines[$i])) { $para[] = $lines[$i]; $i++; }
            $out[] = '<p>' . $inl(implode(' ', $para)) . '</p>';
        }
        return implode("\n", $out);
    }
}

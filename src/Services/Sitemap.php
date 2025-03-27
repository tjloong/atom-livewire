<?php

namespace Jiannius\Atom\Services;

class Sitemap
{
    public $urls = [];

    public function push($urls, $freq = 'monthly')
    {
        foreach ((array) $urls as $url) {
            $path = parse_url($url, PHP_URL_PATH);
            $priority = 1 - (substr_count($path, '/')/10);
            
            $this->urls[] = [
                'url' => $url,
                'added' => time(),
                'lastmod' => now()->toAtomString(),
                'priority' => $priority,
                'changefreq' => $freq,
            ];
        }

        return $this;
    }

    public function render()
    {
        $urls = collect($this->urls)->map(function($url) {
            return <<<EOL
            <url>
            <loc>{$url['url']}</loc>
            <lastmod>{$url['lastmod']}</lastmod>
            <changefreq>{$url['changefreq']}</changefreq>
            <priority>{$url['priority']}</priority>
            </url>
            EOL;
        })->implode("\n");

        return <<<EOL
        <?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
            {$urls}
        </urlset>
        EOL;
    }
}
<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $sitemaps = $this->getUrls()->map(function($freq, $url) {
            $path = parse_url($url, PHP_URL_PATH);
            $priority = 1 - (substr_count($path, '/')/10);
            
            return [
                'url' => $url,
                'added' => time(),
                'lastmod' => now()->toAtomString(),
                'priority' => $priority,
                'changefreq' => $freq,
            ];
        })->values()->all();

        return response()->view('atom::sitemap', compact('sitemaps'))->header('Content-Type', 'application/xml');
    }

    // get urls
    public function getUrls() : mixed
    {
        $sitemap = collect([url('/') => 'monthly']);

        if (has_table('blogs')) {
            foreach (model('blog')->status('published')->latest()->take(500)->get() as $blog) {
                $sitemap->put(route('web.blog', $blog->slug), 'monthly');
            }
        }

        if (has_table('announcements')) {
            foreach (model('announcement')->status('PUBLISHED')->get() as $announcement) {
                $sitemap->put(route('web.announcement', $announcement->slug), 'monthly');
            }
        }
        
        if (has_table('pages')) {
            foreach (model('page')->get() as $page) {
                $sitemap->put(route('web.page', $page->slug), 'monthly');
            }
        }

        return $sitemap;
    }
}
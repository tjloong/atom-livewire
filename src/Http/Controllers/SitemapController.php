<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $sitemap = $this->getSitemap();

        foreach ($sitemap as $url => $changefreq) {
            $path = parse_url($url, PHP_URL_PATH);
            $priority = 1 - (substr_count($path, '/')/10);
            $sitemap[$url] = [
                'added' => time(),
                'lastmod' => now()->toAtomString(),
                'priority' => $priority,
                'changefreq' => $changefreq,
            ];
        }

        return response()
            ->view('atom::sitemap', compact('sitemap'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Get sitemap
     */
    public function getSitemap()
    {
        $sitemap = ['/' => 'monthly'];

        if (enabled_module('blogs')) {
            foreach (model('blog')->status('published')->latest()->take(500)->get() as $blog) {
                $sitemap['/blog/'.$blog->slug] = 'monthly';
            }
        }
        
        if (enabled_module('pages')) {
            foreach (model('page')->getSlugs() as $slug) {
                $sitemap['/'.$slug] = 'monthly';
            }
        }

        return $sitemap;
    }
}
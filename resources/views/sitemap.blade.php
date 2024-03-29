<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    @foreach($sitemaps as $sitemap)
    <url>
        <loc>{{ data_get($sitemap, 'url') }}</loc>
        <lastmod>{{ data_get($sitemap, 'lastmod') }}</lastmod>
        <changefreq>{{ data_get($sitemap, 'freq') }}</changefreq>
        <priority>{{ data_get($sitemap, 'priority') }}</priority>
    </url>
    @endforeach
</urlset>
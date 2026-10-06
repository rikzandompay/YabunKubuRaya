{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('artikel.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('galeri.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@foreach ($articles as $article)
    <url>
        <loc>{{ route('artikel.show', $article->slug) }}</loc>
        <lastmod>{{ ($article->updated_at ?? $article->published_at ?? now())->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
</urlset>

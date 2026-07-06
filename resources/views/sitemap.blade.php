<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <url>
        <loc>{{ url('/') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en') }}"/>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/travel-packages') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/travel-packages') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/travel-packages') }}"/>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @foreach($travel_packages as $package)
    <url>
        <loc>{{ url('/travel-packages/' . $package->slug) }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/travel-packages/' . $package->slug) }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/travel-packages/' . $package->slug) }}"/>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    <url>
        <loc>{{ url('/blogs') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/blogs') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/blogs') }}"/>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($blogs as $blog)
    <url>
        <loc>{{ url('/blogs/' . $blog->slug) }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/blogs/' . $blog->slug) }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/blogs/' . $blog->slug) }}"/>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    <url>
        <loc>{{ url('/gallery') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/gallery') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/gallery') }}"/>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/about-us') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/about-us') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/about-us') }}"/>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/homestay') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/homestay') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/homestay') }}"/>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/community-impact') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/community-impact') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/community-impact') }}"/>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/contact') }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/contact') }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/contact') }}"/>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
</urlset>

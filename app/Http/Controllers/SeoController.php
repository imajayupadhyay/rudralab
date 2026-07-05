<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            [
                'loc' => url('/'),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => url('/verify-certificate'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => url('/contact'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];

        return response()
            ->view('seo.sitemap', [
                'urls' => $urls,
                'lastmod' => now()->toDateString(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /rbtl/',
            'Disallow: /rbtl-secure-login',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}

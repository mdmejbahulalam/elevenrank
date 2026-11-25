<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SeoFilesController extends Controller
{
    /** Public marketing URLs. Account and admin areas are never listed. */
    private const PAGES = ['/', '/features', '/pro', '/pricing', '/docs', '/docs/hooks', '/changelog', '/support', '/about', '/terms', '/privacy', '/refunds', '/gpl'];

    public function robots(): Response
    {
        $body = "User-agent: *\nDisallow: /account\nDisallow: /admin\nDisallow: /checkout\nDisallow: /wp-json/\n\nSitemap: ".url('/sitemap.xml')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach (self::PAGES as $path) {
            $xml .= '<url><loc>'.e(url($path)).'</loc></url>'."\n";
        }

        return response($xml.'</urlset>'."\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function llms(): Response
    {
        $lines = ['# ElevenRank', '', '> A free all-in-one SEO plugin for WordPress (GPL, no tracking), with a paid Pro add-on for audits, automation and AI tools.', '', '## Pages'];
        foreach (['/features' => 'Features of the free plugin', '/pro' => 'ElevenRank Pro', '/pricing' => 'Pricing', '/docs' => 'Documentation', '/changelog' => 'Changelog', '/support' => 'Support'] as $path => $label) {
            $lines[] = '- ['.$label.']('.url($path).')';
        }

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}

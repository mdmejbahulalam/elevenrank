<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Builds the three CSS bundles without Node. The shared hosting has no Node, and the
 * design is plain CSS, so concatenating and lightly minifying is enough.
 * Output goes to public/assets and is committed.
 */
class BuildAssets extends Command
{
    protected $signature = 'assets:build';

    protected $description = 'Build public/assets/{site,account,admin}.css from resources/css';

    public function handle(): int
    {
        $dir = resource_path('css');
        $out = public_path('assets');
        if (! is_dir($out)) {
            mkdir($out, 0775, true);
        }
        $shared = ['tokens', 'base', 'components'];
        $total = [];
        foreach (['site', 'account', 'admin'] as $skin) {
            $css = '';
            foreach (array_merge($shared, [$skin], $skin === 'site' ? ['home'] : ['shell']) as $part) {
                $css .= file_get_contents("$dir/$part.css")."\n";
            }
            $css = $this->minify($css);
            file_put_contents("$out/$skin.css", $css);
            $total[$skin] = strlen($css);
            $this->line(sprintf('%-8s %6d bytes (%d bytes gzip)', "$skin.css", strlen($css), strlen(gzencode($css, 9))));
        }

        return self::SUCCESS;
    }

    private function minify(string $css): string
    {
        $css = preg_replace('!/\*.*?\*/!s', '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/\s*([{};,>])\s*/', '$1', $css);
        $css = preg_replace('/:\s+/', ':', $css);

        return trim(str_replace(';}', '}', $css))."\n";
    }
}

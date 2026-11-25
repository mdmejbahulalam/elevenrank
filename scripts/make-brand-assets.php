<?php
/**
 * Draws the ElevenRank brand images into public/brand with PHP GD: mark icons, logo lockups,
 * social cards and WordPress.org banners. Run: php scripts/make-brand-assets.php [fonts-dir]
 * Needs Segoe UI (segoeui.ttf, segoeuib.ttf, seguibl.ttf) in the fonts directory.
 */
$fonts = rtrim($argv[1] ?? 'C:/Windows/Fonts', '/');
$out = dirname(__DIR__).'/public/brand';
@mkdir($out, 0775, true);
$F = ['reg' => "$fonts/segoeui.ttf", 'bold' => "$fonts/segoeuib.ttf", 'black' => "$fonts/seguibl.ttf"];
foreach ($F as $f) {
    if (! is_file($f)) {
        fwrite(STDERR, "Missing font $f\n");
        exit(1);
    }
}

function col($im, string $hex, int $alpha = 0): int
{
    return imagecolorallocatealpha($im, hexdec(substr($hex, 1, 2)), hexdec(substr($hex, 3, 2)), hexdec(substr($hex, 5, 2)), $alpha);
}

function canvas(int $w, int $h, ?string $bg)
{
    $im = imagecreatetruecolor($w, $h);
    imagesavealpha($im, true);
    imagealphablending($im, false);
    imagefill($im, 0, 0, $bg ? col($im, $bg) : imagecolorallocatealpha($im, 0, 0, 0, 127));
    imagealphablending($im, true);

    return $im;
}

function rrect($im, float $x, float $y, float $w, float $h, float $r, int $color): void
{
    $x2 = $x + $w; $y2 = $y + $h;
    imagefilledrectangle($im, (int) ($x + $r), (int) $y, (int) ($x2 - $r), (int) $y2, $color);
    imagefilledrectangle($im, (int) $x, (int) ($y + $r), (int) $x2, (int) ($y2 - $r), $color);
    foreach ([[$x + $r, $y + $r], [$x2 - $r, $y + $r], [$x + $r, $y2 - $r], [$x2 - $r, $y2 - $r]] as [$cx, $cy]) {
        imagefilledellipse($im, (int) $cx, (int) $cy, (int) (2 * $r), (int) (2 * $r), $color);
    }
}

/** The mark, drawn 4x and scaled down so edges are smooth, then copied onto $dst at ($x,$y) with size $size. */
function mark($dst, int $x, int $y, int $size): void
{
    $s = $size * 4; $k = $s / 64;
    $im = canvas($s, $s, null);
    rrect($im, 0, 0, $s, $s, 14 * $k, col($im, '#4338ca'));
    rrect($im, 14 * $k, 30 * $k, 12 * $k, 20 * $k, 3 * $k, col($im, '#c3bfeb'));
    $w = col($im, '#ffffff');
    imagefilledrectangle($im, (int) (34 * $k), (int) (26 * $k), (int) (44 * $k), (int) (50 * $k), $w);
    imagefilledpolygon($im, [(int) (29 * $k), (int) (26 * $k), (int) (39 * $k), (int) (14 * $k), (int) (49 * $k), (int) (26 * $k)], $w);
    imagealphablending($dst, true);
    imagecopyresampled($dst, $im, $x, $y, 0, 0, $size, $size, $s, $s);
}

function width(string $font, float $px, string $text): float
{
    $b = imagettfbbox($px, 0, $font, $text);

    return $b[2] - $b[0];
}

/** Text whose width is exactly $target pixels (the canvases fix the widths). Returns the width used. */
function fit($im, string $font, string $text, float $x, float $baseline, float $target, string $hex): float
{
    $px = 10.0;
    for ($i = 0; $i < 30; $i++) {
        $px *= $target / width($font, $px, $text);
    }
    imagettftext($im, $px, 0, (int) $x, (int) $baseline, col($im, $hex), $font, $text);

    return $px;
}

/** "ElevenRank" with the second half in the accent colour. $target is the whole word's width. */
function wordmark($im, array $F, float $x, float $baseline, float $target, string $c1, string $c2): void
{
    $px = 10.0;
    for ($i = 0; $i < 30; $i++) {
        $px *= $target / width($F['black'], $px, 'ElevenRank');
    }
    $w1 = width($F['black'], $px, 'Eleven');
    imagettftext($im, $px, 0, (int) $x, (int) $baseline, col($im, $c1), $F['black'], 'Eleven');
    imagettftext($im, $px, 0, (int) ($x + $w1), (int) $baseline, col($im, $c2), $F['black'], 'Rank');
}

// 1. Mark icons.
foreach ([256, 128, 64, 32, 16] as $size) {
    $im = canvas($size, $size, null);
    mark($im, 0, 0, $size);
    imagepng($im, "$out/icon-$size.png");
}
copy("$out/icon-64.png", dirname(__DIR__).'/public/assets/mark.png');

// 2. Logo lockups, transparent.
foreach (['light' => ['#101828', '#4338ca'], 'dark' => ['#ecedfb', '#8b86ff']] as $name => [$c1, $c2]) {
    $im = canvas(1000, 200, null);
    mark($im, 0, 20, 160);
    wordmark($im, $F, 200, 143, 640, $c1, $c2);
    imagepng($im, "$out/logo-$name.png");
}

// 3. Social cards, 1200x630.
foreach (['default' => ['SEO that stays on your site.', null, 928, 345, 499, 400], 'pro' => ['ElevenRank Pro: 15 more', 'features.', 855, 305, 499, 441]] as $name => [$l1, $l2, $headW, $base1, $subW, $subBase]) {
    $im = canvas(1200, 630, '#f5f6f8');
    mark($im, 72, 72, 64);
    wordmark($im, $F, 156, 120, 217, '#101828', '#4338ca');
    $px = fit($im, $F['bold'], $l1, 72, $base1, $headW, '#101828');
    if ($l2) {
        imagettftext($im, $px, 0, 72, 384, col($im, '#101828'), $F['bold'], $l2);
    }
    fit($im, $F['reg'], 'Free WordPress SEO plugin. No tracking.', 72, $subBase, $subW, '#475467');
    rrect($im, 72, 548, 160, 10, 5, col($im, '#4338ca'));
    imagepng($im, "$out/social-$name.png");
}

// 4. WordPress.org banners.
foreach ([[1544, 500, 1.0, 'banner-1544x500.png'], [772, 250, 0.5, 'banner-772x250.png']] as [$w, $h, $s, $file]) {
    $im = canvas($w, $h, '#312e81');
    mark($im, (int) (90 * $s), (int) (142 * $s), (int) (70 * $s));
    wordmark($im, $F, 184 * $s, 195 * $s, 261 * $s, '#ffffff', '#bdb8fa');
    fit($im, $F['bold'], 'SEO that stays on your site.', 90 * $s, 295 * $s, 688 * $s, '#ffffff');
    fit($im, $F['reg'], 'Titles, sitemaps, schema and redirects. No tracking.', 90 * $s, 350 * $s, 582 * $s, '#c9c6f7');
    rrect($im, 1138 * $s, 166 * $s, 316 * $s, 168 * $s, 24 * $s, col($im, '#ffffff'));
    fit($im, $F['reg'], 'SEO score', 1160 * $s, 208 * $s, 90 * $s, '#475467');
    fit($im, $F['bold'], '86', 1160 * $s, 280 * $s, 62 * $s, '#067647');
    rrect($im, 1160 * $s, 300 * $s, 272 * $s, 12 * $s, 6 * $s, col($im, '#e4e7ec'));
    rrect($im, 1160 * $s, 300 * $s, 234 * $s, 12 * $s, 6 * $s, col($im, '#4338ca'));
    imagepng($im, "$out/$file");
}

echo "Brand images written to $out\n";

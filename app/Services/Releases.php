<?php

namespace App\Services;

use DomainException;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * The Pro release on the private disk: releases/elevenrank-pro.zip plus releases/latest.json
 * holding {"version":"x.y.z"}. Older zips are kept as elevenrank-pro-x.y.z.zip so a bad release can
 * be rolled back. Never served directly; the download route checks the licence first.
 */
class Releases
{
    public const ZIP = 'releases/elevenrank-pro.zip';

    public const META = 'releases/latest.json';

    private function disk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk('local');
    }

    /** @return array{version: string, path: string}|null */
    public function current(): ?array
    {
        if (! $this->disk()->exists(self::ZIP) || ! $this->disk()->exists(self::META)) {
            return null;
        }

        $data = json_decode((string) $this->disk()->get(self::META), true);

        return is_array($data) && ! empty($data['version'])
            ? ['version' => (string) $data['version'], 'path' => $this->disk()->path(self::ZIP)]
            : null;
    }

    public function currentVersion(): string
    {
        return $this->current()['version'] ?? '';
    }

    /** @return string[] Published versions that can be restored, newest first. */
    public function history(): array
    {
        $versions = [];

        foreach ($this->disk()->files('releases') as $file) {
            if (preg_match('/elevenrank-pro-(.+)\.zip$/', $file, $m)) {
                $versions[] = $m[1];
            }
        }

        usort($versions, 'version_compare');

        return array_reverse($versions);
    }

    /**
     * Reads the version of a Pro zip, refusing anything that is not the add-on.
     *
     * @throws DomainException
     */
    public function inspect(string $file): string
    {
        $zip = new ZipArchive;

        if (true !== $zip->open($file)) {
            throw new DomainException('That file is not a valid zip.');
        }

        $main = $zip->getFromName('elevenrank-pro/elevenrank-pro.php');
        $zip->close();

        if (false === $main) {
            throw new DomainException('The zip must contain elevenrank-pro/elevenrank-pro.php.');
        }

        if (! preg_match('/^\s*\*\s*Version:\s*([0-9]+\.[0-9]+\.[0-9]+(?:-[0-9A-Za-z.]+)?)\s*$/m', $main, $header)
            || ! preg_match("/define\( 'ELEVENRANK_PRO_VERSION', '([^']+)' \)/", $main, $const)) {
            throw new DomainException('Could not read the version from the plugin file.');
        }

        if ($header[1] !== $const[1]) {
            throw new DomainException('The version in the plugin header and the version constant differ.');
        }

        return $header[1];
    }

    /**
     * Validates a zip and makes it the release customers receive. Going backwards is refused unless
     * $allowOlder is set, so an old file cannot replace a newer one by mistake.
     *
     * @throws DomainException
     */
    public function publish(string $file, bool $allowOlder = false): string
    {
        $version = $this->inspect($file);
        $current = $this->currentVersion();

        if ('' !== $current && ! $allowOlder && version_compare($version, $current, '<')) {
            throw new DomainException("Version {$version} is older than the {$current} now being served.");
        }

        $versioned = "releases/elevenrank-pro-{$version}.zip";

        if (realpath($file) !== realpath($this->disk()->path($versioned))) {
            $stream = fopen($file, 'rb');
            $this->disk()->put($versioned, $stream);
            is_resource($stream) && fclose($stream);
        }

        $this->disk()->copy($versioned, self::ZIP);
        $this->disk()->put(self::META, json_encode(['version' => $version])."\n");

        return $version;
    }

    /** @throws DomainException */
    public function restore(string $version): string
    {
        $path = "releases/elevenrank-pro-{$version}.zip";

        if (! preg_match('/^[0-9A-Za-z.\-]+$/', $version) || ! $this->disk()->exists($path)) {
            throw new DomainException('That version is not available.');
        }

        return $this->publish($this->disk()->path($path), true);
    }
}

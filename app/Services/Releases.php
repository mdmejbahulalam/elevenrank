<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * The Pro release on the private disk: releases/elevenrank-pro.zip plus releases/latest.json
 * holding {"version":"x.y.z"}. Never served directly; the download route checks the licence first.
 */
class Releases
{
    public const ZIP = 'releases/elevenrank-pro.zip';

    public const META = 'releases/latest.json';

    /** @return array{version: string, path: string}|null */
    public function current(): ?array
    {
        $disk = Storage::disk('local');

        if (! $disk->exists(self::ZIP) || ! $disk->exists(self::META)) {
            return null;
        }

        $data = json_decode((string) $disk->get(self::META), true);

        return is_array($data) && ! empty($data['version'])
            ? ['version' => (string) $data['version'], 'path' => $disk->path(self::ZIP)]
            : null;
    }
}

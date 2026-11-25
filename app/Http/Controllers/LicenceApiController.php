<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use App\Services\Releases;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * The API the Pro add-on calls: activate, deactivate, check, update and download.
 * Answers keep the shape of the earlier WordPress licence server so shipped copies keep working.
 */
class LicenceApiController extends Controller
{
    /** @return array{0: ?Licence, 1: string} */
    private function lookup(Request $request): array
    {
        $key = trim((string) $request->input('key'));
        $site = Licence::normaliseSite((string) $request->input('site'));

        return ['' === $key || '' === $site ? null : Licence::where('license_key', $key)->first(), $site];
    }

    private function answer(string $status, string $message, ?Licence $licence = null): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'expires' => $licence?->expires ? $licence->expires->utc()->format('Y-m-d') : '',
        ]);
    }

    /** @return array{0: string, 1: string} Status and text, or two empty strings when the licence is usable. */
    private function problem(?Licence $licence): array
    {
        if (! $licence) {
            return ['invalid', 'That licence key was not found.'];
        }

        if ($licence->status !== 'active') {
            return ['revoked', 'This licence is no longer active.'];
        }

        if ($licence->isExpired()) {
            return ['expired', 'This licence has expired. Renew it to receive updates.'];
        }

        return ['', ''];
    }

    public function activate(Request $request): JsonResponse
    {
        [$licence, $site] = $this->lookup($request);
        [$status, $text] = $this->problem($licence);

        if ($status !== '') {
            return $this->answer($status, $text, $licence);
        }

        if (! $licence->activateSite($site)) {
            return $this->answer('limit', 'This licence is already used on its maximum number of sites. Deactivate it on another site first.', $licence);
        }

        return $this->answer('valid', 'Licence activated.', $licence);
    }

    public function check(Request $request): JsonResponse
    {
        [$licence, $site] = $this->lookup($request);
        [$status, $text] = $this->problem($licence);

        if ($status !== '') {
            return $this->answer($status, $text, $licence);
        }

        if (! in_array($site, $licence->sites(), true)) {
            return $this->answer('invalid', 'This site has not activated the licence.', $licence);
        }

        return $this->answer('valid', '', $licence);
    }

    public function deactivate(Request $request): JsonResponse
    {
        [$licence, $site] = $this->lookup($request);

        $licence?->deactivateSite($site);

        return $this->answer('inactive', 'Licence deactivated on this site.');
    }

    public function update(Request $request, Releases $releases): JsonResponse
    {
        [$licence, $site] = $this->lookup($request);
        [$status] = $this->problem($licence);
        $info = $releases->current();

        if ($status !== '' || ! $info || ! in_array($site, $licence->sites(), true)) {
            return response()->json([]);
        }

        return response()->json([
            'version' => $info['version'],
            'package' => route('licence.download', ['key' => $licence->license_key, 'site' => $site]),
        ]);
    }

    public function download(Request $request, Releases $releases): BinaryFileResponse|JsonResponse
    {
        [$licence, $site] = $this->lookup($request);
        [$status] = $this->problem($licence);
        $info = $releases->current();

        if ($status !== '' || ! $info || ! in_array($site, $licence->sites(), true)) {
            return response()->json(['code' => 'erls_forbidden', 'message' => 'Download not allowed.'], 403);
        }

        return response()->download($info['path'], 'elevenrank-pro.zip', ['Content-Type' => 'application/zip', 'Cache-Control' => 'no-store']);
    }
}

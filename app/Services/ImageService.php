<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    private const DISK = 'public';

    public function storeUploaded(UploadedFile $file, string $directory = 'covers'): string
    {
        return $file->store($directory, self::DISK);
    }

    public function storeFromUrl(string $url, string $directory = 'covers'): ?string
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        $response = Http::timeout(10)->get($url);

        if (! $response->ok()) {
            return null;
        }

        $extension = $this->extensionFromContentType($response->header('Content-Type'))
            ?? $this->extensionFromUrl($url)
            ?? 'jpg';

        $path = $directory.'/'.Str::random(40).'.'.$extension;

        Storage::disk(self::DISK)->put($path, $response->body());

        return $path;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk(self::DISK)->delete($path);
    }

    private function extensionFromContentType(?string $contentType): ?string
    {
        if (! $contentType) {
            return null;
        }

        return match (true) {
            str_contains($contentType, 'jpeg'), str_contains($contentType, 'jpg') => 'jpg',
            str_contains($contentType, 'png') => 'png',
            str_contains($contentType, 'webp') => 'webp',
            str_contains($contentType, 'gif') => 'gif',
            default => null,
        };
    }

    private function extensionFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! $path) {
            return null;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? $ext : null;
    }
}

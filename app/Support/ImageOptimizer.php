<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageOptimizer
{
    public const MIN_KB = 200;
    public const MAX_KB = 300;

    /**
     * Save as WebP keeping original width/height.
     * Large files are compressed to ~300 KB max (usually 1–3 encode passes).
     */
    public static function store(UploadedFile $file, string $directory, ?string $basename = null): string
    {
        [$fullDir, $relativeDir] = self::resolveDirectory($directory);
        File::ensureDirectoryExists($fullDir);

        $basename = $basename
            ? self::normalizeBasename($basename)
            : self::makeBasename($file);

        $fullPath     = $fullDir . $basename;
        $relativePath = $relativeDir . $basename;
        $maxBytes     = self::MAX_KB * 1024;
        $sourcePath   = $file->getRealPath();

        self::saveOptimizedWebp($sourcePath, $fullPath, $maxBytes);

        return $relativePath;
    }

    /**
     * Convert any upload to WebP (fixed quality, optional transform).
     */
    public static function toWebp(
        UploadedFile $file,
        string $directory,
        ?string $basename = null,
        int $quality = 92,
        ?callable $transform = null
    ): string {
        [$fullDir, $relativeDir] = self::resolveDirectory($directory);
        File::ensureDirectoryExists($fullDir);

        $basename = $basename
            ? self::normalizeBasename($basename)
            : self::makeBasename($file);

        $fullPath     = $fullDir . $basename;
        $relativePath = $relativeDir . $basename;

        $image = Image::make($file->getRealPath());
        self::orientate($image);

        if ($transform) {
            $transform($image);
        }

        $image->encode('webp', $quality);
        $image->save($fullPath);
        $image->destroy();

        return $relativePath;
    }

    public static function makeBasename(UploadedFile $file): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = Str::slug($name) ?: 'image';

        return time() . '-' . uniqid() . '-' . $name . '.webp';
    }

    public static function normalizeBasename(string $basename): string
    {
        $basename = trim($basename);
        $basename = preg_replace('/\.(jpe?g|png|gif|webp|bmp)$/i', '', $basename) ?? $basename;
        $basename = Str::slug($basename) ?: 'image';

        return $basename . '.webp';
    }

    private static function resolveDirectory(string $directory): array
    {
        $directory = rtrim(str_replace('\\', '/', $directory), '/') . '/';

        if (str_starts_with($directory, 'public/')) {
            return [base_path($directory), $directory];
        }

        return [public_path($directory), $directory];
    }

    /**
     * Fast path: max 3 WebP encodes instead of slow binary search.
     */
    private static function saveOptimizedWebp(string $sourcePath, string $fullPath, int $maxBytes): void
    {
        $startQuality = 88;
        $size         = self::saveAtQuality($sourcePath, $fullPath, $startQuality);

        if ($size <= $maxBytes) {
            return;
        }

        $estimated = (int) round($startQuality * pow($maxBytes / max($size, 1), 0.72) * 0.94);
        $estimated = max(52, min(90, $estimated));

        if ($estimated !== $startQuality) {
            $size = self::saveAtQuality($sourcePath, $fullPath, $estimated);
            if ($size <= $maxBytes) {
                return;
            }
        }

        $fallback = max(48, $estimated - 14);
        if ($fallback < $estimated) {
            self::saveAtQuality($sourcePath, $fullPath, $fallback);
        }
    }

    private static function saveAtQuality(string $sourcePath, string $fullPath, int $quality): int
    {
        $image = Image::make($sourcePath);
        self::orientate($image);
        $image->encode('webp', $quality);
        $image->save($fullPath);
        $image->destroy();

        return file_exists($fullPath) ? (int) filesize($fullPath) : 0;
    }

    private static function orientate($image): void
    {
        if (method_exists($image, 'orientate')) {
            $image->orientate();
        }
    }
}

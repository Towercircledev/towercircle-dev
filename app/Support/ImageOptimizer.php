<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/** Shrinks uploaded photos (max 1920 px wide) and saves them as WebP, so pages stay fast whatever is uploaded. */
class ImageOptimizer
{
    public const MAX_WIDTH = 1920;

    public static function store(UploadedFile $file, string $dir): string
    {
        $mime = $file->getMimeType();
        $raster = in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true);

        if (! $raster || ! function_exists('imagewebp')) {
            return $file->store($dir, 'public');
        }

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'image/png' => @imagecreatefrompng($file->getRealPath()),
            'image/webp' => @imagecreatefromwebp($file->getRealPath()),
        };
        if (! $src) {
            return $file->store($dir, 'public');
        }

        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $o = @exif_read_data($file->getRealPath())['Orientation'] ?? 1;
            $src = match ($o) { 3 => imagerotate($src, 180, 0), 6 => imagerotate($src, -90, 0), 8 => imagerotate($src, 90, 0), default => $src };
        }

        $w = imagesx($src);
        $h = imagesy($src);
        if ($w > self::MAX_WIDTH) {
            $nh = (int) round($h * self::MAX_WIDTH / $w);
            $dst = imagecreatetruecolor(self::MAX_WIDTH, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, self::MAX_WIDTH, $nh, $w, $h);
            imagedestroy($src);
            $src = $dst;
        } else {
            imagepalettetotruecolor($src);
            imagealphablending($src, false);
            imagesavealpha($src, true);
        }

        $name = trim($dir, '/').'/'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'-'.Str::lower(Str::random(6)).'.webp';
        $tmp = tempnam(sys_get_temp_dir(), 'img');
        imagewebp($src, $tmp, 82);
        imagedestroy($src);
        \Illuminate\Support\Facades\Storage::disk('public')->put($name, file_get_contents($tmp));
        @unlink($tmp);

        return $name;
    }
}

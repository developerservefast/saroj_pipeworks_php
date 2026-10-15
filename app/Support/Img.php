<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Img
{
    public static function store(UploadedFile $file, string $dir, int $max = 1200): string
    {
        $mime = $file->getMimeType();
        if (! function_exists('imagewebp') || ! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return $file->store($dir, 'public');
        }

        $p = $file->getRealPath();
        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($p),
            'image/png' => @imagecreatefrompng($p),
            default => @imagecreatefromwebp($p),
        };
        if (! $src) {
            return $file->store($dir, 'public');
        }

        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $o = @exif_read_data($p)['Orientation'] ?? 1;
            $angle = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
            if ($angle && ($rot = imagerotate($src, $angle, 0))) {
                $src = $rot;
            }
        }

        imagepalettetotruecolor($src);
        $w = imagesx($src);
        $h = imagesy($src);
        if (max($w, $h) > $max) {
            $r = $max / max($w, $h);
            $nw = (int) round($w * $r);
            $nh = (int) round($h * $r);
            $dst = imagecreatetruecolor($nw, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            $src = $dst;
        }
        imagealphablending($src, false);
        imagesavealpha($src, true);

        ob_start();
        imagewebp($src, null, 82);
        $data = ob_get_clean();

        $path = $dir . '/' . Str::random(40) . '.webp';
        Storage::disk('public')->put($path, $data);

        return $path;
    }
}
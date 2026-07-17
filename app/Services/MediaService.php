<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    const IMAGE_MIMES = [
        'image/jpeg', 'image/png', 'image/webp', 'image/gif',
        'image/svg+xml', 'image/avif', 'image/bmp', 'image/tiff',
        'image/heic', 'image/heif',
    ];

    const VIDEO_MIMES = [
        'video/mp4', 'video/quicktime', 'video/x-msvideo',
        'video/x-matroska', 'video/webm', 'video/x-m4v',
        'video/mpeg', 'video/mpg', 'video/x-ms-wmv',
        'video/3gpp', 'video/3gpp2',
    ];

    const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif', 'bmp', 'tiff', 'tif', 'heic', 'heif'];

    const VIDEO_EXTENSIONS = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'm4v', 'mpeg', 'mpg', 'wmv', '3gp', '3g2'];

    const MAX_IMAGE_SIZE = 20480; // KB (20 MB)
    const MAX_VIDEO_SIZE = 307200; // KB (300 MB)
    const THUMB_SIZE = 300;
    const COMPRESS_QUALITY = 85;
    const MAX_DIMENSION = 4000;

    public function detectMimeType(UploadedFile $file): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getPathname());
        finfo_close($finfo);
        return $mime ?: $file->getClientMimeType();
    }

    public function isImage(string $mime): bool
    {
        return in_array($mime, self::IMAGE_MIMES, true);
    }

    public function isVideo(string $mime): bool
    {
        return in_array($mime, self::VIDEO_MIMES, true);
    }

    public function getTypeFromMime(string $mime): string
    {
        if ($this->isImage($mime)) return 'image';
        if ($this->isVideo($mime)) return 'video';
        return 'other';
    }

    public function getExtensionFromMime(string $mime): string
    {
        $map = [
            'image/jpeg' => 'jpg', 'image/png' => 'png',
            'image/webp' => 'webp', 'image/gif' => 'gif',
            'image/svg+xml' => 'svg', 'image/avif' => 'avif',
            'image/bmp' => 'bmp', 'image/tiff' => 'tiff',
            'image/heic' => 'heic', 'image/heif' => 'heif',
            'video/mp4' => 'mp4', 'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi', 'video/x-matroska' => 'mkv',
            'video/webm' => 'webm', 'video/x-m4v' => 'm4v',
            'video/mpeg' => 'mpeg', 'video/mpg' => 'mpg',
            'video/x-ms-wmv' => 'wmv', 'video/3gpp' => '3gp',
            'video/3gpp2' => '3g2',
        ];
        return $map[$mime] ?? 'bin';
    }

    public function isAllowed(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $mime = $this->detectMimeType($file);

        $allowedExts = array_merge(self::IMAGE_EXTENSIONS, self::VIDEO_EXTENSIONS);

        if (!in_array($ext, $allowedExts, true)) {
            return ['allowed' => false, 'reason' => "Ekstensi .{$ext} tidak didukung."];
        }

        $allowedMimes = array_merge(self::IMAGE_MIMES, self::VIDEO_MIMES);
        if (!in_array($mime, $allowedMimes, true)) {
            return ['allowed' => false, 'reason' => "Tipe file {$mime} tidak didukung."];
        }

        $maxSize = $this->isImage($mime) ? self::MAX_IMAGE_SIZE : self::MAX_VIDEO_SIZE;
        $sizeKB = $file->getSize() / 1024;
        if ($sizeKB > $maxSize) {
            $label = $this->isImage($mime) ? 'Gambar' : 'Video';
            $maxMB = $maxSize / 1024;
            return ['allowed' => false, 'reason' => "{$label} maksimal {$maxMB}MB."];
        }

        return ['allowed' => true, 'mime' => $mime, 'type' => $this->getTypeFromMime($mime)];
    }

    public function hashFile(UploadedFile $file): string
    {
        return hash_file('sha256', $file->getPathname());
    }

    public function generateUniqueFilename(string $extension): string
    {
        return Str::random(40) . '.' . $extension;
    }

    public function compressImage(string $path, string $disk = 'public'): void
    {
        $fullPath = Storage::disk($disk)->path($path);
        if (!file_exists($fullPath)) return;

        $mime = mime_content_type($fullPath);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) return;

        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($fullPath),
            'image/png'  => imagecreatefrompng($fullPath),
            'image/webp' => imagecreatefromwebp($fullPath),
        };

        if (!$image) return;

        $w = imagesx($image);
        $h = imagesy($image);

        // Downscale if exceeds max dimension
        if ($w > self::MAX_DIMENSION || $h > self::MAX_DIMENSION) {
            $ratio = min(self::MAX_DIMENSION / $w, self::MAX_DIMENSION / $h);
            $nw = (int) round($w * $ratio);
            $nh = (int) round($h * $ratio);
            $resampled = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($resampled, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($image);
            $image = $resampled;
        }

        // Save compressed
        $tmp = $fullPath . '.tmp';
        match ($mime) {
            'image/jpeg' => imagejpeg($image, $tmp, self::COMPRESS_QUALITY),
            'image/png'  => imagepng($image, $tmp, (int) round((100 - self::COMPRESS_QUALITY) / 10)),
            'image/webp' => imagewebp($image, $tmp, self::COMPRESS_QUALITY),
        };
        imagedestroy($image);

        if (file_exists($tmp)) {
            rename($tmp, $fullPath);
        }
    }

    public function generateThumbnail(string $path, string $disk = 'public'): ?string
    {
        $fullPath = Storage::disk($disk)->path($path);
        if (!file_exists($fullPath)) return null;

        $mime = mime_content_type($fullPath);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp'], true)) return null;

        $image = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($fullPath),
            'image/png'  => imagecreatefrompng($fullPath),
            'image/webp' => imagecreatefromwebp($fullPath),
            'image/gif'  => imagecreatefromgif($fullPath),
            'image/bmp'  => imagecreatefrombmp($fullPath),
        };

        if (!$image) return null;

        $w = imagesx($image);
        $h = imagesy($image);
        $size = min($w, $h, self::THUMB_SIZE);
        $ratio = $size / max($w, $h);
        $nw = (int) round($w * $ratio);
        $nh = (int) round($h * $ratio);

        $thumb = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($image);

        $dir = dirname($path);
        $base = pathinfo($path, PATHINFO_FILENAME);
        $thumbPath = $dir . '/' . $base . '_thumb.jpg';

        $thumbFull = Storage::disk($disk)->path($thumbPath);
        imagejpeg($thumb, $thumbFull, 75);
        imagedestroy($thumb);

        return $thumbPath;
    }

    public function store(UploadedFile $file, string $directory = 'places', string $disk = 'public'): array
    {
        $mime = $this->detectMimeType($file);
        $type = $this->getTypeFromMime($mime);
        $ext = $this->getExtensionFromMime($mime);
        $hash = $this->hashFile($file);
        $filename = $this->generateUniqueFilename($ext);

        $path = $file->storeAs($directory, $filename, $disk);

        $thumbPath = null;
        if ($type === 'image') {
            $this->compressImage($path, $disk);
            $thumbPath = $this->generateThumbnail($path, $disk);
        }

        return [
            'path'      => $path,
            'thumb'     => $thumbPath,
            'mime'      => $mime,
            'type'      => $type,
            'hash'      => $hash,
            'extension' => $ext,
            'size'      => $file->getSize(),
        ];
    }
}

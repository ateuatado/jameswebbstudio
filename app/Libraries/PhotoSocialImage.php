<?php

namespace App\Libraries;

class PhotoSocialImage
{
    public const WIDTH = 1200;
    public const HEIGHT = 630;

    public function generate(int $imageId, string $sourcePath): ?string
    {
        if (! function_exists('imagecreatetruecolor') || ! is_file($sourcePath)) return null;
        $info = @getimagesize($sourcePath);
        if (! $info || empty($info[0]) || empty($info[1])) return null;
        $source = match ($info['mime'] ?? '') {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };
        if (! $source) return null;

        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        $background = imagecolorallocate($canvas, 232, 229, 224);
        imagefill($canvas, 0, 0, $background);
        $scale = min(self::WIDTH / $info[0], self::HEIGHT / $info[1]);
        $width = (int) round($info[0] * $scale);
        $height = (int) round($info[1] * $scale);
        $x = (self::WIDTH - $width) / 2;
        $y = (self::HEIGHT - $height) / 2;
        imagecopyresampled($canvas, $source, $x, $y, 0, 0, $width, $height, $info[0], $info[1]);

        $directory = FCPATH . 'uploads/photo-works/social';
        if (! is_dir($directory)) mkdir($directory, 0755, true);
        $filename = $imageId . '.jpg';
        $target = $directory . DIRECTORY_SEPARATOR . $filename;
        $ok = imagejpeg($canvas, $target, 88);
        imagedestroy($source);
        imagedestroy($canvas);
        return $ok ? 'uploads/photo-works/social/' . $filename : null;
    }
}

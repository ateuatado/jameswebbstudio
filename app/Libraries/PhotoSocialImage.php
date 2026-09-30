<?php

namespace App\Libraries;

class PhotoSocialImage
{
    public const WIDTH = 1200;
    public const HEIGHT = 630;
    public const VERSION = 2;

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
        // Preenche todo o quadro para que a fotografia apareça maior nas
        // prévias de compartilhamento. O recorte é centralizado e mantém a
        // proporção recomendada pelo Open Graph (1200x630).
        $sourceRatio = $info[0] / $info[1];
        $targetRatio = self::WIDTH / self::HEIGHT;
        if ($sourceRatio > $targetRatio) {
            $cropHeight = $info[1];
            $cropWidth = (int) round($info[1] * $targetRatio);
            $sourceX = (int) round(($info[0] - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $info[0];
            $cropHeight = (int) round($info[0] / $targetRatio);
            $sourceX = 0;
            $sourceY = (int) round(($info[1] - $cropHeight) / 2);
        }
        imagecopyresampled($canvas, $source, 0, 0, $sourceX, $sourceY, self::WIDTH, self::HEIGHT, $cropWidth, $cropHeight);

        $directory = FCPATH . 'uploads/photo-works/social';
        if (! is_dir($directory)) mkdir($directory, 0755, true);
        $filename = $imageId . '-v' . self::VERSION . '.jpg';
        $target = $directory . DIRECTORY_SEPARATOR . $filename;
        $ok = imagejpeg($canvas, $target, 88);
        imagedestroy($source);
        imagedestroy($canvas);
        return $ok ? 'uploads/photo-works/social/' . $filename : null;
    }
}

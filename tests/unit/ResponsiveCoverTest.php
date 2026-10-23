<?php

namespace Tests\Unit;

use App\Libraries\ResponsiveCover;
use CodeIgniter\Test\CIUnitTestCase;

class ResponsiveCoverTest extends CIUnitTestCase
{
    public function testGeneratesReusableVariantsAndPreservesOriginal(): void
    {
        $path = 'uploads/heroes/test-cover-' . bin2hex(random_bytes(6)) . '.png';
        $source = FCPATH . $path;
        $image = imagecreatetruecolor(960, 1280);
        imagepng($image, $source);
        imagedestroy($image);
        $hash = hash_file('sha256', $source);
        $result = [];
        try {
            $service = new ResponsiveCover();
            $this->assertSame([], $service->describe('../.env'));
            $this->assertSame([], $service->describe($path)['variants']);
            $result = $service->generate($path);
            $this->assertSame([480, 800], array_keys($result['variants']));
            foreach ($result['variants'] as $width => $variant) {
                $size = getimagesize(FCPATH . $variant);
                $this->assertSame($width, $size[0]);
                $this->assertSame(IMAGETYPE_WEBP, $size[2]);
            }
            $this->assertSame($result['variants'], $service->generate($path)['variants']);
            $this->assertSame($hash, hash_file('sha256', $source));
        } finally {
            foreach ($result['variants'] ?? [] as $variant) unlink(FCPATH . $variant);
            unlink($source);
        }
    }
}

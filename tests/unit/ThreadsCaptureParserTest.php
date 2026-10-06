<?php

namespace Tests\Unit;

use App\Libraries\ThreadsCaptureParser;
use PHPUnit\Framework\TestCase;

class ThreadsCaptureParserTest extends TestCase
{
    public function testParsesMarkdownThreadsCapture(): void
    {
        $result = (new ThreadsCaptureParser())->parse("[**maria**](https://www.threads.com/@maria)\n[5h](https://www.threads.com/@maria/post/abc)\n\nEstou recomeçando e amo meu estilo #goth #Recomeço\nLike");

        $this->assertSame('maria', $result['username']);
        $this->assertSame('5h', $result['published_relative']);
        $this->assertSame('https://www.threads.com/@maria/post/abc', $result['post_url']);
        $this->assertSame(['goth', 'recomeço'], $result['hashtags']);
        $this->assertSame('afinidade estética', $result['context_category']);
        $this->assertStringContainsString('Estou recomeçando', $result['original_text']);
        $this->assertStringNotContainsString('Like', $result['original_text']);
    }

    public function testParsesPlainClipboardCapture(): void
    {
        $result = (new ThreadsCaptureParser())->parse("@ana\nagora\nSou designer e estou criando uma nova fase #design");

        $this->assertSame('ana', $result['username']);
        $this->assertSame('agora', $result['published_relative']);
        $this->assertSame('profissão', $result['context_category']);
        $this->assertSame('high', $result['priority']);
        $this->assertSame(['design'], $result['hashtags']);
    }
}

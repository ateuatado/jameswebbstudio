<?php

namespace App\Libraries\ServiceContent;

interface Provider
{
    /** Returns content, input_tokens, output_tokens, model; must not publish. */
    public function generate(string $topic, string $family, string $facts): array;
}

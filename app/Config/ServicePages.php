<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ServicePages extends BaseConfig
{
    public bool $enabled = false;
    public bool $generationEnabled = false;
    public string $apiKey = '';
    public string $model = 'deepseek-flash';
    public int $maxBatch = 25;
    public int $maxOutputTokens = 3500;
    public int $maxInputTokens = 16000;
    public int $maxAttempts = 2;
    public int $dailyTokenLimit = 100000;
    // Conservative peak/cache-miss USD rates checked on 2026-10-23.
    public string $pricingModel = 'deepseek-flash';
    public float $inputUsdPerMillion = 0.30;
    public float $outputUsdPerMillion = 1.20;
    public float $dailyUsdLimit = 0.25;
}

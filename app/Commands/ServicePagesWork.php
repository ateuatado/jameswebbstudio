<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\ServiceContent\{Repository, Worker, DeepSeekProvider};

class ServicePagesWork extends BaseCommand
{
    protected $group = 'Content';
    protected $name = 'service-pages:work';
    protected $description = 'Process queued service page drafts, never publish them.';
    protected $usage = 'service-pages:work [maximum-jobs]';

    public function run(array $params)
    {
        $repo = new Repository();
        $config = config('ServicePages');
        if (! $repo->ready() || ! $config->generationEnabled || $config->apiKey === '') {
            CLI::error('Instale o módulo e configure a geração/chave no ambiente.');
            return EXIT_ERROR;
        }
        $worker = new Worker($repo, new DeepSeekProvider());
        $max = max(1, min(25, (int) ($params[0] ?? 1)));
        $count = 0;
        while ($count < $max && $worker->once()) $count++;
        CLI::write($count . ' itens processados. Confira resultados no painel; nada foi publicado automaticamente.');
        return EXIT_SUCCESS;
    }
}

<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ServicePagesStatus extends BaseCommand
{
    protected $group = 'Content';
    protected $name = 'service-pages:status';
    protected $description = 'Check service-page setup without displaying secrets or making API calls.';

    public function run(array $params)
    {
        $config = config('ServicePages');
        CLI::write('Ambiente: ' . ENVIRONMENT);
        CLI::write('PHP: ' . PHP_VERSION);
        CLI::write('Arquivo .env legível: ' . (is_readable(ROOTPATH . '.env') ? 'sim' : 'não'));
        CLI::write('Páginas públicas habilitadas: ' . ($config->enabled ? 'sim' : 'não'));
        CLI::write('Geração habilitada: ' . ($config->generationEnabled ? 'sim' : 'não'));
        CLI::write('Chave carregada: ' . (trim($config->apiKey) !== '' ? 'sim (valor omitido)' : 'não'));
        CLI::write('Modelo: ' . $config->model);
        try {
            $db = db_connect();
            $missing = [];
            foreach (['contexts', 'families', 'pages', 'revisions', 'batches', 'jobs', 'audit', 'redirects', 'budgets', 'context_drafts'] as $suffix) {
                if (! $db->tableExists('service_content_' . $suffix)) $missing[] = $suffix;
            }
            CLI::write('Banco acessível: sim');
            CLI::write('Tabelas: ' . ($missing ? 'faltam ' . implode(', ', $missing) : 'completas'));
        } catch (\Throwable $error) {
            CLI::error('Banco inacessível. Confira as configurações do ambiente; detalhes omitidos para proteger credenciais.');
            return EXIT_ERROR;
        }
        return EXIT_SUCCESS;
    }
}

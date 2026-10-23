<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\ServiceContent\Repository;

class ServicePagesInstall extends BaseCommand
{
    protected $group = 'Content';
    protected $name = 'service-pages:install';
    protected $description = 'Install only the additive service-page migration, without running older migrations.';

    public function run(array $params)
    {
        $repo = new Repository();
        if ($repo->ready()) { CLI::write('Módulo já instalado; nenhuma alteração realizada.'); return EXIT_SUCCESS; }
        $runner = service('migrations');
        foreach ($runner->getHistory(config('Database')->defaultGroup) as $history) {
            if ($history->version === '2026-10-23-130000' && $history->namespace === 'App') {
                CLI::error('Histórico existe sem tabelas. Interrompido para evitar reversão automática.'); return EXIT_ERROR;
            }
        }
        $runner->force(APPPATH . 'Database/Migrations/2026-10-23-130000_CreateServiceContent.php', 'App', config('Database')->defaultGroup);
        CLI::write('Tabelas editoriais instaladas. Cadastre contexto e famílias no painel.');
        return EXIT_SUCCESS;
    }
}

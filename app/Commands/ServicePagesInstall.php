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
        $runner = service('migrations');
        foreach (['2026-10-23-130000_CreateServiceContent.php' => 'service_content_pages', '2026-10-23-140000_CreateServiceContextDrafts.php' => 'service_content_context_drafts'] as $file => $table) {
            $version = substr($file, 0, 17);
            $applied = false;
            foreach ($runner->getHistory(config('Database')->defaultGroup) as $history) {
                if ($history->version === $version && $history->namespace === 'App') $applied = true;
            }
            if ($applied && ! $repo->db->tableExists($table)) { CLI::error('Histórico sem tabela correspondente. Interrompido.'); return EXIT_ERROR; }
            if (! $applied && ! $repo->db->tableExists($table)) $runner->force(APPPATH . 'Database/Migrations/' . $file, 'App', config('Database')->defaultGroup);
        }
        CLI::write('Tabelas editoriais atualizadas. O contexto pode ser proposto pela IA no painel.');
        return EXIT_SUCCESS;
    }
}

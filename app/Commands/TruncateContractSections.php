<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TruncateContractSections extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'contract:reset';
    protected $description = 'Limpa e repopula as cláusulas do contrato.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $db->table('contract_sections')->truncate();
        CLI::write('Tabela contract_sections limpa.', 'green');

        $seeder = \Config\Database::seeder();
        $seeder->call('ContractSectionSeeder');
        CLI::write('Cláusulas repopuladas com sucesso!', 'green');
    }
}

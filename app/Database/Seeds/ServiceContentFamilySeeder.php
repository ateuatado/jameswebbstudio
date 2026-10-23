<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Libraries\ServiceContent\{Repository, Content};

class ServiceContentFamilySeeder extends Seeder
{
    public function run(): void
    {
        $repo = new Repository();
        if (! $repo->ready()) throw new \RuntimeException('Instale o módulo antes de cadastrar as famílias.');
        foreach ([
            'Ensaios fotográficos' => 'Conheça as modalidades de ensaios e converse sobre o seu projeto.',
            'Fotografia de produtos' => 'Fotografia still e apresentação de produtos para diferentes aplicações.',
            'Fotografia de imóveis' => 'Fotografia de imóveis, espaços e ambientes.',
            'Fotografia de moda' => 'Fotografia para projetos de moda e apresentação de coleções.',
        ] as $name => $description) {
            if (! $repo->table('families')->where('slug', Content::slug($name))->countAllResults()) $repo->createFamily($name, $description, 0);
        }
        echo "Famílias iniciais cadastradas. Nenhuma página foi publicada.\n";
    }
}

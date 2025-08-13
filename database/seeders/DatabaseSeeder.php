<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(MunicipioSeeder::class);
        $this->call(PromotorSeeder::class);
        $this->call(GrupoPromotoriaSeeder::class);
        $this->call(PromotoriaSeeder::class);
    }
}

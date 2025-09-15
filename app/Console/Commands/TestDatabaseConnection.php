<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseConnection extends Command
{
    protected $signature = 'db:test {connection?}';
    protected $description = 'Test database connection';

    public function handle()
    {
        $connection = $this->argument('connection') ?: config('database.default');
        
        try {
            DB::connection($connection)->getPdo();
            $this->info("Conexão '$connection' está funcionando!");
            
            // Detectar o driver e usar a função correta
            $driver = DB::connection($connection)->getDriverName();
            
            if ($driver === 'pgsql') {
                // PostgreSQL
                $result = DB::connection($connection)->select('SELECT current_database() as db_name');
                $this->info("Banco PostgreSQL conectado: " . $result[0]->db_name);
            } elseif ($driver === 'mysql') {
                // MySQL
                $result = DB::connection($connection)->select('SELECT DATABASE() as db_name');
                $this->info("Banco MySQL conectado: " . $result[0]->db_name);
            } else {
                $this->info("Driver: $driver");
            }
            
        } catch (\Exception $e) {
            $this->error(" Erro na conexão '$connection': " . $e->getMessage());
        }
    }
}
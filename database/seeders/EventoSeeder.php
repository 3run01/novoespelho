<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\Periodo;
use App\Models\EventoPromotor;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sempre busca o último período cadastrado, independente do status
        $periodo = Periodo::latest('created_at')->first();
        
        if (!$periodo) {
            $this->command->error('Nenhum período encontrado. Execute o PeriodoSeeder primeiro.');
            return;
        }

        $this->command->info("Usando período: {$periodo->nome} (ID: {$periodo->id})");

        $promotoriaCivel = Promotoria::where('nome', '1ª PJ Cível')->first();
        if (!$promotoriaCivel) {
            $this->command->warn('Promotoria "1ª PJ Cível" não encontrada. Execute o PromotoriaSeeder primeiro.');
            return;
        }

        $magno = Promotor::where('nome', 'Dr. Magno Fernando Carbonaro Souza')->first();
        if (!$magno) {
            $this->command->warn('Dr. Magno não encontrado. Execute o PromotorSeeder primeiro.');
            return;
        }

        $eventoCivel = Evento::create([
            'titulo' => null,
            'is_urgente' => false,
            'promotoria_id' => $promotoriaCivel->id,
            'periodo_id' => $periodo->id,
            'periodo_inicio' => '2025-07-01',
            'periodo_fim' => '2025-07-31',
        ]);

        EventoPromotor::create([
            'evento_id' => $eventoCivel->id,
            'promotor_id' => $magno->id,
            'tipo' => 'respondendo',
            'data_inicio_designacao' => '2025-07-01',
            'data_fim_designacao' => '2025-07-31',
            'ordem' => 1,
            'observacoes' => 'Designação para evento QUE da 1ª PJ Cível',
        ]);

        $this->command->info('Evento criado com sucesso para a 1ª PJ Cível com Dr. Magno.');

        $promotoriaFamilia = Promotoria::where('nome', '1ª PJ da Família')->first();
        if (!$promotoriaFamilia) {
            $this->command->warn('Promotoria "1ª PJ da Família" não encontrada. Execute o PromotoriaSeeder primeiro.');
            return;
        }

        $eventoFamilia = Evento::create([
            'titulo' => 'XV Congresso Brasileiro de Direito das Famílias e Sucessões - Belo Horizonte/MG',
            'is_urgente' => false,
            'promotoria_id' => $promotoriaFamilia->id,
            'periodo_id' => $periodo->id,
            'periodo_inicio' => '2025-10-28',
            'periodo_fim' => '2025-11-01',
        ]);

        // Não há promotor designado específico para este evento

        $this->command->info('Evento "XV Congresso Brasileiro de Direito das Famílias e Sucessões" criado para a 1ª PJ da Família.');

        // =========================
        // 3ª PJ da FAMÍLIA - Órfãos, Sucessões, Incapazes
        // =========================

        $promotoriaFamilia3 = Promotoria::where('nome', '3ª PJ da Família')->first();
        if (!$promotoriaFamilia3) {
            $this->command->warn('Promotoria "3ª PJ da Família" não encontrada. Execute o PromotoriaSeeder primeiro.');
            return;
        }

        $flavio = Promotor::where('nome', 'Dr. Flávio Costa Cavalcante')->first();
        $eli = Promotor::where('nome', 'Dr. Eli Pinheiro de Oliveira')->first();
        $alberto = Promotor::where('nome', 'Dr. Alberto Eli Pinheiro de Oliveira')->first();

        if (!$flavio || !$eli || !$alberto) {
            $this->command->warn('Um ou mais promotores (Flávio, Eli, Alberto) não encontrados. Execute o PromotorSeeder primeiro.');
            return;
        }

        // Evento de férias do Dr. Flávio
        $eventoFeriasFlavio = Evento::create([
            'titulo' => 'Férias do Dr. Flávio Costa Cavalcante',
            'is_urgente' => false,
            'promotoria_id' => $promotoriaFamilia3->id,
            'periodo_id' => $periodo->id,
            'periodo_inicio' => '2025-08-20',
            'periodo_fim' => '2025-09-02',
        ]);

        // Dr. Eli responde
        EventoPromotor::create([
            'evento_id' => $eventoFeriasFlavio->id,
            'promotor_id' => $eli->id,
            'tipo' => 'respondendo',
            'data_inicio_designacao' => '2025-08-20',
            'data_fim_designacao' => '2025-09-02',
            'ordem' => 1,
            'observacoes' => 'Designado para responder durante as férias do titular.',
        ]);

        // Dr. Alberto Eli auxilia
        EventoPromotor::create([
            'evento_id' => $eventoFeriasFlavio->id,
            'promotor_id' => $alberto->id,
            'tipo' => 'auxiliando',
            'data_inicio_designacao' => '2025-08-20',
            'data_fim_designacao' => '2025-09-02',
            'ordem' => 2,
            'observacoes' => 'Auxílio durante as férias do titular.',
        ]);

        $this->command->info('Evento de férias criado para a 3ª PJ da Família com designações de Eli e Alberto.');

        // Evento do XV Congresso Brasileiro de Direito das Famílias e Sucessões para a 3ª PJ da Família
        $eventoCongressoFamilia3 = Evento::create([
            'titulo' => 'XV Congresso Brasileiro de Direito das Famílias e Sucessões - Belo Horizonte/MG',
            'is_urgente' => false,
            'promotoria_id' => $promotoriaFamilia3->id,
            'periodo_id' => $periodo->id,
            'periodo_inicio' => '2025-10-28',
            'periodo_fim' => '2025-11-01',
        ]);

        // Não há promotor designado específico para este evento

        $this->command->info('Evento "XV Congresso Brasileiro de Direito das Famílias e Sucessões" criado para a 3ª PJ da Família.');

        // =========================
        // 1ª PJ CRIMINAL - Atribuições na 1ª Vara Criminal e Defesa da Ordem Tributária
        // =========================

        $promotoriaCriminal = Promotoria::where('nome', '1ª PJ Criminal')->first();
        if (!$promotoriaCriminal) {
            $this->command->warn('Promotoria "1ª PJ Criminal" não encontrada. Execute o PromotoriaSeeder primeiro.');
            return;
        }

        $jander = Promotor::where('nome', 'Dr. Jander Vilhena Nascimento')->first();
        $furlan = Promotor::where('nome', 'Dr. João Paulo de Oliveira Furlan')->first();
        $julio = Promotor::where('nome', 'Dr. Julio Luiz de Medeiros Alves Lima Kuhlmann')->first();
        $magno = Promotor::where('nome', 'Dr. Magno Fernando Carbonaro Souza')->first();

        if (!$jander || !$furlan || !$julio || !$magno) {
            $this->command->warn('Um ou mais promotores (Jander, Furlan, Julio, Magno) não encontrados. Execute o PromotorSeeder primeiro.');
            return;
        }

        // Evento CAVINP do Dr. Jander
        $eventoCavinp = Evento::create([
            'titulo' => 'CAVINP',
            'is_urgente' => false,
            'promotoria_id' => $promotoriaCriminal->id,
            'periodo_id' => $periodo->id,
            'periodo_inicio' => '2025-07-23',
            'periodo_fim' => '2025-08-04',
        ]);

        $this->command->info('Evento CAVINP criado para a 1ª PJ Criminal.');

        // Evento de Licença Recesso do Dr. Jander
        $eventoLicencaRecesso = Evento::create([
            'titulo' => 'Licença Recesso - Dr. Jander Vilhena Nascimento',
            'is_urgente' => false,
            'promotoria_id' => $promotoriaCriminal->id,
            'periodo_id' => $periodo->id,
            'periodo_inicio' => '2025-08-14',
            'periodo_fim' => '2025-08-31',
        ]);

        // Dr. Furlan responde de 14 a 17/8/25
        EventoPromotor::create([
            'evento_id' => $eventoLicencaRecesso->id,
            'promotor_id' => $furlan->id,
            'tipo' => 'respondendo',
            'data_inicio_designacao' => '2025-08-14',
            'data_fim_designacao' => '2025-08-17',
            'ordem' => 1,
            'observacoes' => 'Designado para responder durante licença recesso do titular.',
        ]);

        // Dr. Furlan auxilia em 22/8/25
        EventoPromotor::create([
            'evento_id' => $eventoLicencaRecesso->id,
            'promotor_id' => $furlan->id,
            'tipo' => 'auxiliando',
            'data_inicio_designacao' => '2025-08-22',
            'data_fim_designacao' => '2025-08-22',
            'ordem' => 2,
            'observacoes' => 'Auxílio durante licença recesso do titular.',
        ]);

        // Dr. Julio responde de 18 a 31/8/25
        EventoPromotor::create([
            'evento_id' => $eventoLicencaRecesso->id,
            'promotor_id' => $julio->id,
            'tipo' => 'respondendo',
            'data_inicio_designacao' => '2025-08-18',
            'data_fim_designacao' => '2025-08-31',
            'ordem' => 3,
            'observacoes' => 'Designado para responder durante licença recesso do titular.',
        ]);

        // Dr. Magno auxilia em 18 e 19/8/25
        EventoPromotor::create([
            'evento_id' => $eventoLicencaRecesso->id,
            'promotor_id' => $magno->id,
            'tipo' => 'auxiliando',
            'data_inicio_designacao' => '2025-08-18',
            'data_fim_designacao' => '2025-08-19',
            'ordem' => 4,
            'observacoes' => 'Auxílio durante licença recesso do titular.',
        ]);

        $this->command->info('Evento de Licença Recesso criado para a 1ª PJ Criminal com designações de Furlan, Julio e Magno.');
    }
}

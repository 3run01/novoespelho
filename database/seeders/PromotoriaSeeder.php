<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\GrupoPromotoria;

class PromotoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar promotores e grupos existentes
        $promotores = Promotor::all();
        $grupos = GrupoPromotoria::all();
        
        if ($promotores->isEmpty() || $grupos->isEmpty()) {
            return;
        }

        $promotorias = [];

        // MACAPÁ - Todas as promotorias no grupo "Promotorias"
        $grupoPromotorias = $grupos->where('nome', 'Promotorias')->first();
        if ($grupoPromotorias) {
            // Buscar promotores
            $magno = $promotores->where('nome', 'Dr. Magno Fernando Carbonaro Souza')->first();
            $eliana = $promotores->where('nome', 'Dra. Eliana Mena Cavalcante')->first();
            $marcelo = $promotores->where('nome', 'Dr. Marcelo José de Guimarães e Moraes')->first();
            $eli = $promotores->where('nome', 'Dr. Eli Pinheiro de Oliveira')->first();
            $flavio = $promotores->where('nome', 'Dr. Flávio Costa Cavalcante')->first();
            $alberto = $promotores->where('nome', 'Dr. Alberto Eli Pinheiro de Oliveira')->first();
            $jander = $promotores->where('nome', 'Dr. Jander Vilhena Nascimento')->first();
            $ubirajara = $promotores->where('nome', 'Dr. Ubirajara Valente Éphina')->first();
            $tiago = $promotores->where('nome', 'Dr. Tiago Silva Diniz')->first();
            $ricardo = $promotores->where('nome', 'Dr. Ricardo Crispino Gomes')->first();
            $vinicius = $promotores->where('nome', 'Dr. Vinícius Mendonça Carvalho')->first();
            $christie = $promotores->where('nome', 'Dra. Christie Damasceno Girão')->first();
            $alexandre = $promotores->where('nome', 'Dr. Alexandre Flávio M. Monteiro')->first();
            $joao = $promotores->where('nome', 'Dr. João Paulo de Oliveira Furlan')->first();
            $helio = $promotores->where('nome', 'Dr. Hélio Paulo Santos Furtado')->first();
            $rodrigoCesar = $promotores->where('nome', 'Dr. Rodrigo César Viana Assis')->first();
            $rodrigoCelestino = $promotores->where('nome', 'Dr. Rodrigo Celestino Pinheiro Menezes')->first();
            $fabiano = $promotores->where('nome', 'Dr. Fabiano da Silveira Castanho')->first();
            $eduardo = $promotores->where('nome', 'Dr. Eduardo Kelson Fernandes de Pinho')->first();
            $lindalva = $promotores->where('nome', 'Dra. Lindalva Gomes Jardina')->first();
            $samile = $promotores->where('nome', 'Dra. Samile Simões A. de Brito')->first();
            $neuza = $promotores->where('nome', 'Dra. Neuza Rodrigues Barbosa')->first();
            $paulo = $promotores->where('nome', 'Dr. Paulo Celso Ramos dos Santos')->first();
            $iaci = $promotores->where('nome', 'Dr. Iaci Pelaes dos Reis')->first();
            $wueber = $promotores->where('nome', 'Dr. Wueber Duarte Penafort')->first();
            $fabia = $promotores->where('nome', 'Dra. Fábia Nilci Santana de Souza')->first();
            $alessandra = $promotores->where('nome', 'Dra. Alessandra Moro de C. Valente')->first();
            $saullo = $promotores->where('nome', 'Dr. Saullo Patrício Andrade')->first();
            $clarisse = $promotores->where('nome', 'Dra. Clarisse Lindanor Alcantara Lax')->first();
            $luiz = $promotores->where('nome', 'Dr. Luiz Marcos da Silva')->first();
            $afonso = $promotores->where('nome', 'Dr. Afonso Henrique O. Pereira')->first();
            $marceloMoreira = $promotores->where('nome', 'Dr. Marcelo Moreira dos Santos')->first();
            $andre = $promotores->where('nome', 'Dr. André Luiz Dias Araújo')->first();
            $andrea = $promotores->where('nome', 'Dra. Andréa Guedes de M. Amanajás')->first();
            $jose = $promotores->where('nome', 'Dr. José Cantuária Barreto')->first();
            $anderson = $promotores->where('nome', 'Dr. Anderson Batista de Souza')->first();
            
            // Cíveis
            $promotorias[] = ['nome' => '1ª PJ Cível', 'promotor_id' => null, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => '1ª,2ª,3ª Cíveis e de Fazenda Pública', 'vacancia_data_inicio' => '2025-07-02'];
            $promotorias[] = ['nome' => '2ª PJ Cível', 'promotor_id' => $eliana?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => '4ª,5ª,6ª Cíveis e de Fazenda Pública'];
            
            // Família
            $promotorias[] = ['nome' => '1ª PJ da Família', 'promotor_id' => $marcelo?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Órfãos Sucessões, Incapazes'];
            $promotorias[] = ['nome' => '2ª PJ da Família', 'promotor_id' => $eli?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Órfãos Sucessões, Incapazes'];
            $promotorias[] = ['nome' => '3ª PJ da Família', 'promotor_id' => $flavio?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Órfãos, Sucessões, Incapazes'];
            $promotorias[] = ['nome' => '4ª PJ da Família', 'promotor_id' => $alberto?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Órfãos Sucessões, Incapazes'];
            
            // Criminais
            $promotorias[] = ['nome' => '1ª PJ Criminal', 'promotor_id' => $jander?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 1ª Vara Criminal e Defesa da Ordem Tributária'];
            $promotorias[] = ['nome' => '2ª PJ Criminal', 'promotor_id' => $ubirajara?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 2ª Vara Criminal'];
            $promotorias[] = ['nome' => '3ª PJ Criminal', 'promotor_id' => $tiago?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 3ª Vara Criminal e Auditoria Militar'];
            $promotorias[] = ['nome' => '4ª PJ Criminal', 'promotor_id' => null, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições no segundo gabinete da Central de Violência Doméstica da Comarca de Macapá'];
            $promotorias[] = ['nome' => '5ª PJ Criminal', 'promotor_id' => $ricardo?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 5ª Vara Criminal, trânsito'];
            $promotorias[] = ['nome' => '6ª PJ Criminal', 'promotor_id' => $vinicius?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 5ª Vara Criminal, trânsito'];
            $promotorias[] = ['nome' => '7ª PJ Criminal', 'promotor_id' => $christie?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 2ª Vara Criminal'];
            $promotorias[] = ['nome' => '8ª PJ Criminal', 'promotor_id' => $alexandre?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 3ª Vara Criminal e Auditoria Militar'];
            $promotorias[] = ['nome' => '9ª PJ Criminal', 'promotor_id' => null, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições no segundo gabinete da Central de Violência Doméstica da Comarca de Macapá'];
            $promotorias[] = ['nome' => '10ª PJ Criminal', 'promotor_id' => $joao?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Atribuições na 1ª Vara Criminal e Defesa da Ordem Tributária'];
            
            // Tribunal do Júri
            $promotorias[] = ['nome' => '1ª PJ Tribunal do Júri', 'promotor_id' => $helio?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Tribunal do Júri', 'promotor_id' => null, 'grupo_promotoria_id' => $grupoPromotorias->id, 'vacancia_data_inicio' => '2025-05-23'];
            
            // Execução Penal e Medidas Alternativas
            $promotorias[] = ['nome' => '1ª PJ Execução Penal', 'promotor_id' => $rodrigoCesar?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Execução Penal', 'promotor_id' => $rodrigoCelestino?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '3ª PJ Execução Penal', 'promotor_id' => $fabiano?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            
            // Infância e Juventude
            $promotorias[] = ['nome' => '1ª PJ Infância e Juventude', 'promotor_id' => $eduardo?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Infância e Juventude', 'promotor_id' => $lindalva?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '3ª PJ Infância e Juventude', 'promotor_id' => $samile?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '4ª PJ Infância e Juventude', 'promotor_id' => $neuza?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            
            // Especializadas
            $promotorias[] = ['nome' => 'Defesa de Direitos Constitucionais', 'promotor_id' => $paulo?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => 'Defesa da Educação', 'promotor_id' => $iaci?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '1ª PJ Defesa da Saúde Pública', 'promotor_id' => $wueber?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Defesa da Saúde Pública', 'promotor_id' => $fabia?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '1ª PJ Defesa da Mulher', 'promotor_id' => $alessandra?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Defesa da Mulher', 'promotor_id' => $saullo?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => 'Central de Violência Doméstica', 'promotor_id' => $clarisse?->id, 'grupo_promotoria_id' => $grupoPromotorias->id, 'competencia' => 'Dra. Klisiomar Lopes Dias também atua'];
            $promotorias[] = ['nome' => 'Defesa do Consumidor', 'promotor_id' => $luiz?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '1ª PJ Meio Ambiente e Conflitos Agrários', 'promotor_id' => $afonso?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Meio Ambiente e Conflitos Agrários', 'promotor_id' => $marceloMoreira?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => 'Urbanismo e Mobilidade Urbana', 'promotor_id' => $andre?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '1ª PJ Defesa do Patrimônio Público e Fundações', 'promotor_id' => $andrea?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '2ª PJ Defesa do Patrimônio Público e Fundações', 'promotor_id' => $jose?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
            $promotorias[] = ['nome' => '3ª PJ Defesa do Patrimônio Público e Fundações', 'promotor_id' => $anderson?->id, 'grupo_promotoria_id' => $grupoPromotorias->id];
        }

        // SANTANA - Cíveis e Família
        $grupoCiveisFamily = $grupos->where('nome', 'Cíveis e Família')->first();
        if ($grupoCiveisFamily) {
            $nilson = $promotores->where('nome', 'Dr. Nilson Alves Costa')->first();
            $silvia = $promotores->where('nome', 'Dra. Silvia de Souza Canela')->first();
            $milton = $promotores->where('nome', 'Dr. Milton Ferreira do Amaral Júnior')->first();
            
            $promotorias[] = ['nome' => '1ª PJ Cível e Família', 'promotor_id' => $nilson?->id, 'grupo_promotoria_id' => $grupoCiveisFamily->id];
            $promotorias[] = ['nome' => '2ª PJ Cível e Família', 'promotor_id' => $silvia?->id, 'grupo_promotoria_id' => $grupoCiveisFamily->id];
            $promotorias[] = ['nome' => '3ª PJ Cível e Família', 'promotor_id' => $milton?->id, 'grupo_promotoria_id' => $grupoCiveisFamily->id];
        }

        // SANTANA - Criminais e Júri
        $grupoCriminaisJuri = $grupos->where('nome', 'Criminais e Júri')->first();
        if ($grupoCriminaisJuri) {
            $horacio = $promotores->where('nome', 'Dr. Horácio Luís Bezerra Coutinho')->first();
            $manoel = $promotores->where('nome', 'Dr. Manoel Edi de Aguiar Júnior')->first();
            $david = $promotores->where('nome', 'Dr. David Zerbini de Faria Soares')->first();
            
            $promotorias[] = ['nome' => '1ª Criminal e Júri', 'promotor_id' => $horacio?->id, 'grupo_promotoria_id' => $grupoCriminaisJuri->id];
            $promotorias[] = ['nome' => '2ª Criminal e Júri', 'promotor_id' => $manoel?->id, 'grupo_promotoria_id' => $grupoCriminaisJuri->id];
            $promotorias[] = ['nome' => '3ª Criminal e Júri', 'promotor_id' => $david?->id, 'grupo_promotoria_id' => $grupoCriminaisJuri->id];
        }

        // SANTANA - Especializadas
        $grupoEspecializadas = $grupos->where('nome', 'Especializadas')->first();
        if ($grupoEspecializadas) {
            $fabiaRegina = $promotores->where('nome', 'Dra. Fábia Regina Rocha Martins')->first();
            $miguel = $promotores->where('nome', 'Dr. Miguel Angel Montiel Ferreira')->first();
            $elissandra = $promotores->where('nome', 'Dra. Elissandra Toscano B. N. Verardi')->first();
            $gisa = $promotores->where('nome', 'Dra. Gisa Veiga Chaves')->first();
            $socorro = $promotores->where('nome', 'Dra. Maria do Socorro Pelaes Braga')->first();
            
            $promotorias[] = ['nome' => '1ª PJ Infância e Juventude', 'promotor_id' => $fabiaRegina?->id, 'grupo_promotoria_id' => $grupoEspecializadas->id];
            $promotorias[] = ['nome' => '2ª PJ Infância e Juventude', 'promotor_id' => $miguel?->id, 'grupo_promotoria_id' => $grupoEspecializadas->id];
            $promotorias[] = ['nome' => 'Meio Ambiente e Urbanismo', 'promotor_id' => $elissandra?->id, 'grupo_promotoria_id' => $grupoEspecializadas->id];
            $promotorias[] = ['nome' => 'Cidadania e Saúde Pública', 'promotor_id' => $gisa?->id, 'grupo_promotoria_id' => $grupoEspecializadas->id];
            $promotorias[] = ['nome' => 'Patrimônio Público e Consumidor', 'promotor_id' => $socorro?->id, 'grupo_promotoria_id' => $grupoEspecializadas->id];
        }

        // ENTRÂNCIA INICIAL - 1º Núcleo
        $grupo1Nucleo = $grupos->where('nome', 'Entrância Inicial - 1º Núcleo')->first();
        if ($grupo1Nucleo) {
            $marcos = $promotores->where('nome', 'Dr. Marcos Rogério Tavares da Costa')->first();
            $arthur = $promotores->where('nome', 'Dr. Arthur Senra Jacob')->first();
            $marcela = $promotores->where('nome', 'Dra. Marcela Balduíno Carneiro')->first();
            $marcoValerio = $promotores->where('nome', 'Dr. Marco Valério Vale dos Santos')->first();
            
            // Laranjal do Jari
            $promotorias[] = ['nome' => '1ª Promotoria', 'promotor_id' => $marcos?->id, 'grupo_promotoria_id' => $grupo1Nucleo->id, 'competencia' => 'Laranjal do Jari'];
            $promotorias[] = ['nome' => '2ª Promotoria', 'promotor_id' => $arthur?->id, 'grupo_promotoria_id' => $grupo1Nucleo->id, 'competencia' => 'Laranjal do Jari'];
            
            // Vitória do Jari
            $promotorias[] = ['nome' => 'Promotoria de Vitória do Jari', 'promotor_id' => $marcela?->id, 'grupo_promotoria_id' => $grupo1Nucleo->id];
            
            // Mazagão
            $promotorias[] = ['nome' => 'Promotoria de Mazagão', 'promotor_id' => $marcoValerio?->id, 'grupo_promotoria_id' => $grupo1Nucleo->id];
        }

        // ENTRÂNCIA INICIAL - 2º Núcleo
        $grupo2Nucleo = $grupos->where('nome', 'Entrância Inicial - 2º Núcleo')->first();
        if ($grupo2Nucleo) {
            $matheus = $promotores->where('nome', 'Dr. Matheus Silva Mendes')->first();
            $leonardo = $promotores->where('nome', 'Dr. Leonardo Rocha Leite de Oliveira')->first();
            $welder = $promotores->where('nome', 'Dr. Welder Tiago dos Santos Feitosa')->first();
            
            // Oiapoque
            $promotorias[] = ['nome' => '1ª Promotoria', 'promotor_id' => $matheus?->id, 'grupo_promotoria_id' => $grupo2Nucleo->id, 'competencia' => 'Oiapoque'];
            $promotorias[] = ['nome' => '2ª Promotoria', 'promotor_id' => $leonardo?->id, 'grupo_promotoria_id' => $grupo2Nucleo->id, 'competencia' => 'Oiapoque'];
            
            // Calçoene
            $promotorias[] = ['nome' => 'Promotoria de Calçoene', 'promotor_id' => $welder?->id, 'grupo_promotoria_id' => $grupo2Nucleo->id];
            
            // Amapá (vaga)
            $promotorias[] = ['nome' => 'Promotoria de Amapá', 'promotor_id' => null, 'grupo_promotoria_id' => $grupo2Nucleo->id, 'vacancia_data_inicio' => '2025-06-13'];
        }

        // ENTRÂNCIA INICIAL - 3º Núcleo
        $grupo3Nucleo = $grupos->where('nome', 'Entrância Inicial - 3º Núcleo')->first();
        if ($grupo3Nucleo) {
            $igor = $promotores->where('nome', 'Dr. Igor Costa Coutinho')->first();
            $roberta = $promotores->where('nome', 'Dra. Roberta Araújo Jacob')->first();
            $carolina = $promotores->where('nome', 'Dra. Carolina Pereira de Oliveira')->first();
            
            // Tartarugalzinho
            $promotorias[] = ['nome' => 'Promotoria de Tartarugalzinho', 'promotor_id' => $igor?->id, 'grupo_promotoria_id' => $grupo3Nucleo->id];
            
            // Ferreira Gomes
            $promotorias[] = ['nome' => 'Promotoria de Ferreira Gomes', 'promotor_id' => $roberta?->id, 'grupo_promotoria_id' => $grupo3Nucleo->id];
            
            // Porto Grande (vaga)
            $promotorias[] = ['nome' => 'Promotoria de Porto Grande', 'promotor_id' => null, 'grupo_promotoria_id' => $grupo3Nucleo->id, 'vacancia_data_inicio' => '2025-06-13'];
            
            // Pedra Branca do Amapari
            $promotorias[] = ['nome' => 'Promotoria de Pedra Branca do Amapari', 'promotor_id' => $carolina?->id, 'grupo_promotoria_id' => $grupo3Nucleo->id];
        }

        // Criar todas as promotorias
        foreach ($promotorias as $promotoria) {
            Promotoria::create($promotoria);
        }
    }
}

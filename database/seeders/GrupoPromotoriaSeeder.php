<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;

class GrupoPromotoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar municípios existentes
        $municipios = Municipio::all();
        
        if ($municipios->isEmpty()) {
            return; // Não criar grupos se não houver municípios
        }

        $grupos = [];

        // MACAPÁ - Promotorias Especializadas
        $macapa = $municipios->where('nome', 'Macapá')->first();
        if ($macapa) {
            $grupos[] = ['nome' => '1ª PJ Cível', 'competencia' => '1ª, 2ª, 3ª Cíveis e de Fazenda Pública', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ Cível', 'competencia' => '4ª, 5ª, 6ª Cíveis e de Fazenda Pública', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ da Família', 'competencia' => 'Órfãos, Sucessões, Incapazes', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ da Família', 'competencia' => 'Órfãos, Sucessões, Incapazes', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '3ª PJ da Família', 'competencia' => 'Órfãos, Sucessões, Incapazes', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '4ª PJ da Família', 'competencia' => 'Órfãos, Sucessões, Incapazes', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ Criminal', 'competencia' => '1ª Vara Criminal e Defesa da Ordem Tributária', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ Criminal', 'competencia' => '2ª Vara Criminal', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '3ª PJ Criminal', 'competencia' => '3ª Vara Criminal e Auditoria Militar', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '5ª PJ Criminal', 'competencia' => '5ª Vara Criminal, trânsito', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '6ª PJ Criminal', 'competencia' => '5ª Vara Criminal, trânsito', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '7ª PJ Criminal', 'competencia' => '2ª Vara Criminal', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '8ª PJ Criminal', 'competencia' => '3ª Vara Criminal e Auditoria Militar', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '10ª PJ Criminal', 'competencia' => '1ª Vara Criminal e Defesa da Ordem Tributária', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ Tribunal do Júri', 'competencia' => 'Tribunal do Júri', 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ Execução Penal (VEP/VEPMA)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ Execução Penal (VEP/VEPMA)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '3ª PJ Execução Penal (Garantias)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ Infância e Juventude – Cível e Administrativa', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ Infância e Juventude – Políticas Públicas', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '3ª PJ Infância e Juventude – Atos Infracionais', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '4ª PJ Infância e Juventude – Atos Infracionais', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Defesa de Direitos Constitucionais', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Defesa da Educação', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Defesa da Saúde Pública – 1ª PJ', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Defesa da Saúde Pública – 2ª PJ', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ de Defesa da Mulher', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ de Defesa da Mulher', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Central de Violência Doméstica – Macapá', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Defesa do Consumidor (PRODECON)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ Meio Ambiente e Conflitos Agrários (PRODEMAC)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ Meio Ambiente e Conflitos Agrários (PRODEMAC)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => 'Urbanismo, Habitação, Saneamento, Mobilidade Urbana, Eventos', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '1ª PJ Defesa do Patrimônio Público e Fundações (GAECO)', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '2ª PJ Defesa do Patrimônio Público e Fundações', 'competencia' => null, 'municipios_id' => $macapa->id];
            $grupos[] = ['nome' => '3ª PJ Defesa do Patrimônio Público e Fundações (PRODEMAP)', 'competencia' => null, 'municipios_id' => $macapa->id];
        }

        // SANTANA - Promotorias Especializadas
        $santana = $municipios->where('nome', 'Santana')->first();
        if ($santana) {
            $grupos[] = ['nome' => '1ª Promotoria Cível e Famílias', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '2ª Promotoria Cível e Famílias', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '3ª Promotoria Cível e Famílias', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '1ª Criminal e Tribunal do Júri', 'competencia' => 'Tribunal do Júri', 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '2ª Criminal e Tribunal do Júri', 'competencia' => 'Tribunal do Júri', 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '3ª Criminal e Tribunal do Júri', 'competencia' => 'Tribunal do Júri', 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => 'Juizado Especial Criminal e Violência Doméstica', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '1ª PJ Infância e Juventude – Santana', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => '2ª PJ Infância e Juventude – Santana', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => 'Meio Ambiente e Urbanismo – Santana', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => 'Cidadania e Saúde Pública – Santana', 'competencia' => null, 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => 'Defesa do Patrimônio Público e Consumidor – Santana', 'competencia' => null, 'municipios_id' => $santana->id];
        }

        // LARANJAL DO JARI
        $laranjal = $municipios->where('nome', 'Laranjal do Jari')->first();
        if ($laranjal) {
            $grupos[] = ['nome' => '1ª Promotoria', 'competencia' => null, 'municipios_id' => $laranjal->id];
            $grupos[] = ['nome' => '2ª Promotoria', 'competencia' => null, 'municipios_id' => $laranjal->id];
        }

        // VITÓRIA DO JARI
        $vitoria = $municipios->where('nome', 'Vitória do Jari')->first();
        if ($vitoria) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $vitoria->id];
        }

        // MAZAGÃO
        $mazagao = $municipios->where('nome', 'Mazagão')->first();
        if ($mazagao) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $mazagao->id];
        }

        // OIAPOQUE
        $oiapoque = $municipios->where('nome', 'Oiapoque')->first();
        if ($oiapoque) {
            $grupos[] = ['nome' => '1ª Promotoria', 'competencia' => null, 'municipios_id' => $oiapoque->id];
            $grupos[] = ['nome' => '2ª Promotoria', 'competencia' => null, 'municipios_id' => $oiapoque->id];
        }

        // CALÇOENE
        $calcoene = $municipios->where('nome', 'Calçoene')->first();
        if ($calcoene) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $calcoene->id];
        }

        // AMAPÁ
        $amapa = $municipios->where('nome', 'Amapá')->first();
        if ($amapa) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $amapa->id];
        }

        // TARTARUGALZINHO
        $tartarugalzinho = $municipios->where('nome', 'Tartarugalzinho')->first();
        if ($tartarugalzinho) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $tartarugalzinho->id];
        }

        // FERREIRA GOMES
        $ferreira = $municipios->where('nome', 'Ferreira Gomes')->first();
        if ($ferreira) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $ferreira->id];
        }

        // PORTO GRANDE
        $portoGrande = $municipios->where('nome', 'Porto Grande')->first();
        if ($portoGrande) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $portoGrande->id];
        }

        // PEDRA BRANCA DO AMAPARI
        $pedraBranca = $municipios->where('nome', 'Pedra Branca do Amapari')->first();
        if ($pedraBranca) {
            $grupos[] = ['nome' => 'Promotoria', 'competencia' => null, 'municipios_id' => $pedraBranca->id];
        }

        // Criar todos os grupos
        foreach ($grupos as $grupo) {
            GrupoPromotoria::create($grupo);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotor;

class PromotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Promotores Titulares
        $promotoresTitulares = [
            // Macapá - Cíveis
            ['nome' => 'Dr. Magno Fernando Carbonaro Souza', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dra. Eliana Mena Cavalcante', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Macapá - Família
            ['nome' => 'Dr. Marcelo José de Guimarães e Moraes', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Eli Pinheiro de Oliveira', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Flávio Costa Cavalcante', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Alberto Eli Pinheiro de Oliveira', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Macapá - Criminais
            ['nome' => 'Dr. Jander Vilhena Nascimento', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Ubirajara Valente Éphina', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Tiago Silva Diniz', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Ricardo Crispino Gomes', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Vinícius Mendonça Carvalho', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Christie Damasceno Girão', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Alexandre Flávio M. Monteiro', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. João Paulo de Oliveira Furlan', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Macapá - Tribunal do Júri
            ['nome' => 'Dr. Hélio Paulo Santos Furtado', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Macapá - Execução Penal
            ['nome' => 'Dr. Rodrigo César Viana Assis', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Rodrigo Celestino Pinheiro Menezes', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Fabiano da Silveira Castanho', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Macapá - Especializadas - Infância e Juventude
            ['nome' => 'Dr. Eduardo Kelson Fernandes de Pinho', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Lindalva Gomes Jardina', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Samile Simões A. de Brito', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Neuza Rodrigues Barbosa', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Macapá - Outras Especializadas
            ['nome' => 'Dr. Paulo Celso Ramos dos Santos', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Iaci Pelaes dos Reis', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Wueber Duarte Penafort', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Fábia Nilci Santana de Souza', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Alessandra Moro de C. Valente', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Saullo Patrício Andrade', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Clarisse Lindanor Alcantara Lax', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Klisiomar Lopes Dias', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Luiz Marcos da Silva', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Afonso Henrique O. Pereira', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Marcelo Moreira dos Santos', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. André Luiz Dias Araújo', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Andréa Guedes de M. Amanajás', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. José Cantuária Barreto', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Anderson Batista de Souza', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Santana
            ['nome' => 'Dr. Nilson Alves Costa', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Silvia de Souza Canela', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Milton Ferreira do Amaral Júnior', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Horácio Luís Bezerra Coutinho', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Manoel Edi de Aguiar Júnior', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. David Zerbini de Faria Soares', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Fábia Regina Rocha Martins', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Miguel Angel Montiel Ferreira', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Elissandra Toscano B. N. Verardi', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Gisa Veiga Chaves', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Maria do Socorro Pelaes Braga', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Entrância Inicial - 1º Núcleo
            ['nome' => 'Dr. Marcos Rogério Tavares da Costa', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Arthur Senra Jacob', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Marcela Balduíno Carneiro', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Marco Valério Vale dos Santos', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Entrância Inicial - 2º Núcleo
            ['nome' => 'Dr. Matheus Silva Mendes', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Leonardo Rocha Leite de Oliveira', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dr. Welder Tiago dos Santos Feitosa', 'tipo' => 'titular', 'is_substituto' => false],
            
            // Entrância Inicial - 3º Núcleo
            ['nome' => 'Dr. Igor Costa Coutinho', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Roberta Araújo Jacob', 'tipo' => 'titular', 'is_substituto' => false],
            ['nome' => 'Dra. Carolina Pereira de Oliveira', 'tipo' => 'titular', 'is_substituto' => false],
        ];

        // Promotores Substitutos
        $promotoresSubstitutos = [
            ['nome' => 'Dr. Adriano de Medeiros Escorbaiolli Nonaka', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dra. Tatyana Cavalcante da Silva', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dr. Vitor Medeiros dos Reis', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dr. Daniel Luz da Silva', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dra. Ivana Rios Melo Coutinho', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dr. Julio Luiz de Medeiros Alves Lima Kuhlmann', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dra. Aline Cristina Lopes da Silva', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dra. Sophia de Moura Leite', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dr. Danilo de Freitas Martins', 'tipo' => 'substituto', 'is_substituto' => true],
            ['nome' => 'Dra. Caroline Montenegro de Almeida Aguiar', 'tipo' => 'substituto', 'is_substituto' => true],
        ];

        // Mapeamento de CARGOS por promotor
        $cargosPorPromotor = [
            // Titulares (Macapá)
            'Dra. Eliana Mena Cavalcante' => ['Coordenador PJ Cíveis'],
            'Dr. Marcelo José de Guimarães e Moraes' => ["Coordenador PJ’s da Família"],
            'Dr. Alberto Eli Pinheiro de Oliveira' => ['Coordenadoria-Geral do MP no CEJUSC'],
            'Dr. Jander Vilhena Nascimento' => ['Coordenador Promotorias Criminais'],
            'Dr. Tiago Silva Diniz' => ['Assessor Especial/PGJ'],
            'Dr. Ricardo Crispino Gomes' => ['Coordenador CAO Eleitoral'],
            'Dr. Vinícius Mendonça Carvalho' => ['Secretário Conselho Superior'],
            'Dra. Christie Damasceno Girão' => ['Chefe de Gabinete/PGJ'],
            'Dr. Alexandre Flávio M. Monteiro' => ['Procurador-Geral de Justiça'],
            'Dr. João Paulo de Oliveira Furlan' => ['Coordenador CAO da Ordem Tributária'],
            'Dr. Hélio Paulo Santos Furtado' => ['Coordenador das PJs do Tribunal do Júri'],
            'Dr. Rodrigo César Viana Assis' => ['Coordenador do NIMP'],
            'Dr. Rodrigo Celestino Pinheiro Menezes' => ['Coordenador da Central de Execução e Fiscalização de Penas e Medidas Alternativas'],
            'Dr. Fabiano da Silveira Castanho' => ['Coordenador CAO Moralidade Administrativa'],
            'Dr. Eduardo Kelson Fernandes de Pinho' => ['Coordenador do Complexo Cidadão Centro / Assessor da Corregedoria-Geral do MPAP'],
            'Dra. Samile Simões A. de Brito' => ['Coordenador do CAO Infância e Juventude'],
            'Dra. Neuza Rodrigues Barbosa' => ['Coordenadora das PJs Infância de Macapá'],
            'Dr. Paulo Celso Ramos dos Santos' => ['Coordenador CAO da Cidadania'],
            'Dr. Iaci Pelaes dos Reis' => ['Coordenador CAO Educação'],
            'Dr. Wueber Duarte Penafort' => ['Coordenador CAO da Saúde'],
            'Dra. Fábia Nilci Santana de Souza' => ['Coordenadora Promotorias Saúde'],
            'Dra. Alessandra Moro de C. Valente' => ['Coordenadora CAO de Defesa da Mulher'],
            'Dr. Saullo Patrício Andrade' => ['Coordenador LAB-LD'],
            'Dra. Clarisse Lindanor Alcantara Lax' => ['Coordenadora ASSEINTI'],
            'Dra. Klisiomar Lopes Dias' => ['Coordenadora Complexo Zona Sul'],
            'Dr. Luiz Marcos da Silva' => ['Coordenador Escritório Projetos e Convênios'],
            'Dr. Afonso Henrique O. Pereira' => ['Coordenador PJ Meio Ambiente'],
            'Dr. Marcelo Moreira dos Santos' => ['Coordenador Complexo Cidadão Zona Norte'],
            'Dr. André Luiz Dias Araújo' => ['Secretário Geral-MPAP'],
            'Dra. Andréa Guedes de M. Amanajás' => ['Coordenadora GAECO'],
            'Dr. Anderson Batista de Souza' => ['Assessor Especial da Corregedoria / Encarregado pelo Tratamento de Dados Pessoais no MPAP'],
            // Informação adicional
            'Dr. Magno Fernando Carbonaro Souza' => ['Atuação em Cível e Criminal em Macapá'],

            // Santana
            'Dr. Nilson Alves Costa' => ['Coordenador das PJs Cíveis e das Famílias de Santana'],
            'Dra. Silvia de Souza Canela' => ['Coord. Núcleo de Mediação, Conciliação e Práticas Restaurativas PJ Santana'],
            'Dr. Milton Ferreira do Amaral Júnior' => ['Coordenador do NUPIA'],
            'Dr. Manoel Edi de Aguiar Júnior' => ['Assessor Especial do Procurador-Geral de Justiça'],
            'Dr. David Zerbini de Faria Soares' => ['Coordenador PJ Criminais Santana'],
            'Dra. Fábia Regina Rocha Martins' => ['Coordenadora PJs Infância Santana'],
            'Dr. Miguel Angel Montiel Ferreira' => ['Coordenador-Geral dos Centros de Apoio Operacional'],
            'Dra. Elissandra Toscano B. N. Verardi' => ['Coordenadora CAO Meio Ambiente'],
            'Dra. Gisa Veiga Chaves' => ['Coordenadora PJs de Santana'],

            // Substitutos (atuações)
            'Dr. Adriano de Medeiros Escorbaiolli Nonaka' => ['Coord. PJ Laranjal do Jari (quando substituiu)'],
            'Dra. Tatyana Cavalcante da Silva' => ['Substituta no Tribunal do Júri'],
            'Dr. Vitor Medeiros dos Reis' => ['Substituições criminais, eleitorais e júri'],
            'Dr. Daniel Luz da Silva' => ['Atuação em PRODEMAP, júri e saúde pública'],
            'Dra. Ivana Rios Melo Coutinho' => ['Atuação em Defesa do Consumidor, Educação e Urbanismo'],
            'Dr. Julio Luiz de Medeiros Alves Lima Kuhlmann' => ['Atuação em PRODEMAP, júri e criminais'],
            'Dra. Aline Cristina Lopes da Silva' => ['Atuação em Ferreira Gomes, Tartarugalzinho e Santana'],
            'Dra. Sophia de Moura Leite' => ['Atuação em Amapá e Violência Doméstica'],
            'Dr. Danilo de Freitas Martins' => ['Atuação em Amapá, Pedra Branca e Defesa da Mulher'],
            'Dra. Caroline Montenegro de Almeida Aguiar' => ['Atuação em Porto Grande e Meio Ambiente Santana'],
        ];

        // Mapeamento de ZONAS ELEITORAIS por promotor (adicionadas como cargo também)
        $zonasPorPromotor = [
            // Santana
            'Dr. Horácio Luís Bezerra Coutinho' => ['numero' => 5, 'cargo' => 'PJ Eleitoral (5ª ZE)'],
            'Dra. Maria do Socorro Pelaes Braga' => ['numero' => 6, 'cargo' => 'PJ Eleitoral (6ª ZE)'],

            // Entrância Inicial
            'Dr. Marcos Rogério Tavares da Costa' => ['numero' => 7, 'cargo' => '7ª Zona Eleitoral'],
            'Dr. Leonardo Rocha Leite de Oliveira' => ['numero' => 4, 'cargo' => '4ª Zona Eleitoral (Oiapoque)'],
            'Dr. Igor Costa Coutinho' => ['numero' => 8, 'cargo' => '8ª Zona Eleitoral (Tartarugalzinho)'],
            'Dra. Caroline Montenegro de Almeida Aguiar' => ['numero' => 12, 'cargo' => '12ª Zona Eleitoral (Porto Grande)'],
            'Dra. Carolina Pereira de Oliveira' => ['numero' => 11, 'cargo' => '11ª Zona Eleitoral (Pedra Branca do Amapari)'],
        ];

        // Criar promotores titulares (com cargos e zonas)
        foreach ($promotoresTitulares as $promotor) {
            $nome = $promotor['nome'];
            $cargos = $cargosPorPromotor[$nome] ?? [];

            if (isset($zonasPorPromotor[$nome])) {
                $promotor['zona_eleitoral'] = true;
                $promotor['numero_da_zona_eleitoral'] = $zonasPorPromotor[$nome]['numero'];
                $cargos[] = $zonasPorPromotor[$nome]['cargo'];
            }

            if (!empty($cargos)) {
                $cargos = array_values(array_unique(array_filter(array_map('trim', $cargos))));
                $promotor['cargos'] = $cargos;
            }

            Promotor::create($promotor);
        }

        // Criar promotores substitutos (com cargos e zonas)
        foreach ($promotoresSubstitutos as $promotor) {
            $nome = $promotor['nome'];
            $cargos = $cargosPorPromotor[$nome] ?? [];

            if (isset($zonasPorPromotor[$nome])) {
                $promotor['zona_eleitoral'] = true;
                $promotor['numero_da_zona_eleitoral'] = $zonasPorPromotor[$nome]['numero'];
                $cargos[] = $zonasPorPromotor[$nome]['cargo'];
            }

            if (!empty($cargos)) {
                $cargos = array_values(array_unique(array_filter(array_map('trim', $cargos))));
                $promotor['cargos'] = $cargos;
            }

            Promotor::create($promotor);
        }
    }
}

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
            ['nome' => 'Dr. Magno Fernando Carbonaro Souza', 'tipo' => 'substituto', 'matricula' => '10134'],
            ['nome' => 'Dra. Eliana Mena Cavalcante', 'tipo' => 'titular', 'matricula' => '10036'],
            
            // Macapá - Família 
            ['nome' => 'Dr. Marcelo José de Guimarães e Moraes', 'tipo' => 'titular', 'matricula' => '10088'],
            ['nome' => 'Dr. Eli Pinheiro de Oliveira', 'tipo' => 'titular', 'matricula' => '10034'],
            ['nome' => 'Dr. Flávio Costa Cavalcante', 'tipo' => 'titular', 'matricula' => '10056'],
            ['nome' => 'Dr. Alberto Eli Pinheiro de Oliveira', 'tipo' => 'titular', 'matricula' => '10086'],
            
            // Macapá - Criminais
            ['nome' => 'Dr. Jander Vilhena Nascimento', 'tipo' => 'titular', 'matricula' => '10082'],
            ['nome' => 'Dr. Ubirajara Valente Éphina', 'tipo' => 'titular', 'matricula' => '10042'],
            ['nome' => 'Dr. Tiago Silva Diniz', 'tipo' => 'titular', 'matricula' => '10085'],
            ['nome' => 'Dr. Ricardo Crispino Gomes', 'tipo' => 'titular', 'matricula' => '10077'],
            ['nome' => 'Dr. Vinícius Mendonça Carvalho', 'tipo' => 'titular', 'matricula' => '10074'],
            ['nome' => 'Dra. Christie Damasceno Girão', 'tipo' => 'titular', 'matricula' => '10092'],
            ['nome' => 'Dr. Alexandre Flávio M. Monteiro', 'tipo' => 'titular', 'matricula' => '10075'],
            ['nome' => 'Dr. João Paulo de Oliveira Furlan', 'tipo' => 'titular', 'matricula' => '10065'],
            
            // Macapá - Tribunal do Júri
            ['nome' => 'Dr. Hélio Paulo Santos Furtado', 'tipo' => 'titular', 'matricula' => '10106'],
            
            // Macapá - Execução Penal
            ['nome' => 'Dr. Rodrigo César Viana Assis', 'tipo' => 'titular', 'matricula' => '10093'],
            ['nome' => 'Dr. Rodrigo Celestino Pinheiro Menezes', 'tipo' => 'titular', 'matricula' => '10091'],
            ['nome' => 'Dr. Fabiano da Silveira Castanho', 'tipo' => 'titular', 'matricula' => '10100'],
            
            // Macapá - Especializadas - Infância e Juventude
            ['nome' => 'Dr. Eduardo Kelson Fernandes de Pinho', 'tipo' => 'titular', 'matricula' => '10104'],
            ['nome' => 'Dra. Lindalva Gomes Jardina', 'tipo' => 'titular', 'matricula' => '10054'],
            ['nome' => 'Dra. Samile Simões A. de Brito', 'tipo' => 'titular', 'matricula' => '10096'],
            ['nome' => 'Dra. Neuza Rodrigues Barbosa', 'tipo' => 'titular', 'matricula' => '10087'],
            
            // Macapá - Outras Especializadas
            ['nome' => 'Dr. Paulo Celso Ramos dos Santos', 'tipo' => 'titular', 'matricula' => '10058'],
            ['nome' => 'Dr. Iaci Pelaes dos Reis', 'tipo' => 'titular', 'matricula' => '10057'],
            ['nome' => 'Dr. Wueber Duarte Penafort', 'tipo' => 'titular', 'matricula' => '10080'],
            ['nome' => 'Dra. Fábia Nilci Santana de Souza', 'tipo' => 'titular', 'matricula' => '10079'],
            ['nome' => 'Dra. Alessandra Moro de C. Valente', 'tipo' => 'titular', 'matricula' => '10059'],
            ['nome' => 'Dr. Saullo Patrício Andrade', 'tipo' => 'titular', 'matricula' => '10102'],
            ['nome' => 'Dra. Clarisse Lindanor Alcantara Lax', 'tipo' => 'titular', 'matricula' => '10101'],
            ['nome' => 'Dra. Klisiomar Lopes Dias', 'tipo' => 'titular', 'matricula' => '10084'],
            ['nome' => 'Dr. Luiz Marcos da Silva', 'tipo' => 'titular', 'matricula' => '10044'],
            ['nome' => 'Dr. Afonso Henrique O. Pereira', 'tipo' => 'titular', 'matricula' => '10052'],
            ['nome' => 'Dr. Marcelo Moreira dos Santos', 'tipo' => 'titular', 'matricula' => '10043'],
            ['nome' => 'Dr. André Luiz Dias Araújo', 'tipo' => 'titular', 'matricula' => '10067'],
            ['nome' => 'Dra. Andréa Guedes de M. Amanajás', 'tipo' => 'titular', 'matricula' => '10031'],
            ['nome' => 'Dr. José Cantuária Barreto', 'tipo' => 'titular', 'matricula' => '10069'],
            ['nome' => 'Dr. Anderson Batista de Souza', 'tipo' => 'titular', 'matricula' => '10072'],
            
            // Santana
            ['nome' => 'Dr. Nilson Alves Costa', 'tipo' => 'titular', 'matricula' => '10053'],
            ['nome' => 'Dra. Silvia de Souza Canela', 'tipo' => 'titular', 'matricula' => '10064'],
            ['nome' => 'Dr. Milton Ferreira do Amaral Júnior', 'tipo' => 'titular', 'matricula' => '10055'],
            ['nome' => 'Dr. Horácio Luís Bezerra Coutinho', 'tipo' => 'titular', 'matricula' => '10071'],
            ['nome' => 'Dr. Manoel Edi de Aguiar Júnior', 'tipo' => 'titular', 'matricula' => '10094'],
            ['nome' => 'Dr. David Zerbini de Faria Soares', 'tipo' => 'titular', 'matricula' => '10098'],
            ['nome' => 'Dra. Fábia Regina Rocha Martins', 'tipo' => 'titular', 'matricula' => '10083'],
            ['nome' => 'Dr. Miguel Angel Montiel Ferreira', 'tipo' => 'titular', 'matricula' => '10063'],
            ['nome' => 'Dra. Elissandra Toscano B. N. Verardi', 'tipo' => 'titular', 'matricula' => '10076'],
            ['nome' => 'Dra. Gisa Veiga Chaves', 'tipo' => 'titular', 'matricula' => '10068'],
            ['nome' => 'Dra. Maria do Socorro Pelaes Braga', 'tipo' => 'titular', 'matricula' => '10073'],
            
            // Entrância Inicial - 1º Núcleo
            ['nome' => 'Dr. Marcos Rogério Tavares da Costa', 'tipo' => 'titular', 'matricula' => '10118'],
            ['nome' => 'Dr. Arthur Senra Jacob', 'tipo' => 'titular', 'matricula' => '10110'],
            ['nome' => 'Dra. Marcela Balduíno Carneiro', 'tipo' => 'titular', 'matricula' => '10119'],
            ['nome' => 'Dr. Marco Valério Vale dos Santos', 'tipo' => 'titular', 'matricula' => '10078'],
            
            // Entrância Inicial - 2º Núcleo
            ['nome' => 'Dr. Matheus Silva Mendes', 'tipo' => 'titular', 'matricula' => '10120'],
            ['nome' => 'Dr. Leonardo Rocha Leite de Oliveira', 'tipo' => 'titular', 'matricula' => '10122'],
            ['nome' => 'Dr. Welder Tiago dos Santos Feitosa', 'tipo' => 'titular', 'matricula' => '10117'],
            
            // Entrância Inicial - 3º Núcleo
            ['nome' => 'Dr. Igor Costa Coutinho', 'tipo' => 'titular', 'matricula' => '10116'],
            ['nome' => 'Dra. Roberta Araújo Jacob', 'tipo' => 'titular', 'matricula' => '10111'],
            ['nome' => 'Dra. Carolina Pereira de Oliveira', 'tipo' => 'titular', 'matricula' => '10112'],
        ];

        // Promotores Substitutos
        $promotoresSubstitutos = [
            ['nome' => 'Dr. Adriano de Medeiros Escorbaiolli Nonaka', 'tipo' => 'substituto', 'matricula' => '10124'],
            ['nome' => 'Dra. Tatyana Cavalcante da Silva', 'tipo' => 'substituto', 'matricula' => '10125'],
            ['nome' => 'Dr. Vitor Medeiros dos Reis', 'tipo' => 'substituto', 'matricula' => '10126'],
            ['nome' => 'Dr. Daniel Luz da Silva', 'tipo' => 'substituto', 'matricula' => '10127'],
            ['nome' => 'Dra. Ivana Rios Melo Coutinho', 'tipo' => 'substituto', 'matricula' => '10128'],
            ['nome' => 'Dr. Julio Luiz de Medeiros Alves Lima Kuhlmann', 'tipo' => 'substituto', 'matricula' => '10129'],
            ['nome' => 'Dra. Aline Cristina Lopes da Silva', 'tipo' => 'substituto', 'matricula' => '10130'],
            ['nome' => 'Dra. Sophia de Moura Leite', 'tipo' => 'substituto', 'matricula' => '10131'],
            ['nome' => 'Dr. Danilo de Freitas Martins', 'tipo' => 'substituto', 'matricula' => '10132'],
            ['nome' => 'Dra. Caroline Montenegro de Almeida Aguiar', 'tipo' => 'substituto', 'matricula' => '10133'],
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

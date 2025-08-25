# Sistema de Geração de PDFs - Espelho

Este sistema permite gerar PDFs completos do espelho de promotorias, incluindo todos os dados do período publicado.

## Funcionalidades

### 1. PDF Completo
- Gera um PDF com todos os municípios, grupos de promotorias, promotores e eventos
- Organizado por município e grupo de promotorias
- Inclui todas as informações detalhadas de cada promotoria

### 2. PDF por Município
- Gera um PDF específico para um município selecionado
- Inclui resumo estatístico do município
- Contém apenas as promotorias e eventos do município escolhido

### 3. Visualização de PDF
- Permite visualizar o PDF no navegador antes de baixar
- Útil para verificar o conteúdo antes da impressão

## Como Usar

### Interface Web
1. Acesse a página do Espelho (`/gestao-espelho`)
2. Use os botões na parte superior para:
   - **PDF Completo**: Baixar o espelho completo
   - **Visualizar PDF**: Ver o PDF no navegador
   - **PDF por Município**: Selecionar um município específico

### Rotas da API
```php
// PDF Completo
GET /espelho/pdf/completo

// PDF por Município
GET /espelho/pdf/municipio/{municipioId}

// Visualizar PDF
GET /espelho/pdf/visualizar
```

## Estrutura dos Dados

### Período
- Busca automaticamente o período com status "publicado"
- Se não houver período publicado, usa o mais recente disponível

### Organização dos Dados
1. **Municípios** (ordenados com Macapá primeiro)
2. **Grupos de Promotorias** (por município)
3. **Promotorias** (com ordem específica para Macapá)
4. **Promotores Titulares** (com informações completas)
5. **Eventos** (com designações de promotores)

### Informações Incluídas
- Nome da promotoria
- Município e grupo
- Período vigente
- Promotor titular (nome, cargo, zona eleitoral, data de início)
- Eventos com datas e tipos
- Designações de promotores para cada evento
- Status de vacância (se aplicável)

## Arquivos do Sistema

### Controllers
- `EspelhoPdfController.php` - Controlador principal para geração de PDFs

### Views
- `espelho-completo.blade.php` - Template para PDF completo
- `espelho-municipio.blade.php` - Template para PDF por município

### Componentes Livewire
- `PdfGenerator.php` - Componente reutilizável para geração de PDFs
- `pdf-generator.blade.php` - Interface do componente

## Configuração

### DOMPDF
O sistema usa a biblioteca DOMPDF para geração de PDFs. Configurações disponíveis:

```php
// Configurações padrão
'dpi' => 150,
'defaultFont' => 'sans-serif',
'isHtml5ParserEnabled' => true,
'isRemoteEnabled' => false,
'defaultMediaType' => 'print'
```

### Papel
- Formato: A4
- Orientação: Portrait (retrato)

## Estilização

### CSS para PDF
- Fontes: DejaVu Sans (compatível com UTF-8)
- Cores: Esquema de cores consistente com a interface web
- Layout: Responsivo e otimizado para impressão
- Quebras de página: Automáticas entre municípios

### Elementos Visuais
- Cabeçalhos com cores diferenciadas
- Tabelas organizadas e legíveis
- Badges para tipos de eventos
- Ícones e indicadores visuais

## Tratamento de Erros

### Validações
- Verifica se existe período publicado
- Valida município selecionado
- Trata erros de geração de PDF

### Logs
- Registra erros de geração
- Mantém histórico de tentativas
- Facilita debugging

## Exemplos de Uso

### Geração Programática
```php
use App\Http\Controllers\EspelhoPdfController;

$controller = new EspelhoPdfController();
$pdf = $controller->gerarEspelhoCompleto($request);
```

### Integração com Outros Sistemas
```php
// Gerar PDF e salvar em storage
$pdf = Pdf::loadView('pdfs.espelho-completo', $dados);
$pdf->save(storage_path('app/public/espelho.pdf'));

// Enviar por email
Mail::send('emails.espelho', $dados, function($message) use ($pdf) {
    $message->attachData($pdf->output(), 'espelho.pdf');
});
```

## Manutenção

### Atualizações
- Verificar compatibilidade com versões do DOMPDF
- Testar geração com novos tipos de dados
- Validar formatação em diferentes navegadores

### Monitoramento
- Verificar logs de erro
- Monitorar tempo de geração
- Validar qualidade dos PDFs gerados

## Suporte

Para dúvidas ou problemas:
1. Verificar logs do sistema
2. Validar dados de entrada
3. Testar com dados mínimos
4. Consultar documentação do DOMPDF

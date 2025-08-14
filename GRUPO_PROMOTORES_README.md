# Componente GrupoPromotores - Documentação

## Visão Geral

O componente GrupoPromotores é um sistema completo de gerenciamento de grupos de promotorias desenvolvido com Livewire 3, seguindo os padrões estabelecidos no projeto. Ele permite criar, editar, visualizar e deletar grupos de promotorias com suas respectivas competências e municípios.

## Conceito do Sistema

Baseado no modelo da imagem fornecida, o sistema funciona da seguinte forma:

### Estrutura Hierárquica
1. **Grupo de Promotoria** (ex: "2ª PJ CÍVEL")
   - Nome identificador da promotoria
   - Competência específica
   - Município de atuação

2. **Promotoria Individual**
   - Nome específico da promotoria
   - Promotor titular responsável
   - Vinculada ao grupo

3. **Promotor**
   - Pode ser titular, substituto ou auxiliar
   - Vinculado a uma ou mais promotorias

## Funcionalidades

### ✅ CRUD Completo
- **Criar**: Adicionar novos grupos de promotorias
- **Ler**: Visualizar lista paginada com competências
- **Atualizar**: Editar informações dos grupos existentes
- **Deletar**: Remover grupos (com validação de dependências)

### ✅ Filtros e Busca
- **Busca por nome/competência**: Pesquisa em tempo real
- **Filtro por município**: Filtrar por município de atuação
- **Limpeza de filtros**: Botão para resetar todos os filtros

### ✅ Validações
- **Nome**: Obrigatório, mínimo 2 caracteres, máximo 100
- **Competência**: Obrigatório, mínimo 2 caracteres, máximo 200
- **Município**: Obrigatório, deve existir na tabela municipios

## Campos do Grupo de Promotoria

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `nome` | string | ✅ | Nome do grupo (ex: "2ª PJ CÍVEL") |
| `competencia` | string | ✅ | Competência específica |
| `municipios_id` | integer | ✅ | ID do município de atuação |

## Dados de Exemplo

O sistema inclui um seeder com 8 grupos de promotorias de exemplo:

1. **2ª PJ CÍVEL** - 4ª,5ª,6ª Cíveis e de Fazenda Pública
2. **1ª PJ CÍVEL** - 1ª,2ª,3ª Cíveis e de Família
3. **PJ CRIMINAL** - Crimes contra a pessoa, patrimônio e ordem pública
4. **PJ INFÂNCIA E JUVENTUDE** - Direitos da criança e do adolescente
5. **PJ MEIO AMBIENTE** - Crimes ambientais e proteção do meio ambiente
6. **PJ CONSUMIDOR** - Direitos do consumidor e relações de consumo
7. **PJ TRIBUTÁRIA** - Crimes contra a ordem tributária e econômica
8. **PJ EXECUÇÕES FISCAIS** - Execuções fiscais e dívida ativa

## Como Usar

### 1. Acesso
```
http://seu-site.com/grupo-promotores
```

### 2. Criar Novo Grupo
1. Clique no botão "Novo Grupo"
2. Preencha o nome (ex: "3ª PJ CÍVEL")
3. Digite a competência específica
4. Selecione o município
5. Clique em "Criar"

### 3. Editar Grupo
1. Clique no botão "Editar" na linha do grupo
2. Modifique os campos desejados
3. Clique em "Atualizar"

### 4. Deletar Grupo
1. Clique no botão "Deletar" na linha do grupo
2. Confirme a ação na caixa de diálogo

### 5. Filtrar e Buscar
- Use a barra de busca para encontrar por nome ou competência
- Use o dropdown para filtrar por município
- Use o botão "Limpar Filtros" para resetar

## Estrutura Técnica

### Componente Livewire
- **Arquivo**: `app/Livewire/GrupoPromotores.php`
- **Namespace**: `App\Livewire`
- **Traits**: `WithPagination`

### View Blade
- **Arquivo**: `resources/views/livewire/grupo-promotores.blade.php`
- **Framework CSS**: Tailwind CSS
- **JavaScript**: Alpine.js para interações

### Rotas
- **URL**: `/grupo-promotores`
- **Nome**: `grupo-promotores`
- **Método**: GET

### Modelos Relacionados
- **GrupoPromotoria**: Grupo principal
- **Promotoria**: Promotorias individuais
- **Promotor**: Promotores titulares
- **Municipio**: Municípios de atuação

## Executar Seeders

Para executar todos os seeders em ordem:

```bash
php artisan db:seed
```

Para executar apenas o seeder de grupos:

```bash
php artisan db:seed --class=GrupoPromotoriaSeeder
```

## Relacionamentos

### GrupoPromotoria
- `belongsTo` Municipio
- `hasMany` Promotoria

### Promotoria
- `belongsTo` GrupoPromotoria
- `belongsTo` Promotor (titular)

### Promotor
- `hasMany` Promotoria

## Personalizações

### Adicionar Novos Campos
1. Adicione o campo na migration
2. Atualize o modelo GrupoPromotoria
3. Adicione a propriedade no componente Livewire
4. Atualize a view com o novo campo

### Modificar Validações
Edite as regras no arquivo `app/Livewire/GrupoPromotores.php`:

```php
#[Rule('required|min:3|max:150')]
public string $nome = '';
```

## Tratamento de Erros

- **Validação**: Mensagens de erro específicas para cada campo
- **Delete**: Verificação de dependências antes de deletar
- **Feedback**: Mensagens de sucesso e erro com flash messages
- **Loading**: Estados de carregamento para operações assíncronas

## Responsividade

- **Mobile**: Layout empilhado com botões de tamanho adequado
- **Tablet**: Grid responsivo com filtros organizados
- **Desktop**: Layout completo com todas as funcionalidades

## Dependências

- **Laravel**: 10.x ou superior
- **Livewire**: 3.x
- **Tailwind CSS**: Para estilização
- **Alpine.js**: Para interações JavaScript
- **PHP**: 8.1 ou superior

## Fluxo de Dados

1. **Criação**: Grupo → Promotoria → Promotor Titular
2. **Consulta**: Busca por nome, competência ou município
3. **Atualização**: Modificação de dados existentes
4. **Exclusão**: Remoção com validação de dependências

## Suporte

Para dúvidas ou problemas:
1. Verifique os logs em `storage/logs/laravel.log`
2. Confirme que todas as dependências estão instaladas
3. Verifique se as migrations foram executadas
4. Confirme que os seeders foram executados com sucesso
5. Verifique se há dados nas tabelas relacionadas

# Componente Promotores - Documentação

## Visão Geral

O componente Promotores é um sistema completo de gerenciamento de promotores desenvolvido com Livewire 3, seguindo os padrões estabelecidos no projeto. Ele permite criar, editar, visualizar e deletar promotores com uma interface moderna e responsiva.

## Funcionalidades

### ✅ CRUD Completo
- **Criar**: Adicionar novos promotores ao sistema
- **Ler**: Visualizar lista paginada de promotores
- **Atualizar**: Editar informações dos promotores existentes
- **Deletar**: Remover promotores (com validação de dependências)

### ✅ Filtros e Busca
- **Busca por nome**: Pesquisa em tempo real com debounce de 300ms
- **Filtro por tipo**: Filtrar por tipo de promotor (titular, substituto, auxiliar)
- **Limpeza de filtros**: Botão para resetar todos os filtros aplicados

### ✅ Validações
- **Nome**: Obrigatório, mínimo 2 caracteres, máximo 100
- **Tipo**: Obrigatório, string com máximo 50 caracteres
- **Substituto**: Boolean (checkbox)
- **Observações**: Opcional, máximo 500 caracteres

## Campos do Promotor

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `nome` | string | ✅ | Nome completo do promotor |
| `tipo` | string | ✅ | Tipo: titular, substituto, auxiliar |
| `is_substituto` | boolean | ❌ | Flag indicando se é substituto |
| `observacoes` | text | ❌ | Observações adicionais |

## Como Usar

### 1. Acesso
```
http://seu-site.com/promotores
```

### 2. Criar Novo Promotor
1. Clique no botão "Novo Promotor"
2. Preencha os campos obrigatórios
3. Clique em "Criar"

### 3. Editar Promotor
1. Clique no botão "Editar" na linha do promotor
2. Modifique os campos desejados
3. Clique em "Atualizar"

### 4. Deletar Promotor
1. Clique no botão "Deletar" na linha do promotor
2. Confirme a ação na caixa de diálogo

### 5. Filtrar e Buscar
- Use a barra de busca para encontrar promotores por nome
- Use o dropdown para filtrar por tipo
- Use o botão "Limpar Filtros" para resetar

## Estrutura Técnica

### Componente Livewire
- **Arquivo**: `app/Livewire/Promotores.php`
- **Namespace**: `App\Livewire`
- **Traits**: `WithPagination`

### View Blade
- **Arquivo**: `resources/views/livewire/promotores.blade.php`
- **Framework CSS**: Tailwind CSS
- **JavaScript**: Alpine.js para interações

### Rotas
- **URL**: `/promotores`
- **Nome**: `promotores`
- **Método**: GET

## Dados de Exemplo

O sistema inclui um seeder com 5 promotores de exemplo:

1. **Dr. João Silva** - Titular da 1ª Promotoria
2. **Dra. Maria Santos** - Titular da 2ª Promotoria  
3. **Dr. Carlos Oliveira** - Substituto para plantões
4. **Dra. Ana Costa** - Auxiliar da 1ª Promotoria
5. **Dr. Pedro Lima** - Substituto para férias/licenças

## Executar Seeder

```bash
php artisan db:seed --class=PromotorSeeder
```

Ou para todos os seeders:

```bash
php artisan db:seed
```

## Personalizações

### Adicionar Novos Tipos
Para adicionar novos tipos de promotor, edite o arquivo `resources/views/livewire/promotores.blade.php` e adicione as opções no select:

```html
<option value="novo_tipo">Novo Tipo</option>
```

### Modificar Validações
Edite as regras no arquivo `app/Livewire/Promotores.php`:

```php
#[Rule('required|min:3|max:150')]
public string $nome = '';
```

### Adicionar Novos Campos
1. Adicione o campo na migration
2. Atualize o modelo Promotor
3. Adicione a propriedade no componente Livewire
4. Atualize a view com o novo campo

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

## Suporte

Para dúvidas ou problemas:
1. Verifique os logs em `storage/logs/laravel.log`
2. Confirme que todas as dependências estão instaladas
3. Verifique se as migrations foram executadas
4. Confirme que o seeder foi executado com sucesso

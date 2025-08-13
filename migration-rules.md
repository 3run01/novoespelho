# 📋 Regras das Migrations - Sistema de Espelhos

## 🎯 Visão Geral do Sistema

O sistema de espelhos permite gerenciar **plantões de promotores** com **múltiplos promotores** por evento, cada um em **períodos diferentes**. Um espelho consolida todas as informações relacionadas a um plantão específico.

## 🏗️ Estrutura das Tabelas

### **1. Tabela `periodos`**
- **Propósito:** Define períodos de tempo reutilizáveis
- **Campos:**
  - `periodo_inicio` (date): Data de início do período
  - `periodo_fim` (date): Data de fim do período
- **Uso:** Um período pode ser usado em múltiplos espelhos, eventos e designações

### **2. Tabela `municipios`**
- **Propósito:** Cadastro de municípios onde ocorrem os plantões
- **Uso:** Cada espelho está vinculado a um município específico

### **3. Tabela `grupo_promotorias`**
- **Propósito:** Agrupa promotorias por região ou especialidade
- **Relacionamento:** Vinculado a um município
- **Uso:** Organiza promotorias para facilitar a designação

### **4. Tabela `promotorias`**
- **Propósito:** Cadastro de promotorias específicas
- **Relacionamento:** Pertence a um grupo de promotorias
- **Uso:** Vincula eventos e promotores a uma promotoria específica

### **5. Tabela `promotores`**
- **Propósito:** Cadastro de promotores disponíveis
- **Campos importantes:**
  - `tipo` (string): Tipo do promotor (titular, substituto, plantão, etc.)
  - `is_substituto` (boolean): Indica se é promotor substituto
- **Uso:** Promotores são designados para eventos através da tabela pivot

### **6. Tabela `plantao_atendimento`**
- **Propósito:** Define os horários e tipos de plantão
- **Uso:** Vinculado ao espelho para definir quando o plantão está ativo

### **7. Tabela `eventos`**
- **Propósito:** Cadastro de eventos que compõem um plantão
- **Campos importantes:**
  - `periodo_id`: Período geral do evento
  - `periodo_inicio` e `periodo_fim`: Datas específicas do evento
  - `is_urgente`: Indica se é um evento prioritário
- **Uso:** Um evento pode ter múltiplos promotores em períodos diferentes

### **8. Tabela `espelhos` (TABELA CENTRAL)**
- **Propósito:** Consolida todas as informações de um plantão
- **Relacionamentos:**
  - `periodo_id`: Período geral do espelho
  - `plantao_atendimento_id`: Tipo de plantão
  - `grupo_promotorias_id`: Grupo responsável
  - `municipio_id`: Município onde ocorre
- **Uso:** É a tabela principal que reúne todas as informações relacionadas

### **9. Tabela `espelho_evento` (PIVOT)**
- **Propósito:** Relaciona espelhos com eventos
- **Campos:**
  - `ordem`: Sequência dos eventos no espelho
  - `observacoes_evento`: Notas específicas do evento neste espelho
- **Uso:** Permite que um espelho tenha múltiplos eventos em ordem específica

### **10. Tabela `evento_promotor` (PIVOT)**
- **Propósito:** Relaciona eventos com promotores (CORE DO SISTEMA)
- **Campos importantes:**
  - `periodo_id`: Período específico deste promotor neste evento
  - `data_inicio_designacao`: Quando este promotor começa
  - `data_fim_designacao`: Quando este promotor termina
  - `tipo`: Tipo de designação (titular, substituto, plantão)
  - `ordem`: Sequência dos promotores no evento
- **Uso:** Permite que um evento tenha múltiplos promotores, cada um em períodos diferentes

## 🔄 Fluxo de Funcionamento

### **1. Criação de um Espelho:**
```
1. Criar período (ex: Janeiro 2025)
2. Criar espelho vinculando:
   - Período
   - Plantão de atendimento
   - Grupo de promotorias
   - Município
3. Adicionar eventos ao espelho
4. Para cada evento, designar promotores com períodos específicos
```

### **2. Exemplo Prático:**
```
ESPELHO: "Plantão Janeiro 2025 - Município X"
├── EVENTO: "Audiência de Custódia"
│   ├── Promotor A → 1º a 15 de Janeiro
│   └── Promotor B → 16 a 31 de Janeiro
├── EVENTO: "Diligências"
│   ├── Promotor C → 1º a 10 de Janeiro
│   └── Promotor D → 11 a 31 de Janeiro
└── EVENTO: "Atendimento ao Público"
    └── Promotor E → Todo Janeiro
```

## 📊 Regras de Negócio

### **1. Períodos:**
- ✅ Um período pode ser usado em múltiplos lugares
- ✅ Períodos são reutilizáveis e independentes
- ✅ Evita inconsistências de datas

### **2. Promotores:**
- ✅ Um promotor pode estar em múltiplos eventos
- ✅ Um promotor pode ter períodos diferentes em eventos diferentes
- ✅ Cada designação tem seu próprio período específico

### **3. Eventos:**
- ✅ Um evento pode ter múltiplos promotores
- ✅ Cada promotor tem seu período individual no evento
- ✅ Eventos são organizados por ordem no espelho

### **4. Espelhos:**
- ✅ Um espelho consolida todas as informações
- ✅ Pode ter múltiplos eventos
- ✅ Cada evento pode ter múltiplos promotores
- ✅ Facilita consultas e relatórios

## 🚀 Vantagens da Estrutura

### **1. Performance:**
- Índices otimizados para consultas rápidas
- JOINs nativos do Laravel funcionam perfeitamente
- Sem campos JSON que prejudicam performance

### **2. Flexibilidade:**
- Múltiplos promotores por evento
- Períodos individuais para cada promotor
- Fácil adição/remoção de promotores

### **3. Manutenibilidade:**
- Estrutura clara e padronizada
- Fácil de entender e modificar
- Relacionamentos bem definidos

### **4. Escalabilidade:**
- Suporta crescimento do sistema
- Fácil de adicionar novos tipos de dados
- Consultas eficientes mesmo com muitos registros

## 🔍 Exemplos de Consultas

### **1. Buscar promotores de um evento:**
```php
$promotores = $evento->promotores;
```

### **2. Buscar promotores disponíveis em um período:**
```php
$promotores = Promotor::disponivelNoPeriodo($periodoId)->get();
```

### **3. Buscar eventos de um promotor:**
```php
$eventos = $promotor->eventos;
```

### **4. Buscar espelhos por município:**
```php
$espelhos = Espelho::porMunicipio($municipioId)->get();
```

## ⚠️ Pontos de Atenção

### **1. Ordem das Migrations:**
- Respeitar a ordem de criação das tabelas
- Tabelas com foreign keys devem ser criadas depois das tabelas referenciadas

### **2. Índices:**
- Manter os índices criados para performance
- Não remover índices sem necessidade

### **3. Relacionamentos:**
- Usar `onDelete('cascade')` apenas quando necessário
- Verificar integridade referencial

### **4. Campos Únicos:**
- A constraint única em `evento_promotor` evita duplicatas
- Não remover sem justificativa

## 📝 Comandos Úteis

```bash
# Executar todas as migrations
php artisan migrate

# Reverter e executar novamente (desenvolvimento)
php artisan migrate:fresh

# Verificar status das migrations
php artisan migrate:status

# Criar nova migration
php artisan make:migration nome_da_migration
```

---

**Última atualização:** Janeiro 2025  
**Desenvolvedor:** Sistema de Espelhos  
**Versão:** 1.0

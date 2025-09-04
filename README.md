# Sistema de Espelho - Gestão de Promotorias

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/your-org/espelho)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-orange.svg)](https://livewire.laravel.com)

## Visão Geral

O Sistema de Espelho é uma aplicação web desenvolvida para gestão de promotorias e plantões de urgência. A aplicação permite o controle de períodos, municípios, promotores e eventos relacionados ao sistema judiciário, oferecendo uma interface moderna e responsiva para administração desses dados.

## Stack Tecnológica

- **Backend**: Laravel 11.x
- **Frontend**: Livewire 3.x, Tailwind CSS 3.x
- **Build Tool**: Vite 6.x
- **Banco de Dados**: PostgreSQL 17
- **Containerização**: Docker & Docker Compose
- **Admin Panel**: Filament 3.x
- **PDF Generation**: DomPDF
- **Testes**: PHPUnit 11.x

## Pré-requisitos

Antes de começar, certifique-se de ter instalado em sua máquina:

- [Docker](https://docs.docker.com/get-docker/) (versão 20.10 ou superior)
- [Docker Compose](https://docs.docker.com/compose/install/) (versão 2.0 ou superior)
- [Git](https://git-scm.com/downloads)

## Começando

### 1. Clone o repositório

```bash
git clone https://github.com/your-org/espelho.git
cd espelho/novoespelho
```

### 2. Configure as variáveis de ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações locais, especialmente as variáveis do banco de dados:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=espelho-docker
DB_USERNAME=postgres
DB_PASSWORD=123@senha
```

### 3. Construa e execute os containers

```bash
docker-compose up -d --build
```

### 4. Instale as dependências do Composer

```bash
docker-compose exec laravelapp composer install
```

### 5. Gere a chave da aplicação

```bash
docker-compose exec laravelapp php artisan key:generate
```

### 6. Execute as migrations e seeders

```bash
docker-compose exec laravelapp php artisan migrate
docker-compose exec laravelapp php artisan db:seed
```

### 7. Instale as dependências do NPM e construa os assets

```bash
docker-compose exec laravelapp npm install
docker-compose exec laravelapp npm run build
```

## Uso

Após seguir todos os passos de instalação, a aplicação estará disponível em:

- **Aplicação Principal**: http://localhost:8000
- **Admin Panel (Filament)**: http://localhost:8000/admin
- **PgAdmin**: http://localhost:5050

### Credenciais Padrão

- **Email**: admin@admin.com
- **Senha**: admin

## Executando Testes

Para executar a suíte de testes dentro do container:

```bash
docker-compose exec laravelapp php artisan test
```

Ou para executar testes específicos:

```bash
docker-compose exec laravelapp php artisan test --filter=ExampleTest
```

## Comandos Docker Úteis

### Gerenciamento de Containers

```bash
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Parar e remover volumes
docker-compose down -v

# Visualizar logs
docker-compose logs -f laravelapp

# Reconstruir containers
docker-compose up -d --build
```

### Acesso ao Container da Aplicação

```bash
# Acessar shell do container
docker-compose exec laravelapp bash

# Executar comandos Artisan
docker-compose exec laravelapp php artisan migrate
docker-compose exec laravelapp php artisan make:controller ExampleController

# Executar comandos NPM
docker-compose exec laravelapp npm run dev
docker-compose exec laravelapp npm run build
```

### Gerenciamento do Banco de Dados

```bash
# Acessar PostgreSQL
docker-compose exec db psql -U postgres -d espelho-docker

# Backup do banco
docker-compose exec db pg_dump -U postgres espelho-docker > backup.sql

# Restaurar backup
docker-compose exec -T db psql -U postgres -d espelho-docker < backup.sql
```

## Variáveis de Ambiente

As seguintes variáveis são críticas para o funcionamento da aplicação:

### Banco de Dados
- `DB_CONNECTION=pgsql` - Tipo de conexão do banco
- `DB_HOST=db` - Host do banco (nome do serviço Docker)
- `DB_PORT=5432` - Porta do PostgreSQL
- `DB_DATABASE=espelho-docker` - Nome do banco de dados
- `DB_USERNAME=postgres` - Usuário do banco
- `DB_PASSWORD=123@senha` - Senha do banco

### Aplicação
- `APP_NAME="Sistema de Espelho"` - Nome da aplicação
- `APP_ENV=local` - Ambiente de execução
- `APP_KEY=` - Chave de criptografia (gerada automaticamente)
- `APP_DEBUG=true` - Modo debug
- `APP_URL=http://localhost:8000` - URL da aplicação

### Cache e Sessão
- `CACHE_DRIVER=file` - Driver de cache
- `SESSION_DRIVER=file` - Driver de sessão
- `QUEUE_CONNECTION=sync` - Driver de filas

## Estrutura do Projeto

```
novoespelho/
├── app/
│   ├── Livewire/          # Componentes Livewire
│   ├── Models/            # Modelos Eloquent
│   └── Providers/         # Service Providers
├── database/
│   ├── migrations/        # Migrations do banco
│   └── seeders/          # Seeders para dados iniciais
├── resources/
│   ├── views/            # Views Blade
│   ├── css/              # Estilos CSS
│   └── js/               # JavaScript
├── routes/               # Rotas da aplicação
├── tests/                # Testes automatizados
├── docker-compose.yml    # Configuração Docker Compose
└── Dockerfile           # Imagem Docker da aplicação
```



---


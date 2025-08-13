# Testando Espelho

## Descrição do Projeto

Este projeto é uma aplicação web desenvolvida com Laravel e Vite, utilizando Tailwind CSS para estilização. O objetivo é fornecer uma interface moderna e responsiva, permitindo a gestão de usuários e municípios, além de funcionalidades relacionadas a grupos de promotoria.

## Tecnologias Utilizadas

- **Laravel**: Framework PHP para desenvolvimento de aplicações web.
- **Vite**: Ferramenta de construção e desenvolvimento para aplicações front-end.
- **Tailwind CSS**: Framework CSS utilitário para estilização rápida e responsiva.
- **Axios**: Biblioteca para fazer requisições HTTP.
- **PostCSS**: Ferramenta para transformar CSS com JavaScript.
- **Autoprefixer**: Plugin PostCSS para adicionar prefixos de navegador automaticamente.
- **Concurrently**: Ferramenta para executar múltiplos comandos simultaneamente.

## Scripts

- `npm run build`: Compila os arquivos para produção usando Vite.
- `npm run dev`: Inicia o servidor de desenvolvimento com Vite.

## Rodando os Seeders

Para popular o banco de dados com dados iniciais, você pode rodar os seeders. Siga os passos abaixo:

1. **Certifique-se de que as migrations foram executadas**:
   ```bash
   php artisan migrate
   ```

2. **Rodar os Seeders**:
   Para rodar todos os seeders registrados, execute:
   ```bash
   php artisan db:seed
   ```

   Se você quiser rodar um seeder específico, como o `UserSeeder`, use:
   ```bash
   php artisan db:seed --class=UserSeeder
   ```

3. Configure o arquivo `.env` com as credenciais do banco de dados.

4. Execute as migrations e seeders conforme descrito acima.

# Atenção
 - Primeiro rodar npm run dev
 - Depois rodar php artisan serve


 ## Docker

 - docker compose up -d db
 - docker compose build
 - docker compose up 

 ## Rodar migrations do banco de dados

 - docker exec laravelapp php artisan migrate
 - docker exec laravelapp php artisan db:seed


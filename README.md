# SEBRAE

Aplicação Laravel para cadastro, consulta, edição e exclusão de usuários, com interface em Blade e ambiente preparado para rodar com Docker Compose.

## Exemplos de tela

### Lista de usuários

<img src = "https://private-user-images.githubusercontent.com/79344/581619957-d9a33ea2-1ff8-4e5f-b411-b0ab35ec5e7e.png?jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3NzY4MDMwMTUsIm5iZiI6MTc3NjgwMjcxNSwicGF0aCI6Ii83OTM0NC81ODE2MTk5NTctZDlhMzNlYTItMWZmOC00ZTVmLWI0MTEtYjBhYjM1ZWM1ZTdlLnBuZz9YLUFtei1BbGdvcml0aG09QVdTNC1ITUFDLVNIQTI1NiZYLUFtei1DcmVkZW50aWFsPUFLSUFWQ09EWUxTQTUzUFFLNFpBJTJGMjAyNjA0MjElMkZ1cy1lYXN0LTElMkZzMyUyRmF3czRfcmVxdWVzdCZYLUFtei1EYXRlPTIwMjYwNDIxVDIwMTgzNVomWC1BbXotRXhwaXJlcz0zMDAmWC1BbXotU2lnbmF0dXJlPWFjMWQwNDM2NzIzNzdlNGE2OGFlNGI5ZGUzMzM1ZTA0ZGZiODNmYWY5YTU1ZTg4MzdmM2VhMTg2NmM5MGEzNTQmWC1BbXotU2lnbmVkSGVhZGVycz1ob3N0JnJlc3BvbnNlLWNvbnRlbnQtdHlwZT1pbWFnZSUyRnBuZyJ9.0SP-5_YuU2G9m_HItPsvTBofMnTHusjNwQfzwAJ47rM">


### Cadastro de usuário
<img src="https://private-user-images.githubusercontent.com/79344/581619958-a9db5b72-95e7-4956-8086-91a394f6c2c2.png?jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3NzY4MDMwMTUsIm5iZiI6MTc3NjgwMjcxNSwicGF0aCI6Ii83OTM0NC81ODE2MTk5NTgtYTlkYjViNzItOTVlNy00OTU2LTgwODYtOTFhMzk0ZjZjMmMyLnBuZz9YLUFtei1BbGdvcml0aG09QVdTNC1ITUFDLVNIQTI1NiZYLUFtei1DcmVkZW50aWFsPUFLSUFWQ09EWUxTQTUzUFFLNFpBJTJGMjAyNjA0MjElMkZ1cy1lYXN0LTElMkZzMyUyRmF3czRfcmVxdWVzdCZYLUFtei1EYXRlPTIwMjYwNDIxVDIwMTgzNVomWC1BbXotRXhwaXJlcz0zMDAmWC1BbXotU2lnbmF0dXJlPTBkZWI5ZjJkZWY1ZTEzZGI5ZjlkMjFlNjc4NWQzNTEyMGZjM2ZmNGQ4MWE1OTA4ZmVhMjNhZTdiZmRkZmZhY2ImWC1BbXotU2lnbmVkSGVhZGVycz1ob3N0JnJlc3BvbnNlLWNvbnRlbnQtdHlwZT1pbWFnZSUyRnBuZyJ9.GfkPie_aCn6rYMsDIqim7-RWsjTLiPe8W9vSo9fOTzA">

### Perfil do usuário
<img src="https://private-user-images.githubusercontent.com/79344/581619959-3d18eec4-4a71-4066-8bb0-a19127b61505.png?jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3NzY4MDMwMTUsIm5iZiI6MTc3NjgwMjcxNSwicGF0aCI6Ii83OTM0NC81ODE2MTk5NTktM2QxOGVlYzQtNGE3MS00MDY2LThiYjAtYTE5MTI3YjYxNTA1LnBuZz9YLUFtei1BbGdvcml0aG09QVdTNC1ITUFDLVNIQTI1NiZYLUFtei1DcmVkZW50aWFsPUFLSUFWQ09EWUxTQTUzUFFLNFpBJTJGMjAyNjA0MjElMkZ1cy1lYXN0LTElMkZzMyUyRmF3czRfcmVxdWVzdCZYLUFtei1EYXRlPTIwMjYwNDIxVDIwMTgzNVomWC1BbXotRXhwaXJlcz0zMDAmWC1BbXotU2lnbmF0dXJlPWE4MzdjMTAwYjRkMzY1ZTNhNDQ5MWMyM2RiZmE3ZGU2ZmRjMTNhMWVlMjZlZDFiN2JhM2MyN2U2MjExMGU2MTQmWC1BbXotU2lnbmVkSGVhZGVycz1ob3N0JnJlc3BvbnNlLWNvbnRlbnQtdHlwZT1pbWFnZSUyRnBuZyJ9.Y2Ty-b2-J7usYijCaN3SnJUluXI9RgWAgp0HNOamOB4">





## Requisitos

- Docker
- Docker Compose

## Como executar com Docker Compose

1. Clone o repositório e entre na pasta do projeto.
2. Se ainda não existir, copie o arquivo de ambiente:
   ```bash
   cp .env.example .env
   ```
3. Suba os containers:
   ```bash
   docker-compose up -d --build
   ```
4. Instale as dependências do PHP dentro do container:
   ```bash
   docker-compose exec app composer install
   ```
5. Gere a chave da aplicação:
   ```bash
   docker-compose exec app php artisan key:generate
   ```
6. Rode as migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```
7. Crie o link público para os arquivos enviados pelo usuário:
   ```bash
   docker-compose exec app php artisan storage:link
   ```
8. Acesse a aplicação em:
   ```bash
   http://localhost:8000
   ```

### Credenciais do banco usadas no `docker-compose.yml`
#### Essas infos são exemplos de como deve estar a configuração do banco de dados no arquivo .env

- Banco: `laravel`
- Usuário: `laravel`
- Senha: `secret`
- Root: `rootsecret`

## Estrutura principal do projeto

- `docker-compose.yml`: define os serviços da aplicação (`app`, `webserver` e `db`).
- `Dockerfile`: monta a imagem PHP 8.3 FPM com as extensões necessárias ao projeto.
- `artisan`: ponto de entrada para os comandos do Laravel.
- `composer.json`: dependências PHP e scripts do projeto.
- `package.json`: dependências do front-end e comandos do Vite.
- `bootstrap/app.php`: configuração inicial da aplicação e registro das rotas.
- `routes/web.php`: concentra as rotas da interface web e redireciona `/` para `/users`.
- `app/Http/Controllers/UserController.php`: controla o fluxo do CRUD de usuários.
- `app/Services/UserService.php`: concentra a lógica de negócio, busca, criação, atualização e remoção.
- `app/DTOs/UserData.php`: transporta os dados validados do formulário para a camada de serviço.
- `app/Http/Requests/StoreUserRequest.php` e `app/Http/Requests/UpdateUserRequest.php`: validações de criação e edição.
- `app/Models/User.php`: modelo Eloquent do usuário, incluindo casts e o atributo derivado `avatar_url`.
- `resources/views/`: telas Blade da aplicação, incluindo layout, listagem e formulários.
- `database/`: migrations, seeders e o arquivo `database.sqlite` que acompanha o esqueleto Laravel.
- `docker/nginx/conf.d/default.conf`: configuração do Nginx para servir a aplicação em `localhost:8000`.
- `docker/php/conf.d/uploads.ini`: ajusta limites de upload e memória do PHP.


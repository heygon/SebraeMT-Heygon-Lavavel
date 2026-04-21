# SEBRAE

Aplicação Laravel para cadastro, consulta, edição e exclusão de usuários, com interface em Blade e ambiente preparado para rodar com Docker Compose.

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

## Observações

- Se você alterar arquivos de front-end, rode o Vite no ambiente local com `npm install` e `npm run dev`.
- O projeto usa o volume do Docker para compartilhar o código entre sua máquina e os containers.

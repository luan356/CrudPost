# CrudPost - Projeto de CRUD

Este projeto é um CRUD de posts, com backend em PHP (Slim Framework) e frontend em React.



## ⚠️ Atenção Importante

**Para que o projeto funcione corretamente, é obrigatório ter os dois servidores rodando ao mesmo tempo:**

- Backend (PHP) na porta **8000** → http://localhost:8000
- Frontend (React) na porta **3000** → http://localhost:3000

O frontend faz requisições para a API do backend. Se apenas um deles estiver ativo, você verá erros (como falhas de CORS, 404 ou respostas vazias).

**Sempre inicie primeiro o backend e depois o frontend.**

**Usuario de teste**
  "name": "Luan",
  "email": "luan@opovo.com",
  "password": "123456"



## Pré-requisitos

Antes de rodar o projeto, certifique-se de ter instalado:

- PHP >= 8.4
- Composer
- Node.js >= 20
- npm ou yarn
- SQLite (opcional, se quiser usar banco local)

## Estrutura do projeto
CrudPost/
├─ backend/
│  ├─ public/
│  │  └─ index.php
│  ├─ src/
│  │  ├─ Routes/api.php
│  │  └─ Config/database.php
│  ├─ vendor/
│  └─ composer.json
├─ frontend/
│  └─ post-frontend/
│     ├─ package.json
│     ├─ src/
│     └─ public/
└─ README.md




## Configurando o backend

Entre na pasta do backend:

```bash
cd CrudPost/backend


Instale as dependências via Composer:
composer install


Configure o banco de dados SQLite (ou outro banco desejado) em src/Config/database.php.
Configure o .env se necessário:
DB_DRIVER=sqlite
DB_PATH=/path/to/database.sqlite

Inicie o servidor PHP:
php -S localhost:8000 -t public

O backend estará disponível em: http://localhost:8000.




Configurando o frontend

Entre na pasta do frontend:
cd CrudPost/frontend/post-frontend

Instale as dependências:
npm install

Inicie o servidor de desenvolvimento:
npm start



O frontend estará disponível em: http://localhost:3000.
Certifique-se de que o backend esteja rodando antes de testar o frontend.
Testando a API

Listar posts: GET /posts
Criar post: POST /posts
Editar post: PUT /posts/{id}
Deletar post: DELETE /posts/{id}

Use o Postman ou Insomnia para testar as rotas.
Atenção: se estiver usando React, configure o CORS no backend (já incluso no seu index.php).


Observações

Se optar por Docker, verifique portas 8000 (backend) e 3000 (frontend) livres.
Se não estiver usando Docker, rode o backend com PHP embutido como mostrado acima.
Certifique-se de que o index.php está dentro da pasta public/ para Slim Framework.
Se estiver recebendo erro 404, confira se a rota no frontend aponta para a URL correta do backend.

Conclusão
Este projeto serve como base para aprender integração PHP Slim + React, incluindo CRUD básico e comunicação via API REST.
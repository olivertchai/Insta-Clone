# InstaClone API 📸

Este é o backend de um clone do Instagram, desenvolvido como parte do programa de Trainee. A API foi construída seguindo as melhores práticas de desenvolvimento, com foco em escalabilidade, segurança e separação de responsabilidades.

## 🚀 Tecnologias e Ferramentas

* **Linguagem:** PHP 8.2+
* **Framework:** [Laravel 13](https://laravel.com)
* **Banco de Dados:** MySQL
* **Autenticação:** Laravel Sanctum (Tokens via API)
* **Ambiente:** Docker & Docker Compose
* **Arquitetura:** Model-Service-Controller (MSC)

## 🏗️ Diferenciais Técnicos

### Arquitetura MSC (Model-Service-Controller)
Para garantir que o código seja testável e fácil de manter, implementei a camada de **Services**. Toda a lógica de negócio (como processamento de imagens e regras de follows) reside nos Services, mantendo os Controllers limpos e focados apenas em requisições e respostas.

### Modelagem de Dados e Eloquent
O projeto utiliza o poder do Eloquent ORM para gerenciar relacionamentos complexos:
* **N:N (Many-to-Many):** Implementado nos sistemas de **Seguidores** (Follows) e **Curtidas** (Likes).
* **1:N (One-to-Many):** Relacionamento entre usuários, posts e comentários.

### Gerenciamento de Mídias
Sistema robusto de upload de imagens para Avatares e Posts, utilizando o **Laravel Storage** com suporte a links simbólicos (Symlinks) para servir arquivos de forma pública e segura.

## 🛠️ Como rodar o projeto

1.  **Clonar o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/instaclone-backend.git](https://github.com/seu-usuario/instaclone-backend.git)
    cd instaclone-backend
    ```

2.  **Subir os containers (Docker):**
    ```bash
    docker compose up -d
    ```

3.  **Instalar dependências (via container):**
    ```bash
    docker compose exec app composer install
    ```

4.  **Configurar o banco e chaves:**
    ```bash
    docker compose exec app php artisan key:generate
    docker compose exec app php artisan migrate --seed
    ```

5.  **Criar link para as imagens:**
    ```bash
    docker compose exec app php artisan storage:link
    ```

A API estará disponível em `http://localhost:8000`.

## 📍 Principais Endpoints

### Autenticação
* `POST /api/auth/register` - Registro de novo usuário.
* `POST /api/auth/login` - Login e retorno de Token.

### Usuários & Perfil
* `GET /api/users/{username}` - Dados públicos do perfil.
* `GET /api/users/suggestions` - Sugestões de perfis para seguir.
* `POST /api/users/me/avatar` - Upload de foto de perfil.
* `POST /api/users/{id}/follow` - Seguir/Deixar de seguir.

### Posts & Feed
* `GET /api/feed` - Posts dos usuários que você segue.
* `POST /api/posts` - Criação de post com imagem.
* `POST /api/posts/{id}/like` - Toggle de curtida no post.

---
Desenvolvido por **Wagner Oliveira** - [LinkedIn](SEU_LINKEDIN_AQUI)

# 🚀 Larakey - SSO & Role Management

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat-square&logo=vue.js)](https://vuejs.org)
[![Keycloak](https://img.shields.io/badge/Keycloak-26.6.1-FF2600?style=flat-square&logo=keycloak)](https://www.keycloak.org)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-336791?style=flat-square&logo=postgresql)](https://www.postgresql.org)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker)](https://www.docker.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

**Uma solução moderna de SSO (Single Sign-On) com gestão de permissões integrada**

[📚 Documentação](#-fluxo-de-autenticação--permissões) • [🚀 Começar](#-como-rodar-o-projeto-localmente) • [❓ FAQ](#-solução-de-problemas-comuns)

</div>

---

## 📖 Visão Geral

Larakey é um projeto **full-stack moderno** que demonstra como integrar um serviço de autenticação centralizado (**Keycloak**) com uma aplicação moderna em **Laravel 13 + Vue.js**.

- 🔐 **Autenticação centralizada** com Keycloak como Identity Provider
- 🔄 **Sincronização automática** de roles e permissões
- ⚡ **Cache inteligente** para performance otimizada
- 🎨 **Interface moderna** com Vue.js 3, Inertia.js e Tailwind CSS
- 🐳 **Totalmente containerizado** com Docker/Podman
- 📐 **Arquitetura escalável** pronta para produção

---

## 🎯 Principais Características

| Feature                               | Descrição                                        |
| ------------------------------------- | ------------------------------------------------ |
| 🔑 **Keycloak como Fonte de Verdade** | Utilizadores e roles geridos centralizadamente   |
| 🔐 **OpenID Connect (OIDC)**          | Comunicação segura entre Laravel e Keycloak      |
| 📦 **Spatie Permission**              | Espelhamento de permissões na BD local com cache |
| 🎪 **Monorepo**                       | Backend, Frontend e temas no mesmo repositório   |
| 🚀 **Zero-Config Deployment**         | Subir com um simples `docker-compose up`         |
| 📊 **Role Management Dashboard**      | Gerenciar acessos através de interface intuitiva |

---

## 🛠️ Stack Tecnológico

<table>
  <tr>
    <th>Camada</th>
    <th>Tecnologia</th>
    <th>Versão</th>
  </tr>
  <tr>
    <td><strong>Backend</strong></td>
    <td>Laravel</td>
    <td>13.x</td>
  </tr>
  <tr>
    <td><strong>Frontend</strong></td>
    <td>Vue.js 3 + Inertia.js + Tailwind CSS</td>
    <td>3.x</td>
  </tr>
  <tr>
    <td><strong>SSO & Identity</strong></td>
    <td>Keycloak</td>
    <td>26.6.1</td>
  </tr>
  <tr>
    <td><strong>Base de Dados</strong></td>
    <td>PostgreSQL</td>
    <td>16+</td>
  </tr>
  <tr>
    <td><strong>Web Server</strong></td>
    <td>Nginx</td>
    <td>Latest</td>
  </tr>
  <tr>
    <td><strong>Orquestração</strong></td>
    <td>Docker/Podman Compose</td>
    <td>Latest</td>
  </tr>
  <tr>
    <td><strong>Permissões</strong></td>
    <td>Spatie Laravel Permission</td>
    <td>Latest</td>
  </tr>
</table>

## 📂 Estrutura do Projeto

```

larakey/
├── 📁 app/ # Aplicação Laravel
│ ├── Http/Controllers/ # Controladores da API
│ ├── Http/Middleware/ # Middleware customizado
│ ├── Http/Requests/ # Form Requests
│ └── Models/ # Modelos Eloquent
│
├── 📁 keycloak/ # Configuração Keycloak
│ └── keycloak-theme/ # Tema customizado com Tailwind
│ ├── src/ # Componentes do tema
│ ├── package.json
│ └── tailwind.config.js
│
├── 📁 podman/ # Configurações Docker/Podman
│ ├── nginx/ # Config Nginx
│ └── php/ # Config PHP-FPM
│
├── 📁 resources/
│ ├── js/ # Componentes Vue.js
│ ├── css/ # Estilos Tailwind
│ └── views/ # Layouts Inertia
│
├── 📁 database/
│ ├── migrations/ # Migrations do banco
│ ├── seeders/ # Seeds (incluindo Roles)
│ └── factories/ # Factories para testes
│
├── 📁 routes/ # Definição de rotas
│ ├── api.php # Rotas da API
│ ├── web.php # Rotas web
│ └── settings.php # Configurações
│
├── 🐳 compose.yaml # Docker Compose
├── 📝 init.sql # Script BD inicial
├── 🔧 .env # Variáveis ambiente
└── 📄 README.md # Este ficheiro

```

## ⚙️ Fluxo de Autenticação & Permissões

### 🔄 O Ciclo Completo

```

┌─────────────────────────────────────────────────────────────────┐
│ │
│ 1️⃣ UTILIZADOR CLICA "LOGIN" │
│ └─► Redirecionado para Keycloak │
│ │
│ 2️⃣ KEYCLOAK AUTENTICA │
│ └─► Utiliza BD PostgreSQL para validar credenciais │
│ └─► Gera JWT Token com Roles │
│ │
│ 3️⃣ CALLBACK PARA LARAVEL │
│ └─► /auth/keycloak/callback recebe token JWT │
│ └─► Extrai roles da claim "realm_access" │
│ │
│ 4️⃣ SINCRONIZAÇÃO DE ROLES (SyncRoles) │
│ └─► Spatie Permission sincroniza roles localmente │
│ └─► Cache invalidado automaticamente │
│ │
│ 5️⃣ UTILIZADOR AUTENTICADO │
│ └─► Acesso à aplicação com permissões aplicadas │
│ │
└─────────────────────────────────────────────────────────────────┘

```

### 🎯 Conceitos-Chave

| Componente            | Função                                           |
| --------------------- | ------------------------------------------------ |
| **Keycloak**          | Fonte única de verdade para utilizadores e roles |
| **Socialite**         | Bridge entre Laravel e Keycloak (OpenID Connect) |
| **JWT Token**         | Contém roles no campo `realm_access`             |
| **Spatie Permission** | Espelha roles do Keycloak na BD local            |
| **Cache DB**          | Mantém permissões sincronizadas e otimizadas     |

---

## 🚀 Como Rodar o Projeto Localmente

### 1. Pré-requisitos

- [Docker](https://www.docker.com/) ou [Podman](https://podman.io/) instalados.
- Domínios locais configurados no seu ficheiro `hosts` (`/etc/hosts` no Linux/Mac ou `C:\Windows\System32\drivers\etc\hosts` no Windows):

```text
127.0.0.1   larakey.test
127.0.0.1   auth.test

```

### 2. Configuração do Ambiente

Clone o repositório e configure o ficheiro `.env` do Laravel:

```bash
cp .env.example .env

```

Certifique-se de que as variáveis de base de dados e do Keycloak estão corretas:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel_db
DB_USERNAME=postgres
DB_PASSWORD=password

# O Cache do Spatie usa o banco de dados
CACHE_STORE=database

# Configurações do Keycloak (Socialite)
KEYCLOAK_CLIENT_ID=laravel-vue-app
KEYCLOAK_CLIENT_SECRET=seu-client-secret-aqui
KEYCLOAK_BASE_URL=http://auth.test:8080
KEYCLOAK_REALM=meu-app-realm
KEYCLOAK_REDIRECT_URI=http://larakey.test/auth/keycloak/callback

```

### 3. Subir a Infraestrutura

Como estamos a usar uma instância unificada de PostgreSQL, o ficheiro `init.sql` criará automaticamente o banco `laravel_db`.

```bash
# Sobe todos os serviços (Nginx, PHP, Postgres, Keycloak e Tailwind Builder)
docker-compose up -d
# ou podman-compose up -d

```

### 4. Instalar Dependências e Migrations

Aceda ao contentor da aplicação Laravel para instalar os pacotes e rodar as migrações:

```bash
docker exec -it laravel_app bash
composer install
npm install
npm run build
php artisan key:generate
php artisan migrate --seed
php artisan permission:cache-reset

```

---

## 🔐 Configuração Obrigatória no Painel do Keycloak

Para que a comunicação entre o Laravel e o Keycloak funcione sem erros de "Invalid redirect uri", é necessário configurar o seu Client (`laravel-vue-app`) no painel do Keycloak (`http://auth.test:8080/admin` | admin / admin):

1. **Client Authentication:** Ative esta opção (ON) para gerar o `Client Secret`.
2. **Valid Redirect URIs:** Adicione `http://larakey.test`
3. **Valid post logout redirect URIs:** Adicione `http://larakey.test`
4. **Mapeamento de Roles no Token:** Configure os _Client Scopes_ > _Roles_ > _Mappers_ para garantir que a claim `realm_access` seja enviada no Token JWT.

---

## 🐛 Solução de Problemas Comuns

- **Erro "Invalid parameter: redirect_uri" no Login/Logout:**
  Verifique as URLs na lista branca do Keycloak. Lembre-se que o Keycloak é sensível a barras no final (Trailing Slashes). Utilize `/*` no final da URL no painel do Keycloak para contornar problemas locais.
- **Permissões não atualizam no Laravel:**
  Rode o comando `php artisan permission:cache-reset` para forçar o Spatie a limpar a tabela de cache no banco de dados.

---

_Desenvolvido com foco em segurança, escalabilidade e boas práticas de autenticação distribuída._

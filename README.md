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
| 🔓 **Login Externo (API)**            | Autenticação via JWT Guard para apps externas    |

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
├── 📁 app/                        # Aplicação Laravel
│   ├── Exceptions/                # Exceções (ex: InvalidApiTokenException)
│   ├── Http/Controllers/          # Controladores (Web, Api e External)
│   ├── Http/Middleware/           # Middlewares customizados
│   ├── Http/Requests/             # Form Requests (Validações)
│   ├── Models/                    # Modelos Eloquent
│   └── Services/                  # Classes de Serviço (Lógica unificada)
│
├── 📁 keycloak/                   # Configuração Keycloak
│   └── keycloak-theme/            # Tema customizado com Tailwind
│       ├── src/                   # Componentes do tema
│       ├── package.json
│       └── tailwind.config.js
│
├── 📁 podman/                     # Configurações Docker/Podman
│   ├── nginx/                     # Configuração Nginx
│   └── php/                       # Configuração PHP-FPM
│
├── 📁 resources/                  # Frontend
│   ├── js/                        # Componentes Vue.js
│   ├── css/                       # Estilos Tailwind
│   └── views/                     # Layouts Blade (Base do Inertia)
│
├── 📁 database/                   # Banco de Dados
│   ├── migrations/                # Migrations do banco
│   ├── seeders/                   # Seeds (incluindo Roles iniciais)
│   └── factories/                 # Factories para testes
│
├── 📁 routes/                     # Definição de Rotas
│   ├── api.php                    # Rotas JSON para o Vue (Sanctum SPA)
│   ├── external.php               # Rotas para integrações (Keycloak Guard)
│   ├── web.php                    # Rotas de renderização de telas (Inertia)
│   └── settings.php               # Configurações do painel/sistema
│
├── 🐳 compose.yaml                # Orquestração de Containers
├── 📝 init.sql                    # Script de BD inicial
├── 🔧 .env                        # Variáveis de ambiente
└── 📄 README.md                   # Documentação do projeto

```

## ⚙️ Fluxo de Autenticação & Permissões

### Dois Modos de Autenticação

O Larakey oferece **dois fluxos de autenticação** complementares:

| Modo                    | Tecnologia                 | Uso Recomendado                          |
| ----------------------- | -------------------------- | ---------------------------------------- |
| **Login via UI**        | Socialite + OpenID Connect | Usuários interagindo com a aplicação web |
| **Login Externo (API)** | Keycloak Guard + JWT       | Aplicações externas e serviços           |

### 🔄 Modo 1: Login via UI (Socialite)

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

### 🔐 Modo 2: Login Externo (API com Guard)

Veja a seção [🔐 Autenticação Externa com Keycloak Guard](#-autenticação-externa-com-keycloak-guard) para detalhes completos.

### 🎯 Conceitos-Chave

| Componente            | Função                                           |
| --------------------- | ------------------------------------------------ |
| **Keycloak**          | Fonte única de verdade para utilizadores e roles |
| **Socialite**         | Bridge entre Laravel e Keycloak (OpenID Connect) |
| **Keycloak Guard**    | Guard JWT para proteger rotas API                |
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

### 5. Testar Login Externo (Opcional)

Após a configuração estar completa, pode testar o login via API:

```bash
# Obter tokens do Keycloak
curl -X POST http://larakey.test/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "demo",
    "password": "demo"
  }'

# Usar o token para acessar rota protegida
curl -X GET http://larakey.test/api/teste-integracao \
  -H "Authorization: Bearer <seu_access_token_aqui>"
```

---

## 🔐 Autenticação Externa com Keycloak Guard

Além da autenticação via UI (Socialite), o projeto agora oferece uma **API de Login Externo** usando o pacote `robsontenorio/laravel-keycloak-guard`.

### 📝 Fluxo de Login Externo

```
┌──────────────────────────────────────────────────────┐
│ 1️⃣ CLIENTE FAZ POST /api/login                      │
│ Credenciais: { username, password }                 │
│                                                       │
│ 2️⃣ KEYCLOAK VALIDA CREDENCIAIS                      │
│ E RETORNA JWT TOKENS                                │
│                                                       │
│ 3️⃣ SINCRONIZAÇÃO LOCAL                              │
│ Usuário criado/atualizado no BD local              │
│ Roles sincronizadas via Spatie Permission           │
│                                                       │
│ 4️⃣ RESPOSTA COM TOKENS                              │
│ { access_token, refresh_token, expires_in }        │
│                                                       │
│ 5️⃣ CLIENTE USA ACCESS_TOKEN                         │
│ Authorization: Bearer <access_token>                │
│ Acessa rotas protegidas pelo Keycloak Guard         │
└──────────────────────────────────────────────────────┘
```

### 🚀 Como Usar a API de Login

#### Obter Tokens

```bash
curl -X POST http://larakey.test/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "seu_usuario",
    "password": "sua_senha"
  }'
```

**Resposta (200 OK):**

```json
{
    "mensagem": "Login bem-sucedido!",
    "access_token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 300
}
```

#### Acessar Rotas Protegidas

```bash
curl -X GET http://larakey.test/api/teste-integracao \
  -H "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Resposta (200 OK):**

```json
{
    "status": "sucesso",
    "mensagem": "Acesso autorizado pelo Keycloak Guard!",
    "dados_do_token": {
        "exp": 1715606400,
        "iat": 1715606100,
        "jti": "abc123...",
        "iss": "http://auth.test:8080/realms/meu-app-realm",
        "sub": "user-id-uuid",
        "typ": "Bearer",
        "azp": "laravel-vue-app",
        "realm_access": {
            "roles": ["admin", "user"]
        }
    }
}
```

### 🔧 Componentes Envolvidos

| Componente                             | Função                                              |
| -------------------------------------- | --------------------------------------------------- |
| `POST /api/login`                      | Endpoint para autenticação com credenciais          |
| `AuthController::loginExternal()`      | Valida input e chama o serviço de autenticação      |
| `KeycloakAuthService`                  | Comunica com Keycloak para obter tokens             |
| `auth:external` middleware             | Protege rotas verificando Bearer token JWT          |
| `robsontenorio/laravel-keycloak-guard` | Guard que valida tokens do Keycloak automaticamente |

### 📋 Proteger Suas Rotas

Para proteger rotas com o Keycloak Guard, adicione o middleware `auth:external`:

```php
Route::middleware('auth:external')->group(function () {
    Route::get('/dados-protegidos', [YourController::class, 'getData']);
    Route::post('/atualizar-perfil', [YourController::class, 'updateProfile']);
});
```

Dentro do controller, acesse os dados do token:

```php
use Illuminate\Support\Facades\Auth;

public function getData()
{
    $tokenPayload = Auth::guard('external')->token();
    $roles = $tokenPayload['realm_access']['roles'] ?? [];

    return response()->json([
        'usuario' => $tokenPayload['preferred_username'],
        'roles' => $roles,
    ]);
}
```

---

## 🔐 Configuração Obrigatória no Painel do Keycloak

Para que a comunicação entre o Laravel e o Keycloak funcione sem erros de "Invalid redirect uri", é necessário configurar o seu Client (`laravel-vue-app`) no painel do Keycloak (`http://auth.test:8080/admin` | admin / admin):

1. **Client Authentication:** Ative esta opção (ON) para gerar o `Client Secret`.
2. **Valid Redirect URIs:** Adicione `http://larakey.test`
3. **Valid post logout redirect URIs:** Adicione `http://larakey.test`
4. **Mapeamento de Roles no Token:** Configure os _Client Scopes_ > _Roles_ > _Mappers_ para garantir que a claim `realm_access` seja enviada no Token JWT.
5. **Access Token Lifespan:** Recomenda-se manter em 5 minutos (300 segundos) para segurança.

---

## 🐛 Solução de Problemas Comuns

### Login Externo (API)

- **Erro "Credenciais inválidas ou acesso negado" (401):**
  Verifique se o utilizador existe no Keycloak com essas credenciais. Certifique-se de que o `client_secret` está correto no arquivo `.env`.

- **Erro "Invalid token" ao usar o Bearer token (401):**
  O token JWT pode ter expirado. Faça login novamente para obter um novo token. A lifespan padrão é 5 minutos.

- **Erro 403 ao acessar rota protegida:**
  A rota está protegida pelo middleware `auth:external` mas você não enviou o token Bearer. Verifique o header `Authorization: Bearer <token>`.

### Login via UI

- **Erro "Invalid parameter: redirect_uri" no Login/Logout:**
  Verifique as URLs na lista branca do Keycloak. Lembre-se que o Keycloak é sensível a barras no final (Trailing Slashes). Utilize `/*` no final da URL no painel do Keycloak para contornar problemas locais.

- **Permissões não atualizam no Laravel:**
  Rode o comando `php artisan permission:cache-reset` para forçar o Spatie a limpar a tabela de cache no banco de dados.

---

_Desenvolvido com foco em segurança, escalabilidade e boas práticas de autenticação distribuída._

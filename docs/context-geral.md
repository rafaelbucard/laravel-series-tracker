# Contexto Geral — Controle de Séries

Documento de referência sobre **o que o sistema faz** e **como o código está organizado**.
Foco no domínio e na arquitetura da aplicação (não em versões de runtime).

---

## 1. Visão geral

Aplicação web para um usuário **rastrear séries, temporadas e episódios assistidos**,
com recursos sociais (fórum/comunidade) e perfis públicos.

Principais capacidades:

- **Catálogo pessoal de séries** por usuário (cada série pertence a um dono).
- **Temporadas e episódios** gerados automaticamente ao criar a série.
- **Marcação de episódios assistidos** com cálculo de progresso (%).
- **Dashboard pessoal**: totais, séries em progresso, concluídas, "continue assistindo" e atividade recente.
- **Serviço de streaming** associado a cada série (Netflix, Prime, Disney+, etc.).
- **Capas de série**: via API OMDB, upload de arquivo ou URL externa.
- **Comunidade/fórum**: tópicos e comentários com 1 nível de respostas (threading).
- **Perfis públicos** de usuário (avatar, bio, séries e estatísticas), com flag de visibilidade.
- **Autenticação completa** (registro, login, reset de senha, confirmação) via Laravel Breeze (Blade).

---

## 2. Arquitetura

O projeto adota uma **camada de serviços (Service Layer)** entre os controllers e os models.
Os controllers ficam finos: validam (via Form Requests), delegam para services e retornam views/redirects.

```
Request → Controller → Form Request (validação) → Service (regra de negócio) → Model/Eloquent → DB
                                                  ↘ Service externo (OMDB) / ImageService (storage)
```

### Camadas

| Camada | Pasta | Responsabilidade |
|--------|-------|------------------|
| Controllers | `app/Http/Controllers/` | Orquestração HTTP, autorização, retorno de views |
| Form Requests | `app/Http/Requests/` | Validação e autorização de entrada |
| Services | `app/Services/` | Regra de negócio, transações, integrações |
| Models | `app/Models/` | Entidades Eloquent e relacionamentos |
| Policies | `app/Policies/` | Autorização por recurso (ownership) |
| Views | `resources/views/` | Blade + componentes + Tailwind |

---

## 3. Domínio e modelos

### Entidades e relacionamentos

```
User 1───* Series *───1 StreamingService
            │
            ├──* Season 1───* Episode (watched: bool)
            │
            └──* Topic 1───* Comment (parent_id → self, 1 nível)

User 1───* Topic
User 1───* Comment
```

### Resumo dos models (`app/Models/`)

- **User** — dono de séries, tópicos e comentários. Campos extras: `avatar_path`, `bio`, `profile_is_public`. Helper `avatarUrl()`.
- **Series** — pertence a `User` e (opcional) `StreamingService`; tem muitas `Season` e (via `hasManyThrough`) muitos `Episode`. Helpers: `coverDisplayUrl()` (prioriza arquivo local sobre URL externa) e `progressPercent()`.
- **Season** — pertence a `Series`; tem muitos `Episode` (ordenados por número).
- **Episode** — pertence a `Season`; campo `watched` (boolean).
- **StreamingService** — catálogo de plataformas (`name`, `slug`, `color`, `logo_path`).
- **Topic** — pertence a `User` e (opcional) `Series`; gera `slug` único automaticamente no `creating`; route key é o `slug`. Relação `rootComments()` traz só comentários raiz.
- **Comment** — pertence a `Topic` e `User`; auto-relacionamento via `parent_id` (`parent`/`replies`).

---

## 4. Schema do banco (resumo)

| Tabela | Colunas principais | Observações |
|--------|--------------------|-------------|
| `users` | name, email (unique), password, avatar_path, bio, profile_is_public (default true) | + sessions e password_reset_tokens |
| `streaming_services` | name, slug (unique), color (#hex), logo_path | populada por seeder |
| `series` | user_id (FK cascade), streaming_service_id (FK nullOnDelete), name, synopsis, year, imdb_id, cover_path, cover_url | índices em user_id e streaming_service_id |
| `seasons` | series_id (FK cascade), number | índice (season_id, number) em episodes |
| `episodes` | season_id (FK cascade), number, watched (default false) | |
| `topics` | user_id (FK cascade), series_id (FK nullOnDelete), title, slug (unique), body | índices em user_id, series_id, created_at |
| `comments` | topic_id (FK cascade), user_id (FK cascade), parent_id (self FK cascade, nullable), body | índice (topic_id, parent_id) |

Drivers padrão: **sessão, cache e fila no banco de dados** (tabelas criadas pelas migrations base).

---

## 5. Services (regra de negócio)

| Service | Função |
|---------|--------|
| `SeriesService` | Cria série dentro de transação, gerando N temporadas × M episódios; trata capa (upload/URL); atualiza e deleta (removendo a capa do storage). |
| `EpisodeService` | `syncWatched()`: marca em massa os episódios de uma temporada como assistidos/não-assistidos a partir de uma lista de IDs (numa transação). |
| `DashboardService` | `statsFor()`: agrega totais, separa séries em progresso/concluídas, calcula "continue assistindo" (próximo episódio não assistido) e atividade recente (últimos episódios marcados). |
| `TopicService` | Lista tópicos paginados com filtros (`series_id`, ordenação por recentes/mais comentados) e cria tópicos. |
| `CommentService` | Cria comentário; mantém threading em **1 nível** (resposta a uma resposta é reanexada ao comentário raiz). |
| `ImageService` | Armazena imagem por upload ou baixando de URL (disk `public`, pasta `covers`); detecta extensão por Content-Type/URL; remove arquivos. |
| `External\OmdbClient` | Integra com a API OMDB: busca por título (`searchByTitle`) e por IMDb ID (`findByImdbId`), com **cache** (1h/1 dia) e degradação graciosa quando não há API key. |

---

## 6. Rotas principais

### Autenticadas (`routes/web.php`, middleware `auth`)

| Método | URI | Ação |
|--------|-----|------|
| GET | `/dashboard` | Dashboard pessoal |
| GET | `/series/omdb/search` | Busca OMDB (JSON) para autocompletar |
| resource | `/series` (exceto `show`) | CRUD de séries |
| GET | `/series/{series}/seasons` | Lista temporadas |
| GET | `/seasons/{season}/episodes` | Lista episódios |
| POST | `/seasons/{season}/episodes` | Atualiza episódios assistidos |
| GET/resource | `/community`, `/community/topics` | Fórum: lista/cria/mostra tópicos |
| POST | `/community/topics/{topic}/comments` | Cria comentário |
| GET | `/users/{user}` | Perfil público |
| GET/PATCH/DELETE | `/profile` | Edição/exclusão do próprio perfil |

`/` redireciona para `dashboard` (logado) ou `login` (visitante).

### Autenticação (`routes/auth.php`)

Registro, login/logout, esqueci a senha, reset de senha e confirmação de senha (padrão Breeze).

---

## 7. Autorização

- **`SeriesPolicy`** (registrada em `AppServiceProvider::boot` via `Gate::policy`): `view`, `update` e `delete` exigem que o usuário seja **dono** da série (`user.id === series.user_id`). `viewAny`/`create` liberados para qualquer autenticado.
- Controllers chamam `$this->authorize('update'|'delete'|'view', $series)`.
- **Perfis públicos**: `PublicProfileController` lança 404 se o perfil não é público e o visitante não é o próprio dono.

---

## 8. Integração OMDB (capas e metadados)

- Configurada em `config/services.php` (chave `omdb.key` / `omdb.base_url`), lida do `.env` (`OMDB_API_KEY`, `OMDB_BASE_URL`).
- `OmdbClient::isConfigured()` controla a UI: sem chave, a busca automática some, mas **upload e URL de capa continuam funcionando**.
- Respostas são cacheadas para reduzir chamadas externas.

---

## 9. Frontend

- **Blade + componentes** em `resources/views/components/` (botões, inputs, modal, dropdown, layouts guest/app, badge de streaming, barra de progresso, card de estatística, capa de série, etc.).
- **Tailwind CSS** (`tailwind.config.js`, `postcss.config.js`) com tema de degradê vermelho → azul.
- **Vite** compila `resources/css/app.css` e `resources/js/app.js`.
  - `@vite([...])` nas views exige **`npm run dev`** rodando (cria `public/hot`) **ou** **`npm run build`** (gera `public/build/manifest.json`).

---

## 10. Testes

Suíte em **Pest** (`tests/`), cobrindo:

- `Feature/Auth/AuthenticationTest` — login/registro.
- `Feature/SeriesTest` — CRUD e autorização entre usuários.
- `Feature/EpisodeWatchTest` — marcação de episódios.
- `Feature/DashboardTest` — agregações do dashboard.
- `Feature/TopicTest` e `Feature/CommentTest` — fórum e threading.
- `Feature/PublicProfileTest` — visibilidade de perfis.
- `Unit/OmdbClientTest` — cliente OMDB (com HTTP fake).

Factories correspondentes em `database/factories/`.

---

## 11. Mapa rápido de pastas

```
app/
├── Http/
│   ├── Controllers/        # Auth/, Community/, Dashboard, Series, Seasons, Episodes, Profile, PublicProfile
│   └── Requests/           # Validação: Series, Topic, Comment, Profile, Auth/Login
├── Models/                 # User, Series, Season, Episode, StreamingService, Topic, Comment
├── Policies/               # SeriesPolicy (ownership)
├── Providers/              # AppServiceProvider (Gate::policy, Vite::useBuildDirectory)
└── Services/               # SeriesService, EpisodeService, DashboardService,
                            # TopicService, CommentService, ImageService, External/OmdbClient
database/
├── migrations/             # streaming_services, series, seasons, episodes, topics, comments (+ base)
├── factories/              # uma por model de domínio
└── seeders/                # StreamingServiceSeeder (8 plataformas)
resources/views/            # auth/, community/, profile/, series/, seasons/, episodes/, users/, components/
routes/                     # web.php (app) + auth.php (Breeze)
tests/                      # Feature/ (Pest) + Unit/
```

---

## 12. Fluxos de uso (resumo)

1. **Cadastrar série** → escolhe streaming, informa nº de temporadas/episódios → o `SeriesService` cria tudo numa transação → capa via OMDB/upload/URL.
2. **Assistir** → abre temporada → marca episódios → `EpisodeService.syncWatched` atualiza em massa → progresso recalculado.
3. **Acompanhar** → `Dashboard` mostra progresso, "continue assistindo" (próximo episódio) e atividade recente.
4. **Comunidade** → cria tópico (opcionalmente ligado a uma série) → outros comentam/respondem (1 nível).
5. **Perfil público** → outros usuários veem séries e estatísticas, se o perfil estiver público.

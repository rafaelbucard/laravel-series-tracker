# Controle de Séries v2

Aplicação Laravel 12 (PHP 8.2+) para rastrear séries, temporadas e episódios assistidos, com:

- **Dashboard pessoal** com progresso, "continue assistindo" e atividade recente
- **Serviço de streaming** por série (Netflix, Prime, Disney+, Max, etc.)
- **Capas de série** via OMDB API ou upload próprio / URL externa
- **Comunidade / fórum** com tópicos e comentários (1 nível de threading)
- **Perfis públicos** de usuário com avatar, bio e suas séries
- **Service Layer** dedicado em `app/Services/`
- **Tema visual** com degradê vermelho → azul

Stack: Laravel 12, Breeze (Blade), Tailwind 3, Vite, Pest 3, MySQL 8 (via Sail).

## Setup com Laravel Sail (Docker)

```bash
# 1) Suba o daemon do Docker (se ainda não estiver)
sudo service docker start

# 2) Instale as dependências do Composer com um container temporário
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# 3) Suba os containers do Sail
./vendor/bin/sail up -d

# 4) Gere uma APP_KEY (caso esteja vazia)
./vendor/bin/sail artisan key:generate

# 5) Crie o symlink do storage para uploads de imagens
./vendor/bin/sail artisan storage:link

# 6) Rode as migrations e seed do StreamingServiceSeeder
./vendor/bin/sail artisan migrate:fresh --seed

# 7) Instale deps de frontend e compile os assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev   # ou: npm run build
```

Acesse: `http://localhost`

## OMDB (capas de séries)

A busca automática de capas usa a API gratuita do OMDB. Crie uma chave em
[omdbapi.com/apikey.aspx](https://www.omdbapi.com/apikey.aspx) e coloque no `.env`:

```
OMDB_API_KEY=sua-chave
```

Sem chave configurada, ainda é possível subir capa por upload ou colando uma URL pública.

## Estrutura do código

```
app/
├── Http/
│   ├── Controllers/         # Inclui Auth/, Community/, PublicProfileController, DashboardController
│   ├── Requests/            # Form requests para Series, Topic, Comment, Profile
│   └── Middleware/          # (Laravel 12 default)
├── Models/                  # User, Series, Season, Episode, StreamingService, Topic, Comment
├── Policies/                # SeriesPolicy (multi-usuário)
└── Services/                # SeriesService, EpisodeService, DashboardService,
    ├── External/            # OmdbClient
    └── ...                  # TopicService, CommentService, ImageService
```

## Comandos úteis

```bash
sail artisan migrate:fresh --seed   # resetar banco
sail artisan tinker                 # console
sail artisan test                   # pest
sail npm run dev                    # vite (HMR)
sail npm run build                  # build produção
./vendor/bin/pint                   # lint PHP
```

## Testes

```bash
./vendor/bin/sail test
```

Cobertura: criação/listagem/edição/exclusão de séries, autorização entre usuários, marcação de episódios,
dashboard, tópicos da comunidade, comentários (threading), perfis públicos, e cliente OMDB.

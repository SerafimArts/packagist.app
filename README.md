# [WIP] Alternative Self-Hosted Packagist

An alternative self-hosted Composer Server (packagist like).

## Requirements

- PHP 8.4+ (>=8.4.0beta4)
- Postgres 16.3+

## Installation

- `cp .env.example .env` (not required, but recommended)
- `docker compose up --build -d`

### Migrations

Migration is required to update the database structure.

- `docker exec -it packagist-php composer db:up`

### Fixtures

Fill the database with random test data.

- `docker exec -it packagist-php composer db:fill`

## Usage

- You can change public HTTP port using `APP_PUBLIC_PORT`
  env variable (80 by default).

- You can change public Postgres port using `APP_DATABASE_PUBLIC_PORT`
  env variable (5432 by default).

### API

- HTTP: `http://packagist.localhost`

To interact with the API, you can use the [Postman](https://www.postman.com/) 
resources located in [resources/postman.json](/resources/postman.json).

### Database

**Docker Credentials:**

| Description | Value       | Environment Variable |
|-------------|-------------|----------------------|
| Host        | `postgres`  | `DB_HOST`            |
| Port        | `5432`      | `DB_PORT`            |
| Login       | `user`      | `DB_USERNAME`        |
| Password    | `password`  | `DB_PASSWORD`        |
| Database    | `packagist` | `DB_DATABASE`        |

If you change the `DB_HOST`, you also need to change `postgres:` the section
name in the [`docker-compose.yml`](./docker-compose.yml) file.


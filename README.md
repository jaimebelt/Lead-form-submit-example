# Technical Test - Sr PHP Developer

*Objective:* Build a simple Lead Management System where users can submit leads through a form. Each new lead should be stored in a database and automatically notify an external system about the new lead. Additionally, implement a frontend using Angular 13 for lead submission and display.

Project is built using Docker and Docker Compose and uses the following technologies:

- PHP 8.1
- MySQL 8.0
- Angular 13 (with Angular Material)
- Docker
- Docker Compose
- Slim Framework 4

## Project Structure

```
project/
├── .scripts/ # Git hooks
├── .docker/ # Docker configuration files
├── api/ # Slim Framework Backend
├── frontend/ # Angular Frontend
├── docker-compose.yml
├── .env.example
├── setup-hooks.sh
└── README.md

```

## Prerequisites

- Docker
- Docker Compose

## Setup and Installation

1. Clone the repository:

```bash
git clone git@github.com:jaimebelt/Lead-form-submit-example.git
```

2. Create a `.env` file in the root directory and set the environment variables:

```bash
cp .env.example .env
```

3. Start the Docker containers (if you already install the project, please omit next steps project must running):

```bash
docker compose up -d
```
> **Important:** If first time usage, It can take sometime until FE packages are installed and server start running. Check docker logs  (See Useful commands section)   

> **Note:**  Follow next steps if first time usage

4. Install API dependencies:

```bash
docker compose exec api composer install
```

5. Run migrations (this will create the necessary tables in DB):

```bash
docker compose exec api vendor/bin/doctrine-migrations migrate
```

6. Troubleshooting: restart containers if not rendering the app in `http://localhost:4200`

```bash
docker compose restart api frontend db
```

## Services and Ports

- Frontend (Angular): http://localhost:4200
- Backend (Slim API): http://localhost:8080
- MySQL Database: localhost:3306

## Development

### Backend API (Slim Framework)
The API is built using Slim Framework 4 and follows PSR-4 autoloading standards. All API endpoints are available under `http://localhost:8080/api/`.

### Frontend (Angular)
The frontend is developed using Angular 13 with Angular Material components. Development server runs on `http://localhost:4200`.


### Migrations
Migrations are handled using Doctrine Migrations. To create a new migration, run:

```bash
docker compose exec api vendor/bin/doctrine-migrations generate
```

To run migrations:

```bash
docker compose exec api vendor/bin/doctrine-migrations migrate
```

## Code Quality Tools

### PHP_CodeSniffer
PHPCS is used to ensure code follows PSR-12 coding standards. To run the code style checker:

```bash
docker compose exec api vendor/bin/phpcs
```

To automatically fix code style issues:

```bash
docker compose exec api vendor/bin/phpcbf
```

### PHPStan
PHPStan performs static analysis to find potential bugs. To run PHPStan:

```bash
docker compose exec api vendor/bin/phpstan analyse
```

The analysis is set to level 8 (highest) in `phpstan.neon`. Adjust the level in the configuration file if needed.

## Git Hooks

The project includes a pre-commit hook that runs code quality checks before each commit. The hook:
- Runs PHP_CodeSniffer to check coding standards
- Runs PHPStan for static analysis

If any check fails, the commit will be aborted. Fix the reported issues and try committing again.

To skip the pre-commit hook in exceptional cases (not recommended), use:
```bash
git commit -m "Your message" --no-verify
```

### Setting up Git Hooks
To install the Git hooks:

```bash
chmod +x setup-hooks.sh
./setup-hooks.sh
```

## Useful commands

Backend Logs:

```bash
docker compose logs api
```

Frontend Logs:

```bash
docker  compose logs frontend
```

Composer dump-autoload:

```bash
docker compose exec api composer dump-autoload
```

Composer require:

```bash
docker compose exec api composer require <package>
```

Restart the Docker containers:

```bash
docker compose down
docker compose up -d
```

Reload API env variables:

```bash
docker compose restart api frontend db
```




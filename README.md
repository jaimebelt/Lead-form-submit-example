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
│ ├── apache/
│ ├── php/
│ └── mysql/ # MySQL configuration
├── api/ # Slim Framework Backend
│ ├── public/
│ ├── src/
│ └── composer.json
├── frontend/ # Angular Frontend
│ ├── src/
│ └── package.json
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
git clone https://github.com/yourusername/full-stack-app.git
```

2. Create a `.env` file in the root directory and set the environment variables:

```bash
cp .env.example .env
```

3. Start the Docker containers:

```bash
docker compose up -d
```

4. Install API dependencies:

```bash
docker compose exec api composer install
```

5. Install frontend dependencies:

```bash
docker compose exec frontend npm install
```

6. Run migrations:

```bash
docker compose exec api vendor/bin/doctrine-migrations migrate
```

7. Start the development server:

```bash
docker compose up -d
```

## Services and Ports

- Frontend (Angular): http://localhost:4200
- Backend (Slim API): http://localhost:8080
- MySQL Database: localhost:3306
- PHPMyAdmin: http://localhost:8081

## Development

### Backend API (Slim Framework)
The API is built using Slim Framework 4 and follows PSR-4 autoloading standards. All API endpoints are available under `http://localhost:8080/api/`.

### Frontend (Angular)
The frontend is developed using Angular 13 with Angular Material components. Development server runs on `http://localhost:4200`.

### Database
MySQL database is accessible on port 3306. Database management can be done through PHPMyAdmin interface at `http://localhost:8081`.

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

Reaload API env variables:

```bash
docker compose restart api frontend db
```



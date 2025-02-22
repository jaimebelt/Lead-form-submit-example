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
├── api/ # Slim Framework Backend
│ ├── public/
│ ├── src/
│ └── composer.json
├── frontend/ # Angular Frontend
│ ├── src/
│ └── package.json
├── .docker/ # Docker configuration files
│ ├── apache/
│ ├── php/
│ └── mysql/ # MySQL configuration
├── docker-compose.yml
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
docker-compose up -d
```


3. Install API dependencies:

```bash
docker-compose exec api composer install
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



# TingisCRM

This is laravel version 10 project.
This a test assignment for tinigs web agency.

## Prerequisites

Before you begin, ensure you have the following:

Docker installed on your system. You can download and install Docker from Docker's official website.
Basic understanding of Docker and Docker Compose. Familiarize yourself with Docker concepts such as containers, images, Dockerfiles, and Docker Compose for managing multi-container Docker applications.
These prerequisites are essential for setting up and running your Laravel project using Docker containers effectively. If you're new to Docker, consider reviewing Docker's documentation or tutorials to get started.

## Installation


1. start Docker Desktop:
   Ensure Docker Desktop is running on your system.

2. Clone the repository: git clone https://github.com/yussef12/crm_api.git
   `cd crm_api`

3. Copy `.env.example to ` `.env` and configure your environment variables.
4. run `docker-compose up -d`

5. Generate key:
   execute the command : ` docker-compose exec php artisan key:generate`

6. Migrate tables:
   execute the command : `docker-compose exec php artisan migrate`

7. seed Data:
   execute the command : `docker-compose exec php artisan db:seed`

7. To set up the JWT secret key required for authentication, run the following command from your project's root directory `docker-compose exec app php artisan jwt:secret`

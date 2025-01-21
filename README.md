# Laravel Project Setup with Docker

This guide provides step-by-step instructions to clone and set up the Laravel project using Docker.

## Prerequisites

Ensure you have the following installed on your system:

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Set Up Environment Variables

Copy the example `.env` file to create your environment configuration:

```bash
cp .env.example .env
```

### 3. Start Docker Containers

Run the following command to build and start the containers in detached mode:

```bash
docker compose up -d
```

### 4. Set Permissions

Ensure the necessary directories have proper permissions:

```bash
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
sudo chmod -R 775 storage
```

### 5. Access the Container

Get inside the Docker container:

```bash
docker exec -it jobtest bash
```

### 6. Install Dependencies

Inside the container, run the following commands:

```bash
composer install
php artisan migrate
```

---

## Additional Notes

-   Ensure that Docker is running on your system before executing these commands.
-   Access the application by navigating to `http://localhost:8004` in your browser.
-   Modify the `.env` file as needed for your local database, mail, or other configuration.

---

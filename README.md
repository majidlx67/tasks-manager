# Task Management System

A web-based task management system built with Laravel 12, Inertia.js, Vue 3, TypeScript, Tailwind CSS, and Shadcn-vue. Users can register, log in, and manage tasks and subtasks with a modern UI.

## Prerequisites
- PHP >= 8.3
- Composer
- Node.js >= 20
- Docker (optional for containerized setup)
- MySQL

## Installation
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd tasks-manager
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install Node.js dependencies:
   ```bash
   npm install
   ```
4. Copy `.env.example` to `.env` and configure your database:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Run migrations:
   ```bash
   php artisan migrate
   ```
7. Build frontend assets:
   ```bash
   npm run build
   ```
8. Start the development server:
   ```bash
   php artisan serve
   npm run dev
   ```

## Docker Setup
1. Ensure Docker and Docker Compose are installed.
2. Run the following command to start the services:
   ```bash
   docker-compose up -d
   ```
3. Access the app at `http://localhost:8000`.
4. Run migrations inside the container:
   ```bash
   docker-compose exec app php artisan migrate
   ```

## Running Tests
1. Ensure the test database is configured in `.env.testing`.
2. Run tests:
   ```bash
   php artisan test
   ```
   For running task tests:
   ```bash
   php artisan test --filter TaskTest
   ```

## Usage
- **Register/Login**: Create an account or log in to manage tasks.
- **Task Management**: Create, edit, delete tasks, and filter by status.
- **Subtasks**: Add subtasks inline, mark them as done/pending, and the parent task status updates automatically.
- **UI**: Uses Shadcn-vue for modals and toasts, styled with Tailwind CSS.

## Additional Notes
- The project has a clean folder structure.
- TypeScript ensures type safety in the frontend.
- Inertia.js handles server-side rendering and client-side navigation.


## .env.example
```
APP_NAME=TasksManager
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tasks_manager
DB_USERNAME=root
DB_PASSWORD=password
```

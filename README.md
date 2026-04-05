# Task Manager
A task management web application built with Symfony 6/7 and MySQL/SQLite.

### Features
- User Authentication: Registration, login and logout
- Personal Task Management: Each user only sees and handles their own tasks
- Full CRUD operations: Create, read, update and delete tasks
- Task fields: Title, description, deadline status and priority
- Dashboard: Quickly view urgent, open and high priority tasks

### Tech stack
- Backend: Symfony 6/7 (PHP 8.1+)
- Database: SQLite (development) / MySQL (production ready)
- ORM: Doctrine
- Templating: Twig
- Frontend: HTML5, CSS3 (custom)
- Build tools: Symfony CLI, Composer, Maker Bundle
- Version Control: Git

### Prerequisites
- PHP 8.1 or higher
- Composer
- Symfony CLI (recommended)
- SQLite (or MySQL)

### Instalation
1. Clone the repository:\
git clone https://github.com/AnneGretheBakker/Task-Manager.git \
cd Task-Manager

2. Install dependencies:\
composer install

3. Set up database (SQLite by default):\
php bin/console doctrine:database:create\
php bin/console doctrine:migrations:migrate

4. Start the Symfony server:\
symfony server:start

5. Go to localhost:8000

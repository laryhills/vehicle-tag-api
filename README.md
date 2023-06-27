# Vehicle Tagging System API

This is a vehicle tagging system that will be used to tag vehicles that are entering and leaving the premises of a company using qr codes.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
 php composer
 mysql database server
```

### Installing

A step by step series of examples that tell you how to get a development env running

Copy the .env.example file to .env

```
cp env.example .env
```

Generate the application key

```
php artisan key:generate
```

Create a database and update the .env file with the database credentials

```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Install the dependencies

```
composer install
```

Run the migrations

```
php artisan migrate
```

Run the seeder

```
php artisan db:seed
```

Run the application

```
php artisan serve
```

## Running the application

The application will be running on http://localhost:8000

Application login credentials

```
email: "superadmin@example.com"
password: "password"
```

##API Endpoints

### Authentication

#### Login

```
POST /api/v1/login
```

#### Logout

```
GET /api/v1/logout
```

### Tags

```
GET /api/v1/tags - Get all tags
```

```
POST /api/v1/tags - Create a new tag
```

```
GET /api/v1/tags/{id} - Get a tag by id
```

```
PUT /api/v1/tags/{id} - Update a tag by id
```

```
GET /api/v1/tags/search/{tagNo} - Get Tag by id
```

```
DELETE /api/v1/tags/{id} - Delete a tag by id
```

### Admin Users

```
GET /api/v1/logs - Get all logs
```

```
GET /api/v1/users - Get all admin users
```

```
GET /api/v1/users/{id} - Get a user by id
```

```
POST /api/v1/register - Create register
```

## Built With

-   [Laravel](https://laravel.com) - The web framework used
-   [Composer](https://getcomposer.org/) - Dependency Management
-   [MySQL](https://www.mysql.com/) - Database

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

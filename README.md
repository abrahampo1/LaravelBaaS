
# Laravel BaaS

A Laravel Backend as a Service




## Requirements

In order to install Laravel, you need Composer, PHP and a database on your machine.

Composer
https://getcomposer.org/download/
    
PHP
https://www.php.net/downloads

MySQL
https://dev.mysql.com/downloads/installer/
## Run Locally

Clone the project

```bash
  git clone https://git.asoft.es/abraham/LaravelBaaS
```

Go to the project directory

```bash
  cd LaravelBaaS
```

Install dependencies

```bash
  php composer install
```

Run the migrations

```bash
  php artisan migrate
```

Start the server

```bash
  php artisan serve
```


## Environment Variables

To run this project, you will need to add the following environment variables to your .env file in order to migrate the database

`DB_HOST`
`DB_PORT`
`DB_USERNAME`
`DB_PASSWORD`


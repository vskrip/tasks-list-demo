# Task List

This is a simple Laravel Web Application

[Live Demo](http://nameless-fjord-49042.herokuapp.com/)

## Prerequisite

You shoud have installed next application on your system:

-   PHP 7.3
-   MySQL Server
-   composer
-   node.js with npm
-   unzip

I recommend to use next web development solutions: [MAMP](https://www.mamp.info/en/mac/) for MacOS kind of operation systems and [XAMP](https://www.apachefriends.org/index.html) for Windows kind of operation systems.

## Deploing

The Application deploing as a zipped archive file: `tasks.zip`

## Installation

Open a terminal console in the folder with archive file `tasks.zip` and invoke the command:

```
$ unzip tasks.zip
```

Step into unziped archive folder:

```
$ cd ./tasks
```

Install dependencies with composer:

```
$ composer install
```

Create database on the MySQL Server

Open file `.env` in the root folder of the project with any text editon and update rows with credentials for access to created database:

```
DB_CONNECTION=mysql
DB_HOST=192.168.10.10
DB_PORT=3306
DB_DATABASE=tasks-db
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Migrate tables to the database by command:

```
$ php artisan migrate
```

## Running

Run development server

```
$ php artisan serve
```

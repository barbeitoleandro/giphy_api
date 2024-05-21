# Project Name

Giphy API

----------
# Getting started

You can found the Postman Collection and the documentation in this shared folder:

https://drive.google.com/drive/folders/1BYfXyGk8wEgjG-UHhCe2L6jVybliiAAz?usp=sharing


## Installation

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Build & run docker-composer images (**Verify you have already shared volumes folders**)

    docker-compose up

Install all the dependencies using composer

    docker exec giphy-app composer install

Generate a new application key

    docker exec giphy-app php artisan key:generate

Run the database migration (**Set the database connection in .env before migrating**)

    docker exec giphy-app php artisan migrate



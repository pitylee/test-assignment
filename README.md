# Test Assignment

This is a test assignment in Laravel with PHP 8.0 that can be run in a local environment included in this repository.

## Before we begin

It is assumed that you have a working setup with the latest git, docker and docker-compose, you can run it locally.

Other than that, you can make use of the ./web directory and run the project without docker in your preferred environment from ./web/public as usual for Laravel projects.


## Cloning the repository

Just use your preferred gui or even better:

Always make sure you are in your destination directory

`cd /path_to_root/`

`git clone https://github.com/pitylee/test-assignment`

## Create a .env file or rename the .env.example

For easier setup, there is a .env.example file that can be used as example or renamed to .env:

Always make sure you are in your destination directory

`cd /path_to_root/`

Than use cp or mv to copy or rename the example file used.

First for the docker environment: 

`mv .env.example .env`

Than for the Laravel:

`
cd ./web/; mv .env.example .env
`

***!Note:** As a security measure it is good practice to only include example files for the critical parts, containing passwords, this is why only .env.example files are present in the repository.*


## Tweaking

### Environment

If you have preferences on port settings or mysql settings, see the .env file in the root directory of the repository.

***!Note:** Do not forget that the host names have to be be the docker container name so that the containers can communicate between them. The default pattern for the container names used in docker.compose.yml follows the pattern test-neurony-{name}!*


### Laravel

After this, you need to set the mysql settings in the final file to correspond to your root .env - don't forget that the root env file is there for the environment itself whilst the web .env is for the Laravel.


***!Note:** Make sure that the /.env and /web/.env are in sync!*



### Defaults

Here is the example of ports that can be used used:

| Server     | Default  | Ext    |
|------------|----------|--------|
| MySQL      |  3306    | -   |
| PHPMyAdmin |  8000    | 8099   |
| Nginx      |  80      | 8098   |
| Nginx SSL  |  443     | 4439   |

## Docker network

It is advised to create a network for each docker bundle, so that they don't get mixed up:

`docker network create test-neurony`



## Running in a local environment

When you are ready with all the above, you simply start up the project with docker compose.

Always make sure you are in your destination directory

`cd /path_to_root/`

Than the compose up daemonized will run it in the background:

`docker-compose up -d`

If the above will be failing, check your .env file for versions and try to build again with:

`docker-compose build`

***!Note:** Starting for the first time will pull the images that are not cached and it may take some time depending of your connection speed!*

## Test

Whenever you want to open the sites, you can use these links:

[Site on http](http://localhost)
[PHPMyAdmin](http://localhost:8000)

## Laravel first start

- Run `docker-compose exec -it php composer install` or `docker-compose exec -it php php composer.phar install`
- Run `docker-compose exec -it php php artisan key:generate`
- Run `docker-compose exec -it php php artisan migrate` to migrate
- Run `docker-compose exec -it php php artisan db:seed` to run seedersy.
- Run `docker-compose exec -it php php artisan storage:link` to run seedersy.

## Frontend

Run `docker-compose run --rm frontend` to install npm dependencies and compile the frontend than remove temporary container.

You can also run `docker-compose run --rm frontend npm run watch` after you ran the above and have the frontend setup, if you want to make changes to the project.

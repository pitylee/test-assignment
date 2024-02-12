# Test Assignment

This is a test assignment in Laravel with PHP 8.0 that can be run in a local environment included in this repository.

<img src="demo.gif" alt="demo" style="width:800px;"/>

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

| Server             | Default  | Example |
|--------------------|----------|---------|
| MySQL              |  3306    | -       |
| PHPMyAdmin         |  8000    | 8099    |
| PHP                |  9000    | 9000    |
| XDebug             |  9003    | 9003    |
| Nginx              |  80      | 8098    |
| Nginx SSL          |  443     | 4439    |
| Hot                |  8080    | 8899    |
| browsersync        |  3000    | 8089    |

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

***!Note:** Starting for the first time will pull the images that are not cached, and creating the database and it may take some time depending of your connection speed!*

## Test

Whenever you want to open the sites, you can use these links:

[Site on http](http://localhost)
[PHPMyAdmin](http://localhost:8000)

## Laravel first start

- Run `docker-compose exec -it php composer install` or `docker-compose exec -it php php composer.phar install`
- Run `docker-compose exec -it php php artisan key:generate`
- Run `docker-compose exec -it php php artisan migrate` to migrate
- Run `docker-compose exec -it php php artisan db:seed` to run seedersy.
- Run `docker-compose exec -it php php artisan storage:link` to make storage link (once).

## Frontend

Run `docker-compose run --rm frontend` to install npm dependencies and compile the frontend than remove temporary container by default (without command and on up).

You can also run `docker-compose run --rm --service-ports frontend npm run hot` after you ran the above and have the frontend setup, if you want to make changes to the project.

It is possible running eslint with `docker-compose run --rm frontend npm run eslint`.

***!Note:** It is a must that you run either with npm run hot or without so that files get compiled to your env!*

<span style="color:red;font-style:italic;">**!Important:**</span>
*Always provide --rm and --service-ports so that the temporary frontend container will be removed and it can communicate through service ports!*

## PHPMyAdmin

When connecting to pma you will have to use `mysql` for the server as it will connect to the mysql container.

You can log in with the users set in the .env.

## PHP

PHPCBF can be run upon development for beautifying the code with the command:

`docker-compose exec -it php composer run-script phpcbf`

Debugging with Xdebug can be done in the IDE that you love working with, by default xdebug 3 for php 8, using your
internal ip, yet this can be tweaked around to follow host, or use xdebug's new gateway host resolver.

Make sure your ide is listening on the configured port with telnet or netcat:

`
telnet IP 9003
`
or
`
nc -vz IP 9003
`

***!Note:** See the xdebug ini file, you can rename the given example, adjust the configuration configuration, and consult your ide extension documentation for how to configure it!*


## Commands

It is also possible to run any kind of command with the patterns:

- `docker-compose run --rm --service-ports frontend {command}`

> Here, --rm will remove the temporary container and --service-ports will assign the ports defined to the service to this temporary container for the docker run command.

- `docker-compose exec -it php {command}`

> execute any kind of command in a container, like php here

- `docker-compose logs {container}`

> empty all the logs

- `docker-compose run --rm --service-ports frontend npm run eslintfix`

> Run eslint fix

- `docker-compose exec -it php composer run-script phpcbf`

> PHPCBF with fix

### Examples

- `docker-compose exec -it php php -i` - php info *(Note the php twice will be: {service} {php_executable}*
- `docker-compose run --rm --service-ports frontend rm -rf node_modules package-lock.json` - if you want to remove node dependencies for a clean install
- `docker-compose run --rm --service-ports frontend npm install {packages} --save-dev --legacy-peer-deps` - as you would run npm install normally
- `docker-compose logs web` - if you want to see container logs generated by web container (different from nginx log)
- `docker-compose exec -it php find /var/log -type f -name "*.log" -exec sh -c '> {}' \;` - to empty all log files in /var/log


***!Note:** You can run any kind of command you would normally run in a unix terminal!*


## Bugs

### 1. Legacy Vue

As the project uses legacy vue, requires specific versions as seen below:


| Package                       | Version  |
|-------------------------------|----------|
| vue                           |  ~2.6.12 |
| vue-loader                    |  ~15.9.5 |
| ...see original package.json  |          |

If you encounter problems upon compiling with npm, for example if you updated packages, make sure that you have the versions needed, e.g.:

`docker-compose run --rm --service-ports frontend npm install vue-template-compiler vue-loader@~15.9.5 --save-dev --legacy-peer-deps`

### 2. Mix assets

Because the project is containerized the laravel mix assets-, and the site be proxied through browsersync a bit differently.
Port 8080 and 3000 will be exposed and will refresh the components, respectively will refresh the site in your browser.

In order to make this work, the webpack will run 0.0.0.0 (all hosts accepted) with port 8080:

- Want to run `npm run hot` with parameterized poll and network (see package.json)
- Define MIX_ASSET_HOT_PROXY_URL in .env

### 3. Hot port allocation

`failed: port is already allocated`

By default the frontend service will run npm install and npm run dev on it's entrypoint.

Now, because the ports are allocated to the service itself, sometimes it gets stuck and the docker run environment can not assign the ports.

To solve this we have to take down the mothership and run the command we wanted:

`
docker-compose kill frontend
docker-compose run --rm --service-ports frontend npm run prod
`


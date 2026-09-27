# Distribution & Enterprise Software - Housing 4 Raajje
A Maldives housing and leasing system developed for the Distribution and Enterprise Software Development Module.<br>
Developed by Awf, Naaih, Midhuha and Hisan.

### Stack
- Docker
- Laravel 13.33.0
- PHP 8.5
- MySQL 8.0
- Bootstrap

---

## Prerequisites
Firstly, remember to install Docker Desktop. We will be using it to setup the environment. You won't need to install any of the dependencies manually as Docker will install the necessary packages, but you may need to install Composer (see "Environment Setup" below). After installing Docker Desktop, clone the Github repo to your local computer. You may use Git or GitHub Desktop, either is fine. If you are using Git, then type this in the command prompt to clone.

```bash
https://github.com/Awfyboy/Housing-Raajje.git
```
You should now see the GitHub repo cloned to your local machine.

---

## Environment Setup
Now we need to compile the project to Docker. Before compiling, remember to setup the __".env"__ file if you haven't. Create a copy of __".env.example"__ file and name it __".env"__. Then, uncomment the DB related variables in __".env"__ and update them to the following:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=housing_raajje
DB_USERNAME=laravel
DB_PASSWORD=password
```

You will notice that __APP_KEY__ in __".env"__ is empty. We need to generate the key, do this by running the following codes.

```bash
docker-compose up -d --build
docker-compose exec app bash
composer install
php artisan key:generate
```

This will build/rebuild your docker containers and also generate the __APP_KEY__ necessary for development in local environment. Finally let's make migrations and generate seeders. We do this whenever the database structure changes, like when we add a new model. You will also need to do this on initial setup.

```bash
php artisan migrate:fresh --seed
```

That should be it. Now if you visit http://localhost:8000, you should see the app running.

---

## Common CMD Commands
These are common commands you would use during the development of the project. These commands should be run in CMD (or Bash/Powershell).
```bash
# Builds/rebuilds the docker containers and turns them on
# Only needs to be run on initial cloning or when dependencies change
docker-compose up -d --build

# prints all containers that are running
# you should see 3 containers (housing-raajje; mysql; phpmyadmin)
docker ps

# opens the docker-compose terminal
# ALL php commands MUST be run in this bash (see "Common Docker-Compose Commands" below)
docker-compose exec app bash

# check the current branch
git branch

# switch to a different branch
git checkout <branch-name>

# add all changed files
git add .

# list all new files or modified files that haven't been committed
git status

# add a new commit with message
git commit -m "<message>"

# push branch to repo
git push origin <branch-name>

# pull changes from a branch
git pull origin <branch-name>
```

---

## Common Docker-Compose Commands
These commands should be run in the Docker-Compose bash *(NOT CMD)*. When you enter the Docker bash, your terminal will show 'root@some-id:/var/www/html#' instead of your project directory. This is where you type the php artisan commands below. You can type 'exit' to go back to your terminal (whether it is CMD/bash/powershell)
```bash
# generate APP KEY for local environment
# only run when you first clone the project or if .env file is missing APP_KEY
php artisan key:generate

# migrate any changes when a model is added
# good to run when testing a new branch, or if a branch has changes made
php artisan migrate:fresh --seed

# rollback migrations
# good to run after testing a branch with different models and switching back to a different branch.
php artisan migrate:rollback

# exit and return to your original terminal
exit

# (optional) run the Laravel project
# there shouldn't really be a need to run this (since Docker should run the project on its own anyways)
# but if localhost isn't working for some reason, try running this
php artisan serve
```

---

## Commit standard
When creating commits, follow the given standard. It will make reading the GitHub history easier.

These are the commit types:
- feat -> adding a new feature
- fix -> fixing a bug or issue
- refactor -> bringing a change to an existing feature
- style -> formatting, appearance, visuals (usually for CSS or HTML)
- chore -> other stuff, like build process, dependancy updates, update readme, etc.

```bash
<type>: <subject>

eg -> feat: add migration and model for leasing
   -> fix: fix login validation
   -> refactor: allow user to select multiple search tags instead of only one
   -> style: update background color
   -> chore: update readme
```

---

### Links for testing
- Laravel app: http://localhost:8000
- phpMyAdmin (if needed): http://localhost:8080
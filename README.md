# Sysdev project - Tropica
A simple tourism island project developed for the System Development Group Project.<br>
Developed by Awf, Naaih and Hisan.

### Stack
- Docker
- Laravel 13.17.0
- PHP 8.5
- MySQL 8.0
- Bootstrap

---

## Prerequisites
Firstly, remember to install Docker Desktop. We will be using it to setup the environment. You won't need to install any of the dependencies manually as Docker will install the necessary packages. After installing Docker Desktop, clone the Github repo to your local computer. You may use Git or GitHub Desktop, either is fine. If you are using Git, then type this in the command prompt to clone.

```bash
https://github.com/Awfyboy/Housing-Raajje.git
```
You should now see the GitHub repo is cloned to your local machine.

---

## Environment Setup
Now we need to compile the project to Docker. Before compiling, remember to setup the __".env"__ file if you haven't. Create a copy of __".env.example"__ file and name it __".env"__. Then, uncomment the DB related variables in __".env"__ and update them to the following.

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=housing_raajje
DB_USERNAME=laravel
DB_PASSWORD=password
```

You will notice that __APP_KEY__ in __".env"__ is empty. We need to generate the key, do this by running the following code.

```bash
docker-compose up -d --build
docker-compose exec app bash
php artisan key:generate
```

This will build/rebuild your docker containers and also generate the __APP_KEY__ necessary for development in local environment. Finally let's make migrations and generate seeders. We do this whenever the database structure changes, like when we add in a model. You will need to do this on initial setup.

```bash
php artisan migrate:fresh --seed
```

That should be it. Now if you visit http://localhost:8000, you should see the app running.

---

## Common CMD commands
These commands should be run in CMD.
```bash
# Builds/rebuilds the docker containers and turns them on
# Only needs to be run on initial cloning or when dependencies change
docker-compose up -d --build

# prints all containers that are running
# you should see 3 containers (tropica-sysdev-app; mysql; phpmyadmin)
docker ps

# opens the docker-compose terminal
# ALL php commands must be run in this bash (see Bash below)
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

## Common Docker-Compose commands
These commands should be run in the Docker-Compose bash *(NOT CMD)*.
```bash
# generate APP KEY for local environment
# only run when you first clone the project or if .env file is missing APP_KEY
php artisan key:generate

# migrate any changes when a model is added
# good to run when testing a new branch
php artisan migrate:fresh --seed

# rollback migrations
# good to run after testing a branch with different models and switching back to a different branch.
php artisan migrate:rollback

# (optional) run the Laravel project
# there shouldn't really be a need to run this (since Docker should handle this on its own)
# but if localhost isn't working try running this
php artisan serve
```

---

## Commit standard
When creating commits, follow the given standard. It will make reading the GitHub history easier.

These are the commit types:
- feat -> adding a new feature
- fix -> bug fix
- style -> formatting, appearance, visuals (usually for CSS)
- refactor -> code change without behaviour change
- chore -> other stuff, like build process, dependancy updates, etc.

```bash
<type>: <subject>

eg -> feat: add migration and model for hotels
   -> fix: fix login validation
   -> style: update background color
```

---

### Links for testing
- Laravel app: http://localhost:8000
- phpMyAdmin (if needed): http://localhost:8080
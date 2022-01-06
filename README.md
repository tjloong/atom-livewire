## Jiannius Atom

### Static Site

1. Install Laravel

```
composer create-project laravel/laravel <project-name>
cd <project-name>
sudo chown www-data:www-data storage -R
sudo chmod 777 storage -R
sudo chmod 777 bootstrap -R
npm install
```

2. Install Atom

```
composer require jiannius/atom-livewire
php artisan atom:install --static --force
npm install
npm run dev
```

3. Enable static site in config/atom.php

```
'static_site' => true,
```


### CMS

1. Create Database

    First you must create a database for your project in MySQL

2. Install Laravel

```
composer create-project laravel/laravel <project-name>
cd <project-name>
sudo chown www-data:www-data storage -R
sudo chmod 777 storage -R
sudo chmod 777 bootstrap -R
npm install
```

3. Install Atom

```
composer require jiannius/atom-livewire
php artisan atom:install --force
npm install
npm run dev
```

4. Install Amazon S3 driver for file upload feature

```
composer require --with-all-dependencies league/flysystem-aws-s3-v3 "^1.0"
```

5. Update .env with database name

```
DB_DATABASE=<project-name>
DB_USERNAME=root
DB_PASSWORD=password
```

6. Run migration

```
php artisan migrate
```

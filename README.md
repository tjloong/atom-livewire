## Jiannius Atom

### Static Site

1. Install Laravel

```
composer create-project laravel/laravel <project-name>
cd <project-name>
npm install

// You don't need this if using Laravel Forge or Laravel Valet
sudo chown www-data:www-data storage -R
sudo chmod 777 storage -R
sudo chmod 777 bootstrap -R
```

2. Install Atom

```
composer require jiannius/atom-livewire
php artisan atom:publish --static
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
npm install

// You don't need this if using Laravel Forge or Laravel Valet
sudo chown www-data:www-data storage -R
sudo chmod 777 storage -R
sudo chmod 777 bootstrap -R
```

3. Update .env with database name

```
DB_DATABASE=<project-name>
DB_USERNAME=root
DB_PASSWORD=password
```

4. Run migration

```
php artisan migrate
```

5. Install Amazon S3 driver for file upload feature

```
composer require -W league/flysystem-aws-s3-v3 "^3.0"
```

6. Install Atom

```
composer require jiannius/atom-livewire
php artisan atom:install
npm install
npm run dev
```

7. Install modules

```
php artisan atom:install
```

8. Optional: Publish module's views for customisation.

```
php artisan atom:publish
```
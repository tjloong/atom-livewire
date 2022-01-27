## Jiannius Atom

### Static Site

1. Install Laravel

```
composer create-project laravel/laravel <project-name>
cd <project-name>
npm install

// optional if using LEMP environment
// if using Laravel Valet or Laravel Forge, this is done automatically
sudo chown www-data:www-data storage -R
sudo chmod 777 storage -R
sudo chmod 777 bootstrap -R
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
npm install

// optional if using LEMP environment
// if using Laravel Valet or Laravel Forge, this is done automatically
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

4. Install Atom

```
composer require jiannius/atom-livewire
php artisan atom:install --force
npm install
npm run dev
```

5. Install Amazon S3 driver for file upload feature

```
composer require --with-all-dependencies league/flysystem-aws-s3-v3 "^1.0"
```

6. Enable features in config/atom.php

7. Run Atom features installer

```
php artisan atom:features
```

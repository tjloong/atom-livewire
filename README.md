### Jiannius Atom

## Static Site

1. Install Laravel

```
composer create-project laravel/laravel <project-name>
cd <project-name>
sudo chown www-data:www-data storage -R
sudo chmod 777 storage -R
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

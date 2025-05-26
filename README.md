## Jiannius Atom

### Static Site Installation

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
php artisan atom:install --static --force
npm install
```

3. Enable static site in config/atom.php

```
'static_site' => true,
```

4. Add middleware to app\Http\Kernel.php

```
protected $middleware = [
    ...
    \Jiannius\Atom\Http\Middleware\Bootstrap::class, // for site security like https redirect etc
];
```

5. Start development

```
npm run dev
```


### Application Installation

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

4. Install Atom

```
composer require jiannius/atom-livewire
```

5. Initialize Atom

```
php artisan atom:init
```

5. Run migration

```
php artisan queue:table        // publish queue table from laravel
php artisan migrate
php artisan atom:migrate base  // migrate atom base tables
```

6. Publish routes. This will copy the base routes from atom to routes/web.php

```
php artisan atom:publish base
```

7. Run npm install

```
npm install
```

8. Add middleware to app\Http\Kernel.php

```
protected $middleware = [
    ...
    \Jiannius\Atom\Http\Middleware\Bootstrap::class,
];
```

9. Configure Sentry for error monitoriing (Optional)

```
php artisan sentry:publish --dsn=<sentry dsn - get from sentry when create project>

// if develop locally, remember to set sentry dsn to null in .env file
// this is to avoid sending error to sentry server in local environment
SENTRY_LARAVEL_DSN=null
SENTRY_TRACES_SAMPLE_RATE=0
```

10. Start development

```
npm run dev
```

### Install Atom Modules

1. Run the migration for the specific module

```
php artisan atom:migrate                // select the module from the list
php artisan atom:migrate app.label      // optionally, if you know the module name
```

2. (Optional) Publish the codes to local for further modification

```
php artisan atom:publish app.label
```

### Modify Livewire\App\Settings

1. To modify the app settings, extend `Jiannius\Atom\Http\Livewire\App\Settings\Index.php`, and then change the `getTabsProperty()` method.

```
// app/Http/Livewire/App/Settings/Index.php

<?php

namespace App\Http\Livewire\App\Settings;

class Index extends \Jiannius\Atom\Http\Livewire\App\Settings\Index
{
    public function getTabsProperty(): array
    {

        return [
            ['group' => 'Account', 'tabs' => [
                ['slug' => 'login', 'label' => 'Login Information', 'icon' => 'login',],
                ['slug' => 'password', 'label' => 'Change Password', 'icon' => 'lock',],
                ['slug' => 'billing', 'label' => 'Subscription', 'icon' => 'credit-card'],
            ]],

            ['group' => 'System', 'tabs' => [
                ['slug' => 'user', 'label' => 'Users', 'icon' => 'users'],
                ['slug' => 'invitation','label' => 'Invitations', 'icon' => 'invitation'],
                ['slug' => 'role', 'label' => 'Roles', 'icon' => 'user-tag'],
                ['slug' => 'team', 'label' => 'Teams', 'icon' => 'people-group'],
                ['slug' => 'page', 'label' => 'Pages', 'icon' => 'newspaper'],
                ['slug' => 'file', 'label' => 'Files and Media', 'icon' => 'images'],
            ]],

            ['group' => 'Labels', 'tabs' => [
                ['slug' => 'label/blog-category', 'label' => 'Blog Categories', 'icon' => 'tag'],
            ]],

            ['group' => 'Website', 'tabs' => [
                ['slug' => 'website/profile', 'label' => 'Profile', 'icon' => 'globe'],
                ['slug' => 'website/seo', 'label' => 'SEO', 'icon' => 'search'],
                ['slug' => 'website/analytics', 'label' => 'Analytics', 'icon' => 'chart-simple'],
                ['slug' => 'website/social-media', 'label' => 'Social Media', 'icon' => 'share-nodes'],
                ['slug' => 'website/announcement', 'label' => 'Announcement', 'icon' => 'bullhorn'],
                ['slug' => 'website/popup', 'label' => 'Pop-Up', 'icon' => 'window-restore'],
            ]],

            ['group' => 'Integration', 'tabs' => [
                ['slug' => 'integration/email', 'label' => 'Email', 'icon' => 'paper-plane'],
                ['slug' => 'integration/storage', 'label' => 'Storage', 'icon' => 'hard-drive'],
                ['slug' => 'integration/payment', 'label' => 'Payment', 'icon' => 'money-bill'],
                ['slug' => 'integration/social-login', 'label' => 'Social Login', 'icon' => 'login'],
            ]]
        ];
    }
}

```

2. Alternatively, you can publish the whole app/settings to local.

```
php artisan atom:publish app.settings
```
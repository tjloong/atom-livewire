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
php artisan atom:publish --static
npm install
npm run dev
```

3. Enable static site in config/atom.php

```
'static_site' => true,
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

4. Run migration

```
php artisan migrate
```

5. Composer install Amazon S3 driver for file upload feature

```
composer require -W league/flysystem-aws-s3-v3 "^3.0"
```

6. Composer install Atom

```
composer require jiannius/atom-livewire
php artisan atom:publish base --force
npm install
npm run dev
```

7. Install Atom base

```
php artisan atom:install
```

8. Run atom:install again to install Atom's modules

```
php artisan atom:install
```

### Account Portal Customisation

1. Atom has provided a default account portal route named "account.authentication" for session user to change their login details and password.

2. You can add additional account portal routes in routes/web.php.

```
Route::prefix('account')->middleware('auth')->as('account.')->group(function() {
    define_route('/', fn() => redirect()->route(
        auth()->user()->account ? 'account.courses' : 'account.authentication')
    )->name('home');
    
    define_route('profile', 'Account\Profile')->name('profile');
    define_route('courses', 'Account\Courses')->name('courses');
});
```

3. You can add/remove side navigation by editing resources/views/layouts/account.blade.php. Add grid columns (recommended col-span-3 + col-span-9), and then add side navigation using &lt;x-sidenav&gt;.

### Blog Module Customisation

1. Atom provided the following blog routes:
    - app.blog.listing using component Jiannius\Atom\Http\Livewire\App\Blog\Listing
    - app.blog.create using component Jiannius\Atom\Http\Livewire\App\Blog\Create
    - app.blog.update using component Jiannius\Atom\Http\Livewire\App\Blog\Update\Index
        - subcomponent Jiannius\Atom\Http\Livewire\App\Blog\Update\Content
        - subcomponent Jiannius\Atom\Http\Livewire\App\Blog\Update\Seo
        - subcomponent Jiannius\Atom\Http\Livewire\App\Blog\Update\Settings

2. To overide the default blog behavior, just create your livewire component into app/Http/Livewire/App/Blog following the structure above. For example:

```
// App\Http\Livewire\App\Blog\Listing

<?php

namespace App\Http\Livewire\App\Blog;

use Jiannius\Atom\Http\Livewire\App\Blog\Listing as AtomBlogListing;

class Listing extends AtomBlogListing
{
    public function render()
    {
        return view('livewire.app.blog.listing'); // your own livewire component
    }
}
```

3. Sometimes you need to modify the side navigation by adding or removing tabs. To do this, create your own App\Http\Livewire\App\Blog\Update\Index and extend it from Jiannius\Atom\Http\Livewire\App\Blog\Update\Index.

```
// App\Http\Livewire\App\Blog\Update\Index

<?php

namespace App\Http\Livewire\App\Blog\Update;

use Jiannius\Atom\Http\Livewire\App\Blog\Update\Index as AtomBlogIndex;

class Index extends AtomBlogIndex
{
    // this should return a collection
    public function getTabsProperty()
    {
        return collect([
            ['slug' => 'content', 'label' => 'Blog Content'],
            ['slug' => 'admin', 'label' => 'Admin Section'],
        ]);
    }
}
```

Atom will then auto discover your component within the app/Http/Livewire/App/Blog/Update folder. Make sure the component follow the namespace convention.

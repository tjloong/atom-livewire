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
    \Jiannius\Atom\Http\Middleware\SiteSecurity::class, // for site security like https redirect etc
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

4. Run migration

```
php artisan migrate
```

5. Composer install Atom

```
composer require jiannius/atom-livewire
php artisan atom:install --force
npm install
```

6. Run atom:install again to install Atom's modules

```
php artisan atom:install
```

7. Add middleware to app\Http\Kernel.php

```
protected $middleware = [
    ...
    \Jiannius\Atom\Http\Middleware\SiteSecurity::class, // for site security like https redirect etc
];

protected $middlewareGroups = [
    'web' => [
        ...
        \Jiannius\Atom\Http\Middleware\PortalGuard::class,  // for checking blocked account etc
        \Jiannius\Atom\Http\Middleware\PlanGuard::class,    // only if using plans module
    ],

    'api' => [
        ...
    ],
];
```

8. Start development

```
npm run dev
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

### Payment Gateway

1. To enable payment gateway, add the provider in config/atom.php

```
// config/atom.php
...
'payment_gateway' => ['ozopay'];
```

2. Exclude the redirect and webhook route from CSRF checking.

```
// app/Http/Middleware/VerifyCsrfToken.php

protected $exclude = [
    '__ozopay/*',
    '__gkash/*',
    '__stripe/*',
];
```

3. Configure config/session.php same site settings to null. This is to allow POST from another provider.

```
// config/session.php
...
'same_site' => null,
```

4. Use the payment gateway component to show the payment method selection box.

```
<x-payment-gateway
    callback="createPayment"
    :providers="['ozopay']"
    :endpoints="[
        'ozopay' => 'https://uatpayment.ozopay.com/PaymentEntry/PaymentOption',
    ]"
    :value="[
        'email' => $contribution->socso_account->email,
        'phone' => $contribution->socso_account->phone,
        'address' => implode(', ', [$contribution->socso_account->address_1, $contribution->socso_account->address_2]),
        'city' => $contribution->socso_account->city,
        'postcode' => $contribution->socso_account->postcode,
        'state' => metadata()->socso('state', $contribution->socso_account->state),
        'country' => 'MY',
        'currency' => 'MYR',
        'amount' => $this->total,
    ]"
/>
```

5. Create a fulfillment job in app/Jobs for each provider. Below are the class name for each providers:
    - Ozopay: app/Jobs/OzopayFulfillment.php

```
// app/Jobs/OzopayFulfillment.php

...
protected $params; // will contains the response from provider

public function __construct($params)
{
    $this->params = $params;
}

public function handle()
{
    // handle fulfillment
}
```

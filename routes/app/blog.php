<?php

define_route()->prefix('blog')->as('blog.')->group(function () {
    define_route('listing', 'App\Blog\Listing')->name('listing');
    define_route('create', 'App\Blog\Create')->name('create');
    define_route('{id}', 'App\Blog\Update')->name('update');
});

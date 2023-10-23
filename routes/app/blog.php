<?php

// app/blog
$route->get('app/blog/{blogId?}', 'App\Blog')->middleware('auth')->name('app.blog');
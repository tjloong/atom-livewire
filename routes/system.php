<?php

$route = app('route');

$route->get('__sitemap', 'SitemapController')->name('__sitemap');
$route->get('__locale/{locale}', 'LocaleController@set')->name('__locale');
$route->post('__select', 'SelectController@get')->name('__select');
$route->post('__share', 'ShareController')->name('__share');

$route->post('__file/url', 'FileController@url')->name('__file.url');
$route->post('__file/list', 'FileController@list')->name('__file.list');
$route->post('__file/upload', 'FileController@upload')->name('__file.upload');
$route->get('__file/{ulid}/{size?}', 'FileController@get')->name('__file.get');
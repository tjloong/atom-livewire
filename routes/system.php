<?php

define_route('__sitemap', 'SitemapController@index')->name('__sitemap');
define_route('__pdf', 'PdfController@index')->name('__pdf');
define_route('__export/{filename}', 'ExportController@download')->name('__export');
define_route('__file/{id}', 'FileController@index')->name('__file');
define_route('__file/upload', 'FileController@upload', 'post')->name('__file.upload');
define_route('__file/download/{id}', 'FileController@download')->name('__file.download');
define_route('__locale/{locale}', 'LocaleController@set')->name('__locale.set');

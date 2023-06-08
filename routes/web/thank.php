<?php

define_route('thank/{slug?}', 'Web\Thank')
    ->where('slug', '.*')
    ->name('web.thank');

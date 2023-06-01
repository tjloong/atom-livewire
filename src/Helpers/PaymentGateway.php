<?php

/**
 * Stripe
 */
function stripe($credentials = null)
{
    return new Jiannius\Atom\Services\Stripe($credentials);
}

<?php

namespace Jiannius\Atom\Traits;

use Jiannius\Atom\Listeners\Notilog as NotilogListener;

trait Notilog
{
    public $notilogEnabled = false;
    public $notilogUlid;
    public $notilogSender;

    // enable notilog
    public function enableNotilog() : void
    {
        $this->notilogEnabled = true;
        $this->notilogSender = user();
        $this->notilogUlid = (string) str()->ulid();
    }

    // failed handler
    public function failed($e) : void
    {
        NotilogListener::failed($this->notilogUlid, $e->getMessage());
    }
}
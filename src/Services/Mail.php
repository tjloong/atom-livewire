<?php

namespace Jiannius\Atom\Services;

use Illuminate\Support\Facades\Schema;

class Mail
{
    public function __construct(
        $to = [],
        $cc = [],
        $bcc = [],
        $subject = '',
        $view = 'atom:mail.sendmail',
        $markdown = '',
        $with = [],
        $tags = [],
        $metadata = [],
        $attachments = [],
        $track = null,
    )
    {
        if (!$to) abort('Missing recipient "to"');
        if (!$view) abort('Missing mail view');

        \Illuminate\Support\Facades\Mail::to($to)
            ->cc($cc)
            ->bcc($bcc)
            ->send(new \Jiannius\Atom\Mail\Mail([
                'subject' => $subject,
                'view' => $view,
                'markdown' => $markdown,
                'with' => $with,
                'tags' => $tags,
                'metadata' => $metadata,
                'attachments' => $attachments,
                'track' => $track ?? Schema::hasTable('sendmails'),
            ]));
    }
}
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
        $view = '',
        $markdown = 'atom::mail.generic',
        $content = '',
        $cta = null,
        $with = [],
        $tags = [],
        $metadata = [],
        $attachments = [],
        $track = null,
        $queue = false,
        $later = null,
    )
    {
        if (!$to) abort('Missing recipient "to"');
        if (!$view && !$markdown && !$content) abort('Empty mail content or missing view');

        $mail = \Illuminate\Support\Facades\Mail::to($to)->cc($cc)->bcc($bcc);

        $message = new \Jiannius\Atom\Mail\Generic([
            'subject' => $subject,
            'view' => $view,
            'markdown' => $view ? '' : $markdown,
            'with' => $content
                ? ['cta' => $cta, 'content' => $content]
                : $with,
            'tags' => $tags,
            'metadata' => $metadata,
            'attachments' => $attachments,
            'track' => $track ?? Schema::hasTable('sendmails'),
        ]);

        if ($queue) {
            if (is_string($queue)) $message = $message->onQueue($queue);
            $mail->queue($message);
        }
        else if ($later) {
            $mail->later($later, $message);
        }
        else {
            $mail->send($message);
        }    
    }
}

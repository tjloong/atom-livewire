<?php

namespace Jiannius\Atom\Services;

class Mail
{
    public function __construct(
        $to = [],
        $cc = [],
        $bcc = [],
        $senderName = null,
        $senderEmail = null,
        $replyTo = null,
        $subject = '',
        $view = '',
        $markdown = 'atom::mail.generic',
        $content = '',
        $cta = null,
        $with = [],
        $tags = [],
        $metadata = [],
        $attachments = [],
        $track = false,
        $queue = false,
        $later = null,
        $logo = null,
    )
    {
        if (!$to) abort('Missing recipient "to"');
        if (!$view && !$markdown && !$content) abort('Empty mail content or missing view');

        $mail = \Illuminate\Support\Facades\Mail::to($to)->cc($cc)->bcc($bcc);

        $message = new \Jiannius\Atom\Mail\Generic([
            'sender_name' => $senderName,
            'sender_email' => $senderEmail,
            'reply_to' => $replyTo,
            'subject' => $subject,
            'view' => $view,
            'markdown' => $view ? '' : $markdown,
            'with' => $content
                ? ['cta' => $cta, 'content' => $content]
                : $with,
            'tags' => $tags,
            'metadata' => $metadata,
            'attachments' => $attachments,
            'track' => $track,
            'logo' => $logo,
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

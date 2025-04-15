<?php

namespace Jiannius\Atom\Services;

use Jiannius\Atom\Atom;

class Notification
{
    public $config = [
        'title' => null,
        'content' => null,
        'href' => null,
        'sender' => null,
        'receiver' => null,
        'fill' => null,
        'timestamp' => null,
    ];

    // set title
    public function title($title)
    {
        $this->config['title'] = $title;

        return $this;
    }

    // set content
    public function content($content)
    {
        $this->config['content'] = $content;

        return $this;
    }

    // set href
    public function href($href)
    {
        $this->config['href'] = $href;

        return $this;
    }

    // set timestamp
    public function timestamp($timestamp)
    {
        $this->config['timestamp'] = $timestamp;

        return $this;
    }

    // set sender
    public function sender($sender)
    {
        $this->config['sender'] = $sender;

        return $this;
    }

    // set receiver
    public function receiver($receiver)
    {
        $this->config['receiver'] = $receiver;

        return $this;
    }

    // set fill
    public function fill($fill)
    {
        $this->config['fill'] = $fill;

        return $this;
    }

    // send
    public function send()
    {
        $this->broadcast()->send();
    }

    // send now
    public function sendNow()
    {
        $this->broadcast()->sendNow();
    }

    // create broadcast
    public function broadcast()
    {
        if (get($this->config, 'receiver')) {
            $message = $this->message($this->notification());

            return Atom::broadcast()
                ->name('notification-created')
                ->private('notification.'.get($message, 'receiver.id'))
                ->with($message);
        }

        return Atom::broadcast()
            ->name('notification-created')
            ->public('notification')
            ->with($this->message($this->config));
    }

    // create notification
    public function notification()
    {
        $receiver = get($this->config, 'receiver');
        $receiver = is_numeric($receiver) || is_string($receiver) ? model('user')->find($receiver) : $receiver;

        $sender = get($this->config, 'sender');
        $sender = is_numeric($sender) || is_string($sender) ? model('user')->find($sender) : $sender;

        $notification = model('notification')->create([
            'title' => get($this->config, 'title'),
            'content' => get($this->config, 'content'),
            'sender_id' => get($sender, 'id') ?? user('id'),
            'receiver_id' => get($receiver, 'id'),
            ...(get($this->config, 'fill') ?? []),
        ]);

        return [
            'title' => $notification->title,
            'content' => $notification->content,
            'href' => $notification->href,
            'timestamp' => $notification->created_at->toDatetimeString(),
            'sender' => $notification->sender?->toArray(),
            'receiver' => $notification->receiver->toArray(),
        ];
    }

    // make message
    public function message($message)
    {
        return [
            ...$message,
            'title' => str()->limit(get($message, 'title'), 80),
            'content' => str()->limit(strip_tags(get($message, 'content')), 100),
        ];
    }
}
<?php

namespace Jiannius\Atom\Services;

class Youtube
{
    // constructor
    public function __construct(public $url)
    {
        //
    }

    // get vid from url
    public function vid() : mixed
    {
        if (!is_string($this->url)) return null;

        $regex = '/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/';
    
        preg_match($regex, $this->url, $matches);
    
        return collect($matches)->first();    
    }

    // get info
    public function info() : array
    {
        $vid = $this->vid();
        $info = json_decode(file_get_contents('https://noembed.com/embed?dataType=json&url='.$this->url), true);

        return array_merge($info, [
            'embed_url' => $vid ? 'https://www.youtube.com/embed/'.$vid : null,
        ]);
    }
}
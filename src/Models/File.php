<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Jiannius\Atom\Traits\Models\HasTrace;
use Jiannius\Atom\Traits\Models\HasFilters;

class File extends Model
{
    use HasFilters;
    use HasTrace;
    
    protected $guarded = [];

    protected $casts = [
        'size' => 'float',
        'data' => 'object',
    ];

    protected $appends = [
        'type',
        'is_image',
        'is_video',
        'is_audio',
    ];

    protected $compression = [
        'width' => 1440,
        'height' => 1440,
    ];

    /**
     * Model boot method
     */
    protected static function booted(): void
    {
        static::deleting(function($file) {
            $path = data_get($file->data, 'path');
            $provider = data_get($file->data, 'provider') ?? data_get($file->data, 'disk') ?? 'local';
            $env = data_get($file->data, 'env', 'production');

            if ($path) {
                if ($env === 'production' && !app()->environment('production')) {
                    abort(400, 'Do not delete production file in '.app()->environment().' environment!');
                }
                else if ($disk = $file->getDisk($provider)) {
                    $disk->delete($path);
                }
            }
        });
    }

    /**
     * Attribute for is image
     */
    protected function isImage(): Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'image/'),
        );
    }

    /**
     * Attribute for is video
     */
    protected function isVideo(): Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'video/'),
        );
    }

    /**
     * Attribute for is audio
     */
    protected function isAudio(): Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'audio/'),
        );
    }

    /**
     * Attribute for size
     */
    protected function size(): Attribute
    {
        return Attribute::make(
            get: fn($size) => format_filesize($size, 'KB'),
        );
    }

    /**
     * Attribute for url
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn($url) => data_get($this->data, 'visibility') === 'private'
                ? route('__file', [$this->id])
                : $url,
        );
    }

    /**
     * Attribute for file type
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get: function() {
                if (!$this->mime) return;
                if ($this->mime === 'youtube') return $this->mime;
        
                $type = (explode('/', $this->mime))[1];
        
                if ($type === 'ld+json') return 'jsonld';
                if ($type === 'svg+xml') return 'svg';
                if ($type === 'plain') return 'txt';
                if (in_array($type, ['msword', 'vnd.openxmlformats-officedocument.wordprocessingml.document'])) return 'word';
                if (in_array($type, ['vnd.ms-powerpoint', 'vnd.openxmlformats-officedocument.presentationml.presentation'])) return 'ppt';
                if (in_array($type, ['vnd.ms-excel', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) return 'excel';
        
                return $type;
            },
        );
    }

    /**
     * Attribute for icon
     */
    protected function icon(): Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->is_image) return 'image';
                elseif ($this->is_video) return 'play';
                elseif ($this->is_audio) return 'music';
                elseif ($this->type === 'word') return 'word';
                elseif ($this->type === 'excel') return 'excel';
                elseif ($this->type === 'ppt') return 'ppt';
                elseif ($this->type === 'pdf') return 'pdf';
        
                return 'file';
            },
        );
    }

    /**
     * Scope for fussy search
     */
    public function scopeSearch($query, $search): void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
            ->orWhere('url', 'like', "%$search%")
        );
    }

    /**
     * Scope for mime
     */
    public function scopeMime($query, $mime): void
    {
        $mime = explode(',', $mime);
        
        $query->where(function($q) use ($mime) {
            foreach ($mime as $val) {
                if ($val === 'file') {
                    $q->orWhere(fn($q) => $q
                        ->where('mime', 'not like', 'image/%')
                        ->where('mime', 'not like', 'video/%')
                        ->where('mime', 'not like', 'audio/%')
                        ->where('mime', '<>', 'youtube')
                    );
                }
                else {
                    $q->orWhere('mime', 'like', str($val)->replace('*', '%')->toString());
                }
            }
        });
    }

    /**
     * Store file content
     */
    public function store($content, $location = 'uploads', $visibility = 'public', $compress = true)
    {
        if ($compress) $this->compress($content);

        $meta = $this->getMeta($content);
        
        if (!$meta) return;
        if (data_get($meta, 'size')) {
            $fs = settings('filesystem', 'local');
    
            if ($fs === 'local') $stored = $this->storeToLocal($content, $location, $visibility);
            else if ($fs === 'do') $stored = $this->storeToDO($content, $location, $visibility);

            $meta = array_merge($meta, $stored);
        }

        return model('file')->create([
            'name' => data_get($meta, 'name'),
            'size' => data_get($meta, 'size'),
            'mime' => data_get($meta, 'mime'),
            'url' => data_get($meta, 'url'),
            'data' => array_filter([
                'vid' => data_get($meta, 'vid'),
                'path' => data_get($meta, 'path'),
                'disk' => data_get($meta, 'disk'),
                'dimension' => data_get($meta, 'dimension'),
                'visibility' => $visibility,
                'env' => app()->environment(),
            ]),
        ]);
    }

    /**
     * Store file content to local disk
     */
    public function storeToLocal($content, $location, $visibility)
    {
        $location = $visibility === 'public' 
            ? str()->start($location, 'public/') 
            : $location;

        $path = $content->store($location);

        $url = $visibility === 'public'
            ? asset('storage/'.str()->replaceFirst('public/', '', $path))
            : null;

        return [
            'url' => $url,
            'path' => $path,
            'disk' => 'local',
        ];
    }

    /**
     * Store file content to digital ocean spaces
     */
    public function storeToDO($content, $location, $visibility)
    {
    
        $disk = $this->getDisk('do');
        $folder = settings('do_spaces_folder').'/'.$location;
        $path = $disk->putFile($folder, $content->path(), $visibility);
        $url = $disk->url($path);

        return [
            'url' => $url,
            'path' => $path,
            'disk' => 'do',
        ];
    }

    /**
     * Get file content meta
     */
    public function getMeta($content)
    {
        if ($vid = youtube_vid($content)) {
            return [
                'name' => $vid,
                'mime' => 'youtube',
                'url' => 'https://www.youtube.com/embed/'.$vid,
                'vid' => $vid,
            ];
        }
        else if (is_string($content)) {
            try {
                $img = Image::make($content);

                return [
                    'name' => $content,
                    'mime' => $img->mime(),
                    'url' => $content,
                    'dimension' => $img->width().'x'.$img->height(),
                ];
            } catch (\Throwable $th) {
                return [];
            }
        }
        else {
            $name = $content->getClientOriginalName();
            $ext = $content->extension();
            $size = round($content->getSize()/1024, 5);
            $mime = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])
                ? 'image/'.$ext
                : $content->getMimeType();
            
            if (str($mime)->is('image/*')) {
                $img = Image::make($content->path());
                $dimension = $img->width().'x'.$img->height();

                return compact('name', 'ext', 'size', 'mime', 'dimension');
            }
            else return compact('name', 'ext', 'size', 'mime');
        }
    }

    /**
     * Compress file content
     */
    public function compress($content)
    {
        if (is_string($content)) return;

        $path = $content->path();
        $ext = $content->extension();

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return;

        $img = Image::make($path)->orientate();

        $img->resize(
            data_get($this->compression, 'width'),
            data_get($this->compression, 'height'),
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            },
        )->save();

        clearstatcache();
    }

    /**
     * Get disk
     */
    public function getDisk($provider)
    {
        // digital ocean
        config([
            'filesystems.disks.do' => [
                'driver' => 's3',
                'key' => settings('do_spaces_key'),
                'secret' => settings('do_spaces_secret'),
                'region' => settings('do_spaces_region'),
                'bucket' => settings('do_spaces_bucket'),
                'folder' => settings('do_spaces_folder'),
                'endpoint' => settings('do_spaces_endpoint'),
                'use_path_style_endpoint' => false,
            ],
        ]);

        return Storage::disk($provider);
    }
}

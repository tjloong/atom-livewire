<?php

namespace Jiannius\Atom\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Jiannius\Atom\Traits\HasTrace;
use Jiannius\Atom\Traits\HasFilters;

class File extends Model
{
    use HasTrace;
    use HasFilters;
    
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

    /**
     * Model boot method
     * 
     * @return
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($file) {
            $path = data_get($file->data, 'path');
            $provider = data_get($file->data, 'provider', 'local');
            $env = data_get($file->data, 'env', 'production');

            if ($path) {
                if ($env === 'production' && !app()->environment('production')) {
                    abort(400, 'Do not delete production file in '.app()->environment().' environment!');
                }
                else if ($disk = model('file')->getStorageDisk($provider)) {
                    $disk->delete($path);
                }
            }
        });
    }

    /**
     * Scope for fussy search
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q
                ->where('name', 'like', "%$search%")
                ->orWhere('url', 'like', "%$search%");
        });
    }

    /**
     * Scope for type
     * 
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType($query, $types)
    {
        if ($types === 'all') return $query;
        else {
            return $query->where(function($q) use ($types) {
                foreach ((array)$types as $type) {
                    if ($type === 'image') $q->orWhere('mime', 'like', 'image/%');
                    if ($type === 'video') $q->orWhere('mime', 'like', 'video/%');
                    if ($type === 'audio') $q->orWhere('mime', 'like', 'audio/%');
                    if ($type === 'youtube') $q->orWhere('mime', 'youtube');
                    if ($type === 'file') {
                        $q->orWhere(fn($q) => $q
                            ->where('mime', 'not like', 'image/%')
                            ->where('mime', 'not like', 'video/%')
                            ->where('mime', 'not like', 'audio/%')
                            ->where('mime', '<>', 'youtube')
                        );
                    }
                }
            });
        }
    }

    /**
     * Get is image attribute
     * 
     * @return boolean
     */
    public function getIsImageAttribute()
    {
        return Str::startsWith($this->mime, 'image/');
    }

    /**
     * Get is video attribute
     * 
     * @return boolean
     */
    public function getIsVideoAttribute()
    {
        return Str::startsWith($this->mime, 'video/');
    }

    /**
     * Get is audio attribute
     * 
     * @return boolean
     */
    public function getIsAudioAttribute()
    {
        return Str::startsWith($this->mime, 'audio/');
    }

    /**
     * Get size attribute
     * 
     * @param float $size
     * @return string
     */
    public function getSizeAttribute($size)
    {
        if ($size <= 0) return null;
        else if ($size < 1) return round($size * 1000, 2) . ' KB';
        else return round($size, 2) . ' MB';
    }

    /**
     * Get url attribute
     * 
     * @return string
     */
    public function getUrlAttribute($url)
    {
        $path = data_get($this->data, 'path');
        $visibility = data_get($this->data, 'visibility', 'public');
        $provider = data_get($this->data, 'provider', 'local');

        if ($visibility === 'private') return route('__file', [$this->id]);
        else if ($url) return $url;
        else if ($path) return $this->getStorageDisk($provider)->url($path);
    }

    /**
     * Get file type attribute
     * 
     * @return string
     */
    public function getTypeAttribute()
    {
        if ($this->mime === 'youtube') return $this->mime;

        $type = (explode('/', $this->mime))[1];

        if ($type === 'ld+json') return 'jsonld';
        if ($type === 'svg+xml') return 'svg';
        if ($type === 'plain') return 'txt';
        if (in_array($type, ['msword', 'vnd.openxmlformats-officedocument.wordprocessingml.document'])) return 'word';
        if (in_array($type, ['vnd.ms-powerpoint', 'vnd.openxmlformats-officedocument.presentationml.presentation'])) return 'ppt';
        if (in_array($type, ['vnd.ms-excel', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) return 'excel';

        return $type;
    }

    /**
     * Get icon attribute
     */
    public function getIconAttribute()
    {
        if ($this->is_image) return 'image';
        elseif ($this->is_video) return 'play';
        elseif ($this->is_audio) return 'music';
        elseif ($this->type === 'word') return 'word';
        elseif ($this->type === 'excel') return 'excel';
        elseif ($this->type === 'ppt') return 'ppt';
        elseif ($this->type === 'pdf') return 'pdf';

        return 'file';
    }

    /**
     * Store file
     */
    public static function store($file, $location = 'public/uploads')
    {
        $visibility = str($location)->is('public/*') ? 'public' : 'private';
        $dimension = self::compress($file);
        $path = $file->store($location);
        $meta = self::getFileMeta($file);
        $data = [
            'dimension' => $dimension,
            'visibility' => $visibility,
            'env' => app()->environment(),
        ];

        // local disk
        if (site_settings('filesystem') === 'local') {
            $url = $visibility === 'public' ? asset('storage/' . str_replace('public/', '', $path)) : null;
            $data = array_merge($data, [
                'path' => $path,
                'provider' => 'local',
            ]);
        }
        // upload file to 3rd party disk
        else if ($disk = self::getStorageDisk()) {
            $folder = site_settings('do_spaces_folder').'/'.str()->replaceFirst('public/', '', $location);
            $storedpath = $disk->putFile($folder, storage_path('app/'.$path), $visibility);
            $url = $disk->url($storedpath);
            $data = array_merge($data, [
                'path' => $storedpath,
                'provider' => site_settings('filesystem'),
            ]);

            // delete the local copy
            Storage::delete($path);
        }

        $file = model('file')->fill([
            'name' => data_get($meta, 'name'),
            'size' => data_get($meta, 'size'),
            'mime' => data_get($meta, 'mime'),
            'url' => $url,
            'data' => $data,
        ]);
        
        $file->save();

        return $file;
    }

    /**
     * Store image url
     */
    public static function storeImageUrl($url)
    {
        $img = Image::make($url);
        $mime = $img->mime();

        $file = model('file');
        $file->name = $url;
        $file->mime = $mime;
        $file->url = $url;
        $file->data = ['dimension' => $img->width() . 'x' . $img->height()];
        $file->save();

        return $file;
    }

    /**
     * Store youtube url
     */
    public static function storeYoutubeUrl($url)
    {
        $file = model('file');
        $file->name = $url;
        $file->mime = 'youtube';
        $file->url = 'https://www.youtube.com/embed/' . $url;
        $file->data = ['vid' => $url];
        $file->save();

        return $file;
    }

    /**
     * Get file meta
     */
    public static function getFileMeta($file)
    {
        $name = $file->getClientOriginalName();
        $size = round($file->getSize()/1024/1024, 5);
        $ext = $file->extension();

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $mime = "image/$ext";
        else $mime = $file->getMimeType();

        return compact('name', 'size', 'mime', 'ext');
    }

    /**
     * Compress files
     */
    public static function compress($file)
    {
        $path = $file->path();
        $ext = $file->extension();

        // resize image
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $img = Image::make($path);

            $img->resize(1440, 1440, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save();

            clearstatcache();

            return $img->width() . 'x' . $img->height();
        }
    }

    /**
     * Get storage disk
     */
    public static function getStorageDisk($provider = null)
    {
        $provider = $provider ?? site_settings('filesystem');

        if ($provider === 'do') {
            $key = site_settings('do_spaces_key');
            $secret = site_settings('do_spaces_secret');

            if ($key && $secret) {
                config([
                    'filesystems.disks.do' => [
                        'driver' => 's3',
                        'key' => $key,
                        'secret' => $secret,
                        'region' => site_settings('do_spaces_region'),
                        'bucket' => site_settings('do_spaces_bucket'),
                        'folder' => site_settings('do_spaces_folder'),
                        'endpoint' => site_settings('do_spaces_endpoint'),
                        'use_path_style_endpoint' => false,
                    ],
                ]);
        
                return Storage::disk('do');
            }
        }
        else if ($provider) return Storage::disk($provider);
    }
}

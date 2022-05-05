<?php

namespace Jiannius\Atom\Models;

use Exception;
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
            $path = $file->data->path ?? null;
            $provider = $file->data->provider ?? 'local';

            if ($path) {
                // prevent production file delete when in local
                if (!app()->environment('production') && (str()->startsWith($path, 'prod/') || str()->startsWith($path, 'production/'))) {
                    abort(400, 'Do not delete production file in ' . app()->environment() . ' environment!');
                }
                // digital ocean spaces
                else if ($provider === 'do') {
                    if ($disk = model('site_setting')->getDoDisk()) $disk->delete($path);
                }
                // local file
                else {
                    Storage::delete($path);
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
                            ->where('mime', 'not lime', 'video/%')
                            ->where('mime', 'not lime', 'audio/%')
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
        $path = $this->data->path ?? null;
        $isPublic = !$path || str($path)->is('public/*');

        if ($isPublic) return $path ? Storage::url($path) : $url;
        else return route('__file', [$this->id]);
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
     * Get youtube thumbnail attribute
     * 
     * @return string
     */
    public function getYoutubeThumbnailAttribute()
    {
        return $this->mime === 'youtube'
            ? 'https://img.youtube.com/vi/' . ($this->data->vid ?? '') . '/default.jpg'
            : null;
    }

    /**
     * Store file
     */
    public static function store($file, $location = 'public/uploads')
    {
        $isPublic = str($location)->is('public/*');
        $provider = 'local';
        $dimension = self::compress($file);
        $path = $file->store($location);
        $meta = self::getFileMeta($file);
        $url = $isPublic ? asset('storage/' . str_replace('public/', '', $path)) : null;

        // upload file to DO
        if (site_settings('filesystem') === 'do') {
            if ($disk = model('site_setting')->getDoDisk()) {
                try {
                    $folder = app()->environment('production') ? 'prod' : 'staging';
                    $dopath = $disk->putFile($folder, storage_path("app/$path"), $isPublic ? 'public' : 'private');
                    $cdn = site_settings('do_spaces_cdn');
                    $url = $cdn . '/' . $dopath;
                    $provider = 'do';
    
                    // delete the local copy
                    Storage::delete($path);
                } catch (Exception $e) {
                    logger("Unable to upload $path to Digital Ocean bucket.");
                }
            }
        }

        $file = model('file');
        $file->name = $meta['name'];
        $file->size = $meta['size'];
        $file->mime = $meta['mime'];
        $file->url = $url;

        $file->data = [
            'path' => $dopath ?? $path,
            'provider' => $provider,
            'dimension' => $dimension,
        ];

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
}

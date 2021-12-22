<?php

namespace Jiannius\Atom\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Jiannius\Atom\Traits\HasOwner;
use Jiannius\Atom\Traits\HasFilters;
use Jiannius\Atom\Models\SiteSetting;

class File extends Model
{
    use HasOwner;
    use HasFilters;
    
    protected $fillable = [
        'name',
        'mime',
        'size',
        'url',
        'data',
    ];

    protected $casts = [
        'size' => 'float',
        'data' => 'object',
    ];

    protected $appends = [
        'type',
        'is_image',
        'is_video',
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
            if ($path = $file->data->path ?? null) {
                // prevent production file delete when in local
                if (!app()->environment('production') && (Str::startsWith($path, 'prod/') || Str::startsWith($path, 'production/'))) {
                    abort(500, 'Do not delete production file in ' . app()->environment() . ' environment!');
                }
                // local file
                else if (Str::startsWith($path, 'public/uploads/')) {
                    Storage::delete($path);
                }
                // digital ocean spaces
                else if (Str::startsWith($file->url, SiteSetting::getSetting('do_spaces_cdn'))) {
                    if ($disk = SiteSetting::getDoDisk()) $disk->delete($path);
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
    public function scopeType($query, $type)
    {
        if ($type === 'all') return $query;
        if ($type === 'image') return $query->where('mime', 'like', 'image/%');
        if ($type === 'video') return $query->where('mime', 'like', 'video/%');
        if ($type === 'youtube') return $query->where('mime', 'youtube');
        if ($type === 'file') {
            return $query->where(function ($q) {
                $q->where('mime', 'not like', 'image/%')->where('mime', '<>', 'youtube');
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
}

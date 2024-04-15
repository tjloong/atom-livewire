<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Intervention\Image\ImageManagerStatic as Image;
use Jiannius\Atom\Jobs\File\CreateThumbnails;
use Jiannius\Atom\Traits\Models\Footprint;
use Jiannius\Atom\Traits\Models\HasFilters;
use Jiannius\Atom\Traits\Models\HasUlid;

class File extends Model
{
    use Footprint;
    use HasFilters;
    use HasUlid;
    
    protected $guarded = [];

    protected $casts = [
        'size' => 'float',
        'width' => 'integer',
        'height' => 'integer',
        'data' => 'array',
    ];

    protected $appends = [
        'type',
        'is_image',
        'is_video',
        'is_audio',
        'is_file',
    ];

    // booted
    protected static function booted() : void
    {
        static::deleting(function($file) {
            if ($file->path) {
                $env = data_get($file->data, 'env', 'production');

                if ($env === 'production' && !app()->environment('production')) {
                    abort(400, 'Do not delete production file in '.app()->environment().' environment!');
                }
                else {
                    $file->thumbnails->each(fn($tn) => $tn->delete());
                    
                    if ($storage = $file->getStorage()) {
                        $storage->delete($file->path);
                    }
                }
            }
        });
    }

    // get thumbnails for file
    public function thumbnails() : HasMany
    {
        return $this->hasMany(model('file'), 'parent_id');
    }

    // attribute for is image
    protected function isImage() : Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'image/'),
        );
    }

    // attribute for is video
    protected function isVideo() : Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'video/'),
        );
    }

    // attribute for is audio
    protected function isAudio() : Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'audio/'),
        );
    }

    // attribute for is file
    protected function isFile() : Attribute
    {
        return Attribute::make(
            get: fn() => !$this->is_image && !$this->is_video && !$this->is_audio,
        );
    }

    // attribute for size
    protected function size() : Attribute
    {
        return Attribute::make(
            get: fn($size) => format_filesize($size, 'KB'),
        );
    }

    // attribute for filename
    protected function filename() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->path ? last(explode('/', $this->path)) : null,
        );
    }

    // attribute for storage path
    protected function storagePath() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->disk === 'local' ? storage_path("app/{$this->path}") : null,
        );
    }

    // attribute for url
    protected function url() : Attribute
    {
        return Attribute::make(
            get: function($url) {
                if ($this->mime === 'youtube') return $url;
                if ($this->ulid) return route('__file.get', $this->ulid);
            },
        );
    }

    // attribute for file type
    protected function type() : Attribute
    {
        return Attribute::make(
            get: fn() => collect([
                'youtube' => $this->mime === 'youtube',
                'jsonld' => str($this->mime)->is('*ld+json'),
                'svg' => str($this->mime)->is('*svg+xml'),
                'txt' => str($this->mime)->is('*plain'),
                'word' => str($this->mime)->is('*msword')
                    || str($this->mime)->is('*vnd.openxmlformats-officedocument.wordprocessingml.document'),
                'ppt' => str($this->mime)->is('*vnd.ms-powerpoint')
                    || str($this->mime)->is('*vnd.openxmlformats-officedocument.presentationml.presentation'),
                'excel' => str($this->mime)->is('*vnd.ms-excel')
                    || str($this->mime)->is('*vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                'file' => true,
            ])->filter()->keys()->first(),
        );
    }

    // attribute for icon
    protected function icon() : Attribute
    {
        return Attribute::make(
            get: fn() => collect([
                'image' => ($this->is_image),
                'play' => ($this->is_video),
                'music' => ($this->is_audio),
                'file-word' => ($this->type === 'word'),
                'file-excel' => ($this->type === 'excel'),
                'file-powerpoint' => ($this->type === 'ppt'),
                'file-pdf' => ($this->type === 'pdf'),
                'file' => true,
            ])->filter()->keys()->first(),
        );
    }

    // attribute for embed
    protected function embed() : Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->type === 'youtube' && ($vid = data_get($this->data, 'vid') ?? youtube_vid($this->url))) {
                    $url = 'https://www.youtube.com/embed/'.$vid;
                    return '<iframe class="w-full h-full" src="'.$url.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
                }
            },
        );
    }

    // scope for fussy search
    public function scopeSearch($query, $search) : void
    {
        $query->where(fn($q) => $q
            ->where('name', 'like', "%$search%")
        );
    }

    // scope for mime
    public function scopeMime($query, $mime) : void
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

    // store file content
    public function store($content, $path = 'uploads', $visibility = 'private')
    {
        $file = model('file')->fill([
            'data' => ['env' => app()->environment()],
        ]);

        // url
        if (is_string($content)) {
            if ($vid = youtube($content)->vid()) {
                $info = youtube($content)->info();

                $file->fill([
                    'name' => data_get($info, 'title') ?? $vid,
                    'mime' => 'youtube',
                    'url' => $content,
                    'data' => [
                        ...$file->data,
                        'vid' => $vid,
                        'thumbnail' => data_get($info, 'thumbnail_url'),
                    ],
                ])->save();
            }
            else if ($img = rescue(fn() => Image::make($content), null)) {
                $file->fill([
                    'name' => $content,
                    'mime' => $img->mime(),
                    'url' => $content,
                    'width' => $img->width(),
                    'height' => $img->height(),
                ])->save();
            }
        }
        // file content
        else if ($content->path()) {
            $file->fill([
                'name' => $content->getClientOriginalName(),
                'extension' => $content->extension(),
                'size' => round($content->getSize()/1024, 5),
                'mime' => $content->getMimeType(),
                'disk' => settings('filesystem', 'local'),
            ]);

            $file->putContent($content, $path, $visibility);
        }

        return $file;
    }

    // check is authorized to read file
    public function auth() : bool
    {
        return tier('root') || user('id') === $this->footprint('created.id');
    }

    // response
    public function response() : mixed
    {
        if ($this->storage_path && file_exists($this->storage_path)) {
            return $this->is_image
                ? response()->file($this->storage_path)
                : response()->download($this->storage_path);
        }
        else if ($url = data_get($this->getAttributes(), 'url')) {
            if (in_array($this->disk, ['do', 's3'])) return $this->getStorage()->response($this->path);
            else return redirect()->to($url);
        }
        else return response('File not found', 404);
    }

    // response in base 64
    public function responseInBase64() : mixed
    {
        if ($this->storage_path && file_exists($this->storage_path)) $url = $this->storage_path;
        elseif (in_array($this->disk, ['do', 's3'])) $url = $this->getStorage()->temporaryUrl($this->path, now()->addHour());
        else $url = data_get($this->getAttributes(), 'url');

        $mime = pathinfo($this->path, PATHINFO_EXTENSION);
        $content = file_get_contents($url);
        $b64 = 'data:image/'.$mime.';base64,'.base64_encode($content);

        return $b64;
    }

    // put content
    public function putContent($content, $path, $visibility) : mixed
    {
        $storage = $this->getStorage();
        $config = $storage->getConfig();
        $dest = collect([data_get($config, 'folder'), $path])->filter()->join('/');
        $path = $storage->putFile($dest, $content->path(), $visibility);
        $url = $this->disk !== 'local' ? $storage->url($path) : null;
        $img = str($this->mime)->is('image/*')
            ? rescue(fn() => Image::make($content->path()), null)
            : null;

        $this->fill([
            'url' => $url,
            'path' => $path,
            'width' => optional($img)->width(),
            'height' => optional($img)->height(),
        ])->save();

        if ($img) CreateThumbnails::dispatch($this->fresh());

        return $this;
    }

    // get storage
    public function getStorage() : mixed
    {
        // disk configs
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

        return Storage::disk($this->disk);
    }
}
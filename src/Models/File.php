<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jiannius\Atom\Atom;
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
        'kb' => 'float',
        'width' => 'integer',
        'height' => 'integer',
        'data' => 'array',
    ];

    protected $appends = [
        'type',
        'icon',
        'size',
        'is_image',
        'is_video',
        'is_audio',
        'is_youtube',
        'is_file',
        'endpoint',
        'endpoint_sm',
        'endpoint_md',
    ];

    protected static function booted() : void
    {
        static::creating(function ($file) {
            $file->data = [
                ...($file->data ??  []),
                'env' => app()->environment(),
            ];
        });

        static::deleting(function($file) {
            $file->preventProductionDelete();
            $file->deleteFromDisk();
        });
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(model('file'), 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(model('file'), 'parent_id');
    }

    protected function isImage() : Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'image/'),
        );
    }

    protected function isVideo() : Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'video/'),
        );
    }

    protected function isAudio() : Attribute
    {
        return Attribute::make(
            get: fn() => str()->startsWith($this->mime, 'audio/'),
        );
    }

    protected function isYoutube() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->type === 'youtube',
        );
    }

    protected function isFile() : Attribute
    {
        return Attribute::make(
            get: fn() => !$this->is_image && !$this->is_video && !$this->is_audio && !$this->is_youtube,
        );
    }

    protected function size() : Attribute
    {
        return Attribute::make(
            get: fn() => num()->filesize($this->kb, 'KB'),
        );
    }

    protected function filename() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->path ? last(explode('/', $this->path)) : null,
        );
    }

    protected function storagePath() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->disk === 'local' ? storage_path("app/{$this->path}") : null,
        );
    }

    protected function endpoint() : Attribute
    {
        return Attribute::make(
            get: fn () => $this->getEndpoint(),
        );
    }

    protected function endpointSm() : Attribute
    {
        return Attribute::make(
            get: fn () => $this->getEndpoint('sm'),
        );
    }

    protected function endpointMd() : Attribute
    {
        return Attribute::make(
            get: fn () => $this->getEndpoint('md'),
        );
    }

    protected function type() : Attribute
    {
        return Attribute::make(
            get: function() {
                $mime = str($this->mime);

                if ($mime->is('image/*')) return (string) $mime->replace('image/', '');

                return pick([
                    'youtube' => $mime->is('youtube'),
                    'jsonld' => $mime->is('*ld+json'),
                    'svg' => $mime->is('*svg+xml'),
                    'txt' => $mime->is('*plain'),
                    'ms-word' => $mime->is('*msword') || $mime->is('*vnd.openxmlformats-officedocument.wordprocessingml.document'),
                    'ms-ppt' => $mime->is('*vnd.ms-powerpoint') || $mime->is('*vnd.openxmlformats-officedocument.presentationml.presentation'),
                    'ms-excel' => $mime->is('*vnd.ms-excel') || $mime->is('*vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                    'pdf' => $mime->is('*/pdf'),
                    'video' => $mime->is('video/*'),
                    'audio' => $mime->is('audio/*'),
                    'file' => true,
                ]);
            },
        );
    }

    protected function icon() : Attribute
    {
        return Attribute::make(
            get: fn() => pick([
                'image' => $this->is_image,
                'play' => $this->is_video,
                'music' => $this->is_audio,
                'file-word' => $this->type === 'word',
                'file-excel' => $this->type === 'excel',
                'file-powerpoint' => $this->type === 'ppt',
                'file-pdf' => $this->type === 'pdf',
                'file' => true,
            ]),
        );
    }

    public function scopeSearch($query, $search) : void
    {
        $query->where('name', 'like', "%$search%");
    }

    public function scopeMime($query, $mime) : void
    {
        if (!$mime) return;

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

    public function auth() : bool
    {
        return true;
    }

    public function getEndpoint($size = null, $noauth = false)
    {
        if ($this->is_youtube) return util($this->url)->getYoutubeEmbedUrl();

        $e404 = 'https://placehold.co/300?text=404&font=lato';
        $e403 = 'https://placehold.co/300?text=Error403&font=lato';
        $isDo = $this->isDisk('do', 's3');
        $endpoint = $isDo ? $this->path : (
            $this->url ?? ($this->path ? asset('storage/'.$this->path) : null)
        );

        if (!$endpoint) return $e404;
        if (!$noauth && !$this->auth()) return $e403;

        if ($this->is_image) {
            $endpoint = $this->getThumbnailName($endpoint, $size);

            if ($isDo) $endpoint = $this->getDisk()->temporaryUrl($endpoint, now()->addHour());

            return $endpoint ?? $e404;
        }
        else if ($isDo) return $this->getDisk()->temporaryUrl($endpoint, now()->addHour());
        else return $endpoint;
    }

    public function getBase64($size = null, $noauth = false)
    {
        $endpoint = $this->getEndpoint($size, $noauth);

        if (!$endpoint) return null;

        $ext = pathinfo($endpoint, PATHINFO_EXTENSION);
        $content = file_get_contents($endpoint);

        return 'data:image/'.$ext.';base64,'.base64_encode($content);
    }

    public function getThumbnailWidth($size)
    {
        return match ($size) {
            'sm' => 480,
            'md' => 800,
            default => null,
        };
    }

    public function getThumbnailName($path, $size)
    {
        $width = $this->getThumbnailWidth($size);

        if (!$width) return $path;

        $split = collect(explode('.', $path));
        $ext = $split->pop();

        return $split->push($width.'w')->push($ext)->filter()->join('.');
    }

    public function getDisk()
    {
        return Storage::disk($this->disk);
    }

    public function isDisk(...$name)
    {
        return in_array($this->disk, (array) $name);
    }

    public function preventProductionDelete()
    {
        if (!$this->isDisk('do', 's3')) return;
        if (!$this->path) return;

        $env = get($this->data, 'env', 'production');

        throw_if(
            $env === 'production' && !app()->environment('production'),
            \Exception::class,
            'Do not delete production file in '.app()->environment().' environment!',
        );
    }

    public function store($content, $path = null, $visibility = null)
    {
        return $this->storeYoutube($content)
            ?? $this->storeImageUrl($content)
            ?? $this->storeUploaded(
                content: $content,
                path: $path ?? 'uploads',
                visibility: $visibility ?? 'private',
            );
    }

    public function storeYoutube($content)
    {
        if (!is_string($content)) return;

        $vid = util($content)->getYoutubeVideoId();

        if (!$vid) return;

        $info = util($content)->getYoutubeVideoInfo();

        return model('file')->create([
            'name' => get($info, 'title') ?? $vid,
            'mime' => 'youtube',
            'url' => $content,
            'data' => [
                'vid' => $vid,
                'thumbnail' => get($info, 'thumbnail_url'),
            ],
        ]);
    }

    public function storeImageUrl($content)
    {
        if (!is_string($content)) return;

        $img = getimagesize($content);

        if (!$img) return;

        return model('file')->create([
            'name' => $content,
            'mime' => get($img, 'mime'),
            'url' => $content,
            'width' => get($img, 0),
            'height' => get($img, 1),
        ]);
    }

    public function storeUploaded($content, $path, $visibility)
    {
        if (!$content->path()) return;

        $file = model('file')->fill([
            'name' => $content->getClientOriginalName(),
            'extension' => $content->extension(),
            'kb' => round($content->getSize()/1024, 5),
            'mime' => $content->getMimeType(),
            'disk' => env('FILESYSTEM_DISK', 'local'),
        ]);

        $disk = $file->getDisk();
        $config = $disk->getConfig();
        $dest = collect([get($config, 'folder'), $path])->filter()->join('/');
        $path = $disk->putFile($dest, $content->path(), $visibility);
        $url = $file->disk !== 'local' ? $disk->url($path) : null;
        $img = str($file->mime)->is('image/*') ? getimagesize($content->path()) : null;

        $file->fill([
            'url' => $url,
            'path' => $path,
            'width' => get($img, 0),
            'height' => get($img, 1),
        ]);

        $file->save();

        if ($img) Atom::action('create-file-thumbnails', $file->fresh());

        return $file;
    }

    public function deleteFromDisk()
    {
        if (!$this->path) return;

        // delete thumbnails
        if ($this->is_image) {
            foreach ([
                $this->getThumbnailName($this->path, 'sm'),
                $this->getThumbnailName($this->path, 'md'),
            ] as $path) {
                $this->getDisk()->delete($path);
            }
        }

        $this->getDisk()->delete($this->path);
    }
}

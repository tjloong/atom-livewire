<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'filesize',
        'is_image',
        'is_video',
        'is_audio',
        'is_youtube',
        'is_file',
        'endpoint',
    ];

    // booted
    protected static function booted() : void
    {
        static::deleting(function($file) {
            if ($file->path) {
                $env = get($file->data, 'env', 'production');

                if ($env === 'production' && !app()->environment('production')) {
                    abort(400, 'Do not delete production file in '.app()->environment().' environment!');
                }
                else {
                    $file->children->each(fn($child) => $child->delete());
                    $file->getDisk()->delete($file->path);
                }
            }
        });
    }

    // get parent for file
    public function parent() : BelongsTo
    {
        return $this->belongsTo(model('file'), 'parent_id');
    }

    // get children for file
    public function children() : HasMany
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

    // attribute for is youtube
    protected function isYoutube() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->type === 'youtube',
        );
    }

    // attribute for is file
    protected function isFile() : Attribute
    {
        return Attribute::make(
            get: fn() => !$this->is_image && !$this->is_video && !$this->is_audio && !$this->is_youtube,
        );
    }

    // attribute for filesize
    protected function filesize() : Attribute
    {
        return Attribute::make(
            get: fn() => format($this->size)->filesize('KB'),
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

    // attribute for endpoint
    protected function endpoint() : Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->is_youtube) {
                    if ($vid = youtube($this->url)->vid() ?? get($this->data, 'vid')) {
                        return 'https://www.youtube.com/embed/'.$vid;
                    }
                }

                return route('__file', [
                    'name' => $this->name,
                    'ulid' => $this->ulid,
                ]);
            },
        );
    }

    // attribute for endpoint sm
    protected function endpointSm() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_image ? $this->endpoint.'&sm' : $this->endpoint,
        );
    }

    // attribute for endpoint md
    protected function endpointMd() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_image ? $this->endpoint.'&md' : $this->endpoint,
        );
    }

    // attribute for file type
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
                    'word' => $mime->is('*msword') || $mime->is('*vnd.openxmlformats-officedocument.wordprocessingml.document'),
                    'ppt' => $mime->is('*vnd.ms-powerpoint') || $mime->is('*vnd.openxmlformats-officedocument.presentationml.presentation'),
                    'excel' => $mime->is('*vnd.ms-excel') || $mime->is('*vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                    'pdf' => $mime->is('*/pdf'),
                    'file' => true,
                ]);
            },
        );
    }

    // attribute for icon
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

    // get variants
    public function variants($size = null) : mixed
    {
        $variants = [
            'sm' => $this->children->first(fn($child) => str($child->name)->is('*_480w'))
                ?? $this->children->first(fn($child) => str($child->name)->is('*_512w')),
            'md' => $this->children->first(fn($child) => str($child->name)->is('*_800w'))
                ?? $this->children->first(fn($child) => str($child->name)->is('*_1024w')),
        ];

        if ($size) {
            if ($variant = get($variants, $size)) return $variant;
            else return $this;
        }

        return $variants;
    }

    // scope for fussy search
    public function scopeSearch($query, $search) : void
    {
        $query->where('name', 'like', "%$search%");
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

    // check is authorized to read file
    public function auth() : bool
    {
        return tier('root') || user('id') === $this->footprint('created.id');
    }

    // get disk
    public function getDisk() : mixed
    {
        return Storage::disk($this->disk);
    }

    // store file content
    public function store($content, $path = null, $visibility = null)
    {
        $path = $path ?? 'uploads';
        $visibility = $visibility ?? 'private';
        $file = model('file')->fill([
            'data' => ['env' => app()->environment()],
        ]);

        // url
        if (is_string($content)) {
            // youtube url
            if ($vid = youtube($content)->vid()) {
                $info = youtube($content)->info();
                $file->fill([
                    'name' => get($info, 'title') ?? $vid,
                    'mime' => 'youtube',
                    'url' => $content,
                    'data' => [
                        ...$file->data,
                        'vid' => $vid,
                        'thumbnail' => get($info, 'thumbnail_url'),
                    ],
                ])->save();
            }
            // image url
            else if ($img = getimagesize($content)) {
                $file->fill([
                    'name' => $content,
                    'mime' => get($img, 'mime'),
                    'url' => $content,
                    'width' => get($img, 0),
                    'height' => get($img, 1),
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
            ])->save();
    
            if ($img) {
                \Jiannius\Atom\Jobs\CreateThumbnails::dispatchSync($file->fresh());
            }
        }

        return $file;
    }

    // response
    public function response() : mixed
    {
        if ($this->storage_path && file_exists($this->storage_path)) {
            return $this->is_image
                ? response()->file($this->storage_path)
                : response()->download($this->storage_path);
        }
        else if (in_array($this->disk, ['do', 's3'])  && $this->path) return $this->getDisk()->response($this->path);
        else if ($this->url) return redirect()->to($this->url);
        else return response('File not found', 404);
    }

    // response in base 64
    public function responseInBase64() : mixed
    {
        if ($this->storage_path && file_exists($this->storage_path)) $url = $this->storage_path;
        elseif (in_array($this->disk, ['do', 's3'])) $url = $this->getDisk()->temporaryUrl($this->path, now()->addHour());
        else $url = $this->url;

        if ($url) {
            $mime = pathinfo($this->path, PATHINFO_EXTENSION);
            $content = file_get_contents($url);
            $b64 = 'data:image/'.$mime.';base64,'.base64_encode($content);
    
            return $b64;
        }

        return null;
    }
}
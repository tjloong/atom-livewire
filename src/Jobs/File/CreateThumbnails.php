<?php

namespace Jiannius\Atom\Jobs\File;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManagerStatic as Image;

class CreateThumbnails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // supported extensions
    public $extensions = [
        'jpg', 
        'jpeg', 
        'png', 
        'bmp', 
        'webp',
    ];

    /**
     * Create a new job instance.
     */
    public function __construct(public $file)
    {
        //
    }
 
    /**
     * Execute the job.
     */
    public function handle() : void
    {
        if (in_array($this->file->extension, $this->extensions)) {
            foreach ([512, 1024] as $size) {
                rescue(fn() => $this->putContent($size));
            }
        }
    }

    // put content
    public function putContent($size) : void
    {
        $storage = $this->file->getStorage();
        $name = $this->file->name.'_'.$size.'w';
        $filename = $this->getFilename($size);
        $path = $this->getPath();
        $img = Image::make($storage->get($this->file->path))->orientate();

        if ($img->width() > $size || $img->height() > $size) {
            $temp = storage_path(str()->random().'.'.$this->file->extension);

            $img->resize($size, $size, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($temp, 60);

            $path = $storage->putFileAs($path, $temp, $filename);

            $this->file->thumbnails()->create([
                'name' => $name,
                'mime' => $this->file->mime,
                'size' => round($img->filesize()/1024, 5),
                'disk' => $this->file->disk,
                'path' => $path,
                'url' => $this->file->disk !== 'local' ? $storage->url($path) : null,
                'width' => $img->width(),
                'height' => $img->height(),
                'extension' => $this->file->extension,
                'data' => $this->file->data,
                'created_by' => $this->file->created_by,
            ]);

            unlink($temp);
        }
    }

    // get filename
    public function getFilename($size) : string
    {
        $original = $this->file->filename;
        $splits = collect(explode('.', $original));
        $extension = $splits->pop();

        return $splits->push($size.'w')->push($extension)->join('.');
    }

    // get path
    public function getPath() : string
    {
        $original = $this->file->path;
        $splits = collect(explode('/', $original));
        $splits->pop();

        return $splits->join('/');
    }
}
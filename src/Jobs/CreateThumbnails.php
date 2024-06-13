<?php

namespace Jiannius\Atom\Jobs;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateThumbnails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        foreach ([480, 800] as $size) {
            rescue(function() use ($size) {
                $this->file->children?->each(fn($child) => $child->delete());
                $this->putContent($size);
            });
        }
    }

    // put content
    public function putContent($size) : void
    {
        $storage = $this->file->getDisk();
        $name = $this->file->name.'_'.$size.'w';
        $filename = $this->getFilename($size);
        $path = $this->getPath();
        $img = app('image')->read($storage->get($this->file->path));

        if ($img->width() > $size || $img->height() > $size) {
            $temp = storage_path(str()->random().'.'.$this->file->extension);
            $img->scaleDown($size, $size)->save($temp, 60);
            $path = $storage->putFileAs($path, $temp, $filename);
            $filesize = filesize($temp);

            $this->file->children()->create([
                'name' => $name,
                'mime' => $this->file->mime,
                'size' => round($filesize/1024, 5),
                'disk' => $this->file->disk,
                'path' => $path,
                'url' => $this->file->disk !== 'local' ? $storage->url($path) : null,
                'width' => $img->width(),
                'height' => $img->height(),
                'extension' => $this->file->extension,
                'data' => $this->file->data,
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
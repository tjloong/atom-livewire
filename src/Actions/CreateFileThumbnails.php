<?php

namespace Jiannius\Atom\Actions;
 
class CreateFileThumbnails
{
    public function __construct(public $file)
    {
        //
    }
 
    public function run()
    {
        $sizes = [
            $this->file->getThumbnailWidth('sm'),
            $this->file->getThumbnailWidth('md'),
        ];

        foreach ($sizes as $size) {
            rescue(fn () => $this->createThumbnail($size));
        }
    }

    public function createThumbnail($size) : void
    {
        $disk = $this->file->getDisk();
        $filename = $this->getFilename($size);
        $path = $this->getPath();
        $img = app('image')->read($disk->get($this->file->path));

        if ($img->width() > $size || $img->height() > $size) {
            $temp = storage_path(str()->random().'.'.$this->file->extension);
            $img->scaleDown($size, $size)->save($temp, 60);
            $path = $disk->putFileAs($path, $temp, $filename);
            unlink($temp);
        }
    }

    public function getFilename($size) : string
    {
        $original = $this->file->filename;
        $splits = collect(explode('.', $original));
        $extension = $splits->pop();

        return $splits->push($size.'w')->push($extension)->join('.');
    }

    public function getPath() : string
    {
        $original = $this->file->path;
        $splits = collect(explode('/', $original));
        $splits->pop();

        return $splits->join('/');
    }
}

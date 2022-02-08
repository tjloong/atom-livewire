<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Intervention\Image\ImageManagerStatic as Image;
use Jiannius\Atom\Models\File;
use Jiannius\Atom\Models\SiteSetting;

class Uploader extends Component
{
    use WithFileUploads;

    public $uid;
    public $urls;
    public $tabs;
    public $title;
    public $accept;
    public $multiple;
    public $currentTab;
    public $uploadedFiles;
    public $inputFileTypes;

    /**
     * Mount
     * 
     * @return void
     */
    public function mount(
        $uid = null,
        $title = 'File Manager',
        $multiple = false,
        $sources = ['device', 'image', 'youtube', 'library'],
        $accept = ['image', 'video', 'youtube', 'file']
    ) {
        $this->uid = $uid;
        $this->title = $title;
        $this->multiple = $multiple;
        $this->tabs = $this->getTabs($sources);
        $this->currentTab = $this->tabs->first()['name'];
        $this->accept = $accept;
        $this->inputFileTypes = $this->getInputFileTypes($accept);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('atom::app.file.uploader');
    }

    /**
     * Handle uploaded files
     * 
     * @return void
     */
    public function updatedUploadedFiles()
    {
        $completed = [];

        foreach ($this->uploadedFiles as $file) {
            $dimension = $this->compress($file);
            $path = $file->store('public/uploads');
            $meta = $this->getMeta($file);
            $url = asset('storage/' . str_replace('public/', '', $path));

            // upload file to DO
            if (SiteSetting::getSetting('filesystem') === 'do') {
                if ($disk = SiteSetting::getDoDisk()) {
                    try {
                        $folder = app()->environment('production') ? 'prod' : 'staging';
                        $dopath = $disk->putFile($folder, storage_path("app/$path"), 'public');
                        $cdn = SiteSetting::getSetting('do_spaces_cdn');
                        $url = $cdn . '/' . $dopath;
        
                        // delete the local copy
                        Storage::delete($path);
                    } catch (Exception $e) {
                        logger("Unable to upload $path to Digital Ocean bucket.");
                    }
                }
            }

            $data = ['path' => $dopath ?? $path];
            if ($dimension) $data['dimension'] = $dimension;

            $saved = File::create([
                'name' => $meta['name'],
                'size' => $meta['size'],
                'mime' => $meta['mime'],
                'url' => $url,
                'data' => $data,
            ]);

            array_push($completed, $saved);
        }

        $this->finished($completed);
    }

    /**
     * Handle image/youtube urls
     * 
     * @return void
     */
    public function updatedUrls()
    {
        $completed = [];

        foreach ($this->urls as $url) {
            if ($this->currentTab === 'image') {
                $img = Image::make($url);
                $mime = $img->mime();
    
                $file = File::create([
                    'name' => $url,
                    'mime' => $mime,
                    'url' => $url,
                    'data' => [
                        'dimension' => $img->width() . 'x' . $img->height(),
                    ],
                ]);
            }
            else if ($this->currentTab === 'youtube') {
                $file = File::create([
                    'name' => $url,
                    'mime' => 'youtube',
                    'url' => 'https://www.youtube.com/embed/' . $url,
                    'data' => [
                        'vid' => $url,
                    ],
                ]);
            }

            array_push($completed, $file);
        }

        $this->finished($completed);
    }

    /**
     * Handle select files in library
     * 
     * @return void
     */
    public function selectFiles($ids)
    {
        $files = File::whereIn('id', $ids)->get();

        $this->finished($files);
    }

    /**
     * Emit finished
     * 
     * @param array $files
     * @return void
     */
    public function finished($files)
    {
        $this->emitSelf('finished');
        $this->emitUp($this->uid . '-completed', $files);
        $this->dispatchBrowserEvent($this->uid . '-completed', $files);
    }

    /**
     * Get files for library
     * 
     * @return array
     */
    public function getFiles($page = 1, $search = null)
    {
        return File::query()
            ->where(function($q) {
                $q->when(in_array('image', $this->accept), fn($q) => $q->where('mime', 'like', 'image/%'))
                ->when(in_array('video', $this->accept), fn($q) => $q->orWhere('mime', 'like', 'video/%'))
                ->when(in_array('youtube', $this->accept), fn($q) => $q->orWhere('mime', 'youtube'))
                ->when(in_array('file', $this->accept), fn($q) => $q->orWhere(
                    fn($q) => $q->where('mime', 'not like', 'image/%')->where('mime', 'not like', 'video/%')->where('mime', '<>', 'youtube')
                ));
            })
            ->when($search, fn($q) => $q->search($search))
            ->orderBy('created_at', 'desc')
            ->paginate(50, ['*'], 'page', $page)
            ->toArray();
    }

    /**
     * Get tabs
     * 
     * @return Collection
     */
    private function getTabs($sources)
    {
        $agent = new Agent();
        $tabs = [
            ['name' => 'device', 'label' => $agent->isDesktop() ? 'Computer' : 'Device'],
            ['name' => 'image', 'label' => 'Web Image'],
            ['name' => 'youtube', 'label' => 'Youtube'],
            ['name' => 'library', 'label' => 'Library'],
        ];

        return collect($tabs)
            ->filter(fn($tab) => in_array($tab['name'], $sources))
            ->values();
    }

    /**
     * Get input file types
     * 
     * @return array
     */
    private function getInputFileTypes($accept)
    {
        $types = [];

        if (in_array('image', $accept)) $types = array_merge($types, ['image/png', 'image/jpg', 'image/jpeg', 'image/webp']);
        if (in_array('video', $accept)) $types = array_merge($types, ['video/x-flv', 'video/mp4']);
        if (in_array('file', $accept)) {
            $types = array_merge($types, [
                'application/pdf',
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        return $types;
    }

    /**
     * Get file meta data
     * 
     * @return array
     */
    private function getMeta($file)
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
     * 
     * @return array
     */
    private function compress($file)
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
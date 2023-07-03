<?php

namespace Jiannius\Atom\Http\Livewire\App\Banner;

use Illuminate\Database\Eloquent\Builder;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;
    use WithPopupNotify;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    // get query property
    public function getQueryProperty(): Builder
    {
        return model('banner')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->oldest('seq')->latest('id'));
    }

    // get table columns
    public function getTableColumns($query): array
    {
        return [
            [
                'checkbox' => $query->id,
            ],
            [
                'name' => 'Name',
                'sort' => 'name',
                'label' => $query->name,
                'image' => $query->image->url,
                'href' => route('app.banner.update', [$query->id]),
                'sortable_id' => $query->id,
            ],
            [
                'name' => 'Type',
                'sort' => 'type',
                'label' => $query->type,
            ],
            [
                'name' => 'Start Date',
                'sort' => 'start_at',
                'date' => $query->start_at ?? '--',
            ],
            [
                'name' => 'End Date',
                'sort' => 'end_at',
                'date' => $query->end_at ?? '--',
            ],
            [
                'name' => 'Status',
                'status' => $query->status,
            ],
        ];
    }

    // sort
    public function sort($data): void
    {
        foreach ($data as $seq => $id) {
            model('banner')->find($id)->fill(['seq' => $seq])->save();
        }
    }

    // delete
    public function delete()
    {
        if ($this->checkboxes) {
            model('banner')->whereIn('id', $this->checkboxes)->delete();
            $this->resetCheckboxes();
        }
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.banner.listing');
    }
}
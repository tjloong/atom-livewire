<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithShareable
{
    /**
     * Create shareable
     */
    public function createShareable()
    {
        $shareable = model('shareable')->create();

        $this->createdShareable($shareable);

        return $shareable->toArray();
    }

    /**
     * Created shareable
     */
    public function createdShareable($shareable)
    {
        // override this in consuming class
    }

    /**
     * Regenerate shareable
     */
    public function regenerateShareable($uuid)
    {
        model('shareable')->where('uuid', $uuid)->delete();

        return $this->createShareable();
    }

    /**
     * Update shareable
     */
    public function updateShareable($data)
    {
        if ($shareable = model('shareable')->where('uuid', data_get($data, 'uuid'))->first()) {
            $shareable->fill([
                'valid_for' => empty(data_get($data, 'valid_for'))
                    ? null
                    : data_get($data, 'valid_for'),
                'data' => data_get($data, 'data'),
            ]);

            if ($shareable->isDirty('valid_for')) {
                $shareable->fill([
                    'expired_at' => !empty($shareable->valid_for)
                        ? $shareable->created_at->addDays($shareable->valid_for)
                        : null,
                ]);
            }

            $shareable->save();

            return $shareable->toArray();
        }
    }
}
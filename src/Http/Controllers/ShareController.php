<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class ShareController extends Controller
{
    public function __invoke()
    {
        if ($data = request()->share) {
            $share = model('share')->find(get($data, 'id'));
            $share->fill(request()->share)->save();
        }
        else {
            $entity = app(request()->model)->find(request()->id);
            $share = $entity->share ?? $entity->share()->create(['is_enabled' => true]);

            if (request()->regen) $share->fill(['ulid' => null])->save();
        }

        return [
            ...$share->fresh()->toArray(),
            'notes' => $share->expired_at
                ? tr('app.label.shared-link-will-expired-on', ['date' => format($share->expired_at)->value()])
                : null,
        ];
    }
}
<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class EpinglageController extends Controller
{
    use AuthorizesRequests;

    public function store(Publication $publication): RedirectResponse
    {
        $this->authorize('epingler', $publication);

        $publication->update([
            'epingle_le' => $publication->epingle_le ? null : now(),
        ]);

        return back()->with('succes', $publication->epingle_le
            ? 'Publication épinglée.'
            : 'Publication désépinglée.'
        );
    }
}
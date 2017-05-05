<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Notes\Controllers;

use FI\Modules\Notes\Repositories\NoteRepository;
use FI\Events\NoteCreated;
use FI\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function __construct(NoteRepository $noteRepository)
    {
        parent::__construct();

        $this->noteRepository = $noteRepository;
    }

    public function create()
    {
        $objectType = 'FI\\Modules\\' . request('module') . '\\Models\\' . request('objectType');

        $object = $objectType::find(request('objectId'));

        $note = $this->noteRepository->create($object, request('note'), auth()->user()->id, request('isPrivate'));

        if (auth()->user()->client_id)
        {
            event(new NoteCreated($note));
        }

        return view('notes._notes_list')
            ->with('notes', $object->notes()->protect(auth()->user())->orderBy('created_at', 'desc')->get())
            ->with('showPrivateCheckbox', request('showPrivateCheckbox'));
    }

    public function delete()
    {
        $this->noteRepository->delete(request('id'));
    }
}
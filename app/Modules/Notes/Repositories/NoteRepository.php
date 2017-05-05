<?php

namespace FI\Modules\Notes\Repositories;

use FI\Modules\Notes\Models\Note;

class NoteRepository
{
    public function create($object, $note, $userId, $private = 0)
    {
        return $object->notes()->create(['note' => $note, 'user_id' => $userId, 'private' => $private]);
    }

    public function delete($id)
    {
        Note::destroy($id);
    }
}
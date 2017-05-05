<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Groups\Repositories;

use FI\Modules\Groups\Models\Group;

class GroupRepository
{
    public function all()
    {
        return Group::orderBy('name')->all();
    }

    public function paginate()
    {
        return Group::sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function find($id)
    {
        return Group::find($id);
    }

    public function findIdByName($name)
    {
        if ($group = Group::where('name', $name)->first())
        {
            return $group->id;
        }

        return null;
    }

    public function generateNumber($id)
    {
        $group = Group::find($id);

        $nextId = str_pad($group->next_id, $group->left_pad, '0', STR_PAD_LEFT);

        $number = '';

        if ($group->prefix) $number .= $group->prefix;
        if ($group->prefix_year) $number .= date('Y');
        if ($group->prefix_month) $number .= date('m');

        $number .= $nextId;

        return $number;
    }

    public function incrementNextId($id)
    {
        $group          = Group::find($id);
        $group->next_id = $group->next_id + 1;
        $group->save();
    }

    public function lists()
    {
        return Group::orderBy('name')->lists('name', 'id')->all();
    }

    public function create($input)
    {
        return Group::create($input);
    }

    public function update($input, $id)
    {
        $group = Group::find($id);

        $group->fill($input);

        $group->save();

        return $group;
    }

    public function delete($id)
    {
        Group::destroy($id);
    }
}
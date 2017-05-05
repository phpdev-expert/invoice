<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Groups\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Groups\Validators\GroupValidator;

class GroupController extends Controller
{
    public function __construct(GroupRepository $groupRepository, GroupValidator $groupValidator)
    {
        parent::__construct();
        $this->groupRepository = $groupRepository;
        $this->groupValidator  = $groupValidator;
    }

    public function index()
    {
        $groups = $this->groupRepository->paginate();

        return view('groups.index')
            ->with('groups', $groups);
    }

    public function create()
    {
        return view('groups.form')
            ->with('editMode', false);
    }

    public function store()
    {
        $input = request()->all();

        $validator = $this->groupValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('groups.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $this->groupRepository->create($input);

        return redirect()->route('groups.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $group = $this->groupRepository->find($id);

        return view('groups.form')
            ->with(['editMode' => true, 'group' => $group]);
    }

    public function update($id)
    {
        $input = request()->all();

        $validator = $this->groupValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('groups.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->groupRepository->update($input, $id);

        return redirect()->route('groups.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $this->groupRepository->delete($id);

        return redirect()->route('groups.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }
}
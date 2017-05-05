<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Users\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CustomFields\Repositories\CustomFieldRepository;
use FI\Modules\CustomFields\Repositories\UserCustomRepository;
use FI\Modules\Users\Repositories\UserRepository;
use FI\Modules\Users\Validators\UserValidator;
use Hash;

class UserController extends Controller
{
    public function __construct(
        CustomFieldRepository $customFieldRepository,
        UserCustomRepository $userCustomRepository,
        UserRepository $userRepository,
        UserValidator $userValidator
    )
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepository;
        $this->userRepository        = $userRepository;
        $this->userCustomRepository  = $userCustomRepository;
        $this->userValidator         = $userValidator;
    }

    public function index()
    {
        $users = $this->userRepository->paginate();

        return view('users.index')
            ->with('users', $users);
    }

    public function create()
    {
        return view('users.form')
            ->with('editMode', false)
            ->with('customFields', $this->customFieldRepository->getByTable('users'));
    }

    public function store()
    {
        $input = request()->all();

        if (request()->has('custom'))
        {
            $custom = $input['custom'];
            unset($input['custom']);
        }

        $validator = $this->userValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('users.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        unset($input['password_confirmation']);

        $userId = $this->userRepository->create($input)->id;

        if (request()->has('custom'))
        {
            $this->userCustomRepository->save($custom, $userId);
        }

        return redirect()->route('users.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        return view('users.form')
            ->with(['editMode' => true, 'user' => $user])
            ->with('customFields', $this->customFieldRepository->getByTable('users'));
    }

    public function update($id)
    {
        $input = request()->all();

        if (request()->has('custom'))
        {
            $custom = $input['custom'];
            unset($input['custom']);
        }

        $validator = $this->userValidator->getUpdateValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('users.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->userRepository->update($input, $id);

        if (request()->has('custom'))
        {
            $this->userCustomRepository->save($custom, $id);
        }

        return redirect()->route('users.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $this->userRepository->delete($id);

        return redirect()->route('users.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function ajaxModalLookup()
    {
        return view('users._modal_lookup')
            ->with('id', request('id'))
            ->with('users', $this->userRepository->listsAdmins())
            ->with('refreshFromRoute', request('refresh_from_route'))
            ->with('updateUserIdRoute', request('update_user_id_route'));
    }
}
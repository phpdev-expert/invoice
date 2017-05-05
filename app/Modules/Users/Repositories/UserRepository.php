<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Users\Repositories;

use FI\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function paginate()
    {
        return User::sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function lists()
    {
        return User::orderBy('name')->lists('name', 'id')->all();
    }

    public function listsAdmins()
    {
        return User::where('client_id', 0)->orderBy('name')->lists('name', 'id')->all();
    }

    public function find($id)
    {
        return User::with('custom')->find($id);
    }

    public function findByApiPublicKey($key)
    {
        return User::where('api_public_key', $key)->first();
    }

    public function count()
    {
        return User::count();
    }

    public function create($input)
    {
        $user = new User;

        $user->fill($input);

        $user->password = Hash::make($input['password']);

        $user->save();

        return $user;
    }

    public function update($input, $id)
    {
        $user = User::find($id);

        $user->fill($input);

        $user->save();

        return $user;
    }

    public function updatePassword($password, $id)
    {
        $user = User::find($id);

        $user->password = Hash::make($password);

        $user->save();

        return $user;
    }

    public function delete($id)
    {
        User::destroy($id);
    }
}
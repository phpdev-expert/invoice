<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Clients\Repositories;

use DB;
use FI\Modules\Clients\Models\Client;
use FI\Modules\Users\Models\User;
use FI\Support\BaseRepository;
use Illuminate\Support\Facades\Hash;

class ClientRepository extends BaseRepository
{
    public function paginate($status = null, $filter = null)
    {
        $client = Client::getQuery()->with(['currency']);

        if ($status == 'active')
        {
            $client->where('active', 1);
        }
        elseif ($status == 'inactive')
        {
            $client->where('active', 0);
        }

        if ($filter)
        {
            $client->keywords($filter);
        }

        return $client->sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function find($id)
    {
        $client = Client::getQuery();

        return $client->with($this->with)->where('id', $id)->first();
    }

    public function findIdByListIdentifier($listIdentifier)
    {
        if ($client = Client::select('id')->where('unique_name', $listIdentifier)->first())
        {
            return $client->id;
        }

        return null;
    }

    public function create($input)
    {
        if (isset($input['password']) and $input['password'])
        {
            $password = Hash::make($input['password']);
        }

        unset($input['password'], $input['password_confirmation']);

        $client = new Client;

        $client->fill($input);

        if (isset($input['allow_login']))
        {
            $client->allow_login = $input['allow_login'];
        }

        $client->save();

        if (isset($password))
        {
            $user = new User;

            $user->client_id = $client->id;
            $user->name      = $client->name;
            $user->email     = $client->email;
            $user->password  = $password;

            $user->save();
        }

        return $client;
    }

    public function firstOrCreate($listIdentifier)
    {
        $client = Client::firstOrNew([
            'unique_name' => $listIdentifier
        ]);

        if (!$client->id)
        {
            $client->name = $listIdentifier;
            $client->save();
        }

        return $client;
    }

    public function update($input, $id)
    {
        if (isset($input['password']) and $input['password'])
        {
            $password = Hash::make($input['password']);
        }

        unset($input['password'], $input['password_confirmation']);

        $client = Client::find($id);

        $client->fill($input);

        if (isset($input['allow_login']))
        {
            $client->allow_login = $input['allow_login'];
        }

        $client->save();

        if (isset($password))
        {
            $user = User::firstOrCreate(['client_id' => $client->id]);

            $user->name     = $client->name;
            $user->email    = $client->email;
            $user->password = $password;

            $user->save();
        }
        elseif (count($client->user))
        {
            $user = $client->user;

            $user->name  = $client->name;
            $user->email = $client->email;

            $user->save();
        }

        return $client;
    }

    public function delete($id)
    {
        Client::destroy($id);
    }

    public function nameIsDuplicate($name, $uniqueName, $id)
    {
        if (Client::where(function ($query) use ($name, $uniqueName)
            {
                $query->where('name', $name);
                $query->orWhere('unique_name', $uniqueName);
            })->where('id', '<>', $id)->count() > 0
        )
        {
            return true;
        }

        return false;
    }

    /**
     * Provides a json encoded array of matching client identifiers.
     *
     * @param  string $listIdentifier
     * @return json
     */
    public function lookupByListIdentifier($listIdentifier)
    {
        $clients = Client::select('unique_name')
            ->where('active', 1)
            ->where('unique_name', 'like', '%' . $listIdentifier . '%')
            ->orderBy('unique_name')
            ->get();

        $return = [];

        foreach ($clients as $client)
        {
            $return[]['value'] = $client->unique_name;
        }

        return json_encode($return);
    }
}
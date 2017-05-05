<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Addons\Repositories;

use FI\Modules\Addons\Models\Addon;
use FI\Support\Directory;

class AddonRepository
{
    public function get()
    {
        return Addon::orderBy('name')->get();
    }

    public function getEnabled()
    {
        return Addon::where('enabled', 1)->orderBy('name')->get();
    }

    public function findByName($name)
    {
        return Addon::where('name', $name)->first();
    }

    public function create($input)
    {
        return Addon::create($input);
    }

    public function update($input, $id)
    {
        $addon = Addon::find($id);

        $addon->fill($input);

        $addon->save();

        return $addon;
    }

    public function delete($id)
    {
        Addon::destroy($id);
    }

    public function refreshList()
    {
        $addons = Directory::listDirectories(addon_path());

        foreach ($addons as     $addon)
        {
            $setupClass = 'Addons\\' . $addon . '\Setup';

            $setupClass = new $setupClass;

            $addonRecord = $setupClass->properties;

            if (!Addon::where('name', $addonRecord['name'])->count())
            {
                $addonRecord['path'] = $addon;

                Addon::create($addonRecord);
            }
        }
    }

    public function install($id)
    {
        $addon = Addon::find($id);

        $setupClass = 'Addons\\' . $addon->path . '\Setup';

        $setupClass = new $setupClass;

        $setupClass->install();

        $addon->enabled = 1;

        $addon->save();
    }

    public function uninstall($id)
    {
        $addon = Addon::find($id);

        $setupClass = 'Addons\\' . $addon->path . '\Setup';

        $setupClass = new $setupClass;

        $setupClass->uninstall();

        $addon->enabled = 0;

        $addon->save();
    }
}
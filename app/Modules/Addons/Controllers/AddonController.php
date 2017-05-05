<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Addons\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Addons\Repositories\AddonRepository;

class AddonController extends Controller
{
    public function __construct(AddonRepository $addonRepository)
    {
        parent::__construct();

        $this->addonRepository = $addonRepository;
    }

    public function index()
    {
        $this->addonRepository->refreshList();

        return view('addons.index')
            ->with('addons', $this->addonRepository->get());
    }

    public function install($id)
    {
        $this->addonRepository->install($id);

        return redirect()->route('addons.index');
    }

    public function uninstall($id)
    {
        $this->addonRepository->uninstall($id);

        return redirect()->route('addons.index');
    }
}
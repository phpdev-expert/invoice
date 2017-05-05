<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\ClientCenter\Repositories\ClientCenterPaymentRepository;

class ClientCenterPaymentController extends Controller
{
    public function __construct(ClientCenterPaymentRepository $clientCenterPaymentRepository)
    {
        parent::__construct();
        $this->clientCenterPaymentRepository = $clientCenterPaymentRepository;
        $this->clientId                      = auth()->user()->client->id;
    }

    public function index()
    {
        return view('client_center.payments.index')
            ->with('payments', $this->clientCenterPaymentRepository->paginate($this->clientId));
    }
}
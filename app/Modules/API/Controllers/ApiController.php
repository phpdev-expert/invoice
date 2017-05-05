<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Api\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->validator = App::make('Illuminate\Validation\Factory');
    }
}
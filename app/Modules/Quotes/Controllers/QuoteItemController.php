<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Controllers;

use FI\Modules\Quotes\Repositories\QuoteItemRepository;
use FI\Http\Controllers\Controller;

class QuoteItemController extends Controller
{
    public function __construct(QuoteItemRepository $quoteItemRepository)
    {
        parent::__construct();

        $this->quoteItemRepository = $quoteItemRepository;
    }

    public function delete()
    {
        $this->quoteItemRepository->delete(request('id'));
    }
}
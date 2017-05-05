<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\CustomFields\Repositories;

use FI\Modules\CustomFields\Models\UserCustom;

class UserCustomRepository
{
    public function save($input, $userId)
    {
        $record = (UserCustom::find($userId)) ?: new UserCustom;

        $record->user_id = $userId;

        $record->fill($input);

        $record->save();

        return $record;
    }

    public function delete($id)
    {
        UserCustom::destroy($id);
    }
}
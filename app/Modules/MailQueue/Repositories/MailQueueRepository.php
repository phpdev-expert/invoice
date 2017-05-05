<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\MailQueue\Repositories;

use FI\Modules\MailQueue\Models\MailQueue;

class MailQueueRepository
{
    public function create($object, $input)
    {
        return $object->mailQueue()->create([
            'from'       => json_encode(['email' => $object->user->email, 'name' => $object->user->name]),
            'to'         => json_encode(explode(',', $input['to'])),
            'cc'         => json_encode(explode(',', $input['cc'])),
            'bcc'        => json_encode(explode(',', $input['bcc'])),
            'subject'    => $input['subject'],
            'body'       => $input['body'],
            'attach_pdf' => $input['attach_pdf']
        ]);
    }

    public function getUnsent()
    {
        return MailQueue::where('sent', 0)->get();
    }

    public function delete($id)
    {
        MailQueue::destroy($id);
    }
}
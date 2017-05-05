<?php

namespace FI\Listeners;

use FI\Events\NoteCreated;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;

class NoteCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MailQueueRepository $mailQueueRepository)
    {
        $this->mailQueueRepository = $mailQueueRepository;
    }

    /**
     * Handle the event.
     *
     * @param  NoteCreated $event
     * @return void
     */
    public function handle(NoteCreated $event)
    {
        $this->mailQueueRepository->create($event->note->notable, [
            'to'         => $event->note->notable->user->email,
            'cc'         => config('fi.mailDefaultCc'),
            'bcc'        => config('fi.mailDefaultBcc'),
            'subject'    => trans('fi.note_notification'),
            'body'       => $event->note->formatted_note,
            'attach_pdf' => config('fi.attachPdf')
        ]);
    }
}

<?php

namespace FI\Events;

use Illuminate\Queue\SerializesModels;

class TemplateDefaultChanged extends Event
{
    use SerializesModels;

    public function __construct($template, $originalTemplate, $newTemplate)
    {
        $this->template         = $template;
        $this->originalTemplate = $originalTemplate;
        $this->newTemplate      = $newTemplate;
    }
}

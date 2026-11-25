<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** One mailable for every service email: a template in resources/views/emails plus its variables. */
class TemplateMail extends Mailable
{
    public function __construct(public string $template, public string $mailSubject, public array $vars = [])
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.'.$this->template, with: $this->vars);
    }
}

<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\View;

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
        return new Content(
            view: 'emails.'.$this->template,
            text: 'emails.text',
            with: $this->vars + ['plain' => $this->plain()],
        );
    }

    /** The plain-text twin of the HTML email: same words, links written out, no markup. */
    public function plain(): string
    {
        $html = View::make('emails.'.$this->template, $this->vars)->render();
        $start = (int) strpos($html, '<h1');
        $end = strpos($html, '<tr><td style="padding:16px 28px 20px');
        $body = substr($html, $start, $end === false ? null : $end - $start);

        $body = preg_replace('~<a [^>]*href="([^"]+)"[^>]*>(.*?)</a>~s', '$2: $1', $body);
        $body = preg_replace('~\s+~', ' ', $body);
        $body = preg_replace('~</(p|div|h1|table)>~i', '[[P]]', $body);
        $body = preg_replace('~</tr>~i', '[[N]]', $body);
        $body = str_replace('[[N]]', "\n", strip_tags($body));
        $body = html_entity_decode($body, ENT_QUOTES);
        $paras = array_filter(array_map(fn ($p) => trim(preg_replace('~[ \t]+~', ' ', $p)), explode('[[P]]', $body)), fn ($p) => $p !== '');

        $footer = (Setting::get('seller_name') ?: '[Legal name and address]').' / '.(Setting::get('support_email') ?: '[support address]');

        return implode("\n\n", $paras)."\n\n--\n".$footer;
    }
}

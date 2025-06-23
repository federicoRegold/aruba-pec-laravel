<?php

namespace Regoldidealista\ArubaPecMailer\Contracts;

use Illuminate\Mail\Mailable;

interface ArubaPecMailerInterface
{
    public function account(string $account): self;

    public function send(Mailable $mailable, string|array $to): bool;

    public function sendRaw(string $subject, string $body, string|array $to, array $attachments = []): bool;
}
<?php

namespace Regoldidealista\ArubaPecMailer;

use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Regoldidealista\ArubaPecMailer\Contracts\ArubaPecMailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class ArubaPecMailer implements ArubaPecMailerInterface
{
    protected string $currentAccount = 'default';

    public function account(string $account): self
    {
        if (!array_key_exists($account, config('aruba-pec.accounts'))) {
            throw new Exception("Account '{$account}' does not exist.");
        }
        $this->currentAccount = $account;
        return $this;
    }

    public function send(Mailable $mailable, string|array $to): bool
    {
        try {
            $config = $this->getAccountConfig();
            $mailable->from($config['from']['address'], $config['from']['name']);
            $mailable->to($to);
            config([
                'mail.mailers.aruba-pec-'.$this->currentAccount => [
                    'transport' => 'smtp',
                    'host' => $config['host'],
                    'port' => $config['port'],
                    'encryption' => $config['encryption'],
                    'username' => $config['username'],
                    'password' => $config['password']
                ]
            ]);

            Mail::mailer('aruba-pec-'.$this->currentAccount)->send($mailable);
            return true;
        } catch (Exception $e) {
            Log::error("Errore invio PEC: {$e->getMessage()}");
            return false;
        }
    }

    public function sendRaw(string $subject, string $body, string|array $to, array $attachments = []): bool
    {
        try {
            $config = $this->getAccountConfig();
            $transport = new EsmtpTransport($config['host'], $config['port'], $config['encryption'] === 'ssl');
            $transport->setUsername($config['username']);
            $transport->setPassword($config['password']);
            $email = (new Email())
                ->from($config['from']['address'])
                ->subject($subject)
                ->html($body);

            if (!is_array($to)) {
                $to = [$to];
            }

            foreach ($to as $recipient) {
                $email->addTo($recipient);
            }

            foreach ($attachments as $attachment) {
                if (!is_array($attachment)) {
                    $attachment['path'] = $attachment;
                }
                $email->attachFromPath($attachment['path'], $attachment['name'] ?? null);
            }

            $transport->send($email);
            return true;
        } catch (Exception $e) {
            Log::error("Errore invio PEC raw: {$e->getMessage()}");
            return false;
        }
    }

    protected function getAccountConfig(): array
    {
        $globalConfig = config('aruba-pec.config');
        $accountConfig = config("aruba-pec.accounts.{$this->currentAccount}");
        return (array) array_merge($globalConfig, $accountConfig, ['transport' => 'smtp']);
    }
}
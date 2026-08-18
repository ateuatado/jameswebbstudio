<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestEmail extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:email';
    protected $description = 'Sends a test email to verify SMTP settings.';

    public function run(array $params)
    {
        $to = $params[0] ?? CLI::prompt('Email address to send test to');

        if (empty($to)) {
            CLI::error('You must provide an email address.');
            return;
        }

        $email = \Config\Services::email();

        $email->setTo($to);
        $email->setSubject('Test Email from James Webb Studio');
        $email->setMessage('<p>This is a test email sent via CodeIgniter CLI using the configured SMTP settings.</p>');

        CLI::write("Sending email to {$to}...", 'yellow');

        if ($email->send()) {
            CLI::write('Email successfully sent!', 'green');
        } else {
            CLI::error('Failed to send email.');
            CLI::write($email->printDebugger(['headers', 'subject', 'body']));
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Functions\WhatsApp;
use Illuminate\Console\Command;

class BillsPayable extends Command
{
    protected $signature = 'bills-payable';

    protected $description = 'Send Bills need to pay from student at this moment based on ...';

    public function handle()
    {
        WhatsApp::SendWhatsApp(['201208601044'], 'bills payable');
    }
}

<?php

namespace App\Console\Commands;

use App\Functions\WhatsApp;
use Illuminate\Console\Command;

class SendMeetingsContent extends Command
{
    protected $signature = 'meetings-content';

    protected $description = 'Send Content For Teachers Based on Meeting number  == Pacing Guide Lesson number';

    public function handle()
    {
        WhatsApp::SendWhatsApp(['201208601044'], 'Meeting Content');
    }
}

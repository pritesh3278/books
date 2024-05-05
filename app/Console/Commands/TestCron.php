<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Mail;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send name using cron jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = array('data'=>'Cron Testing');
        Mail::send("mail",$data,function($message){
            $message->to('pritesh3278@gmail.com')
            ->subject('cron testing mail example');
        });
    }
}

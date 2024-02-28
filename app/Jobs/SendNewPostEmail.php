<?php

namespace App\Jobs;

use App\Mail\NewPostEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewPostEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(public $incoming)
    {
      
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->incoming['sendTo'])->send(new NewPostEmail(['name'=>$this->incoming['name'],'title'=>$this->incoming['title']]));
    }
}

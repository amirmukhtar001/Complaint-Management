<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendUpdateStatusEmail;
use Illuminate\Support\Facades\Mail;

use Exception;
use Twilio\Rest\Client;

class SendStatusUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $complaint;
    public $complaintUser;
    public $complainUserPhone;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($complaint, $complaintUser, $complainUserPhone)
    {
        $this->complaint = $complaint;
        $this->complaintUser = $complaintUser;
        $this->complainUserPhone = $complainUserPhone;        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $receiverNumber = $this->complainUserPhone;
        $message = "This is testing msg from Amir Mukhtar";
  
        try {
  
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
  
        } catch (Exception $e) {
            // dd("Error: ". $e->getMessage());
        }

        $email = new SendUpdateStatusEmail($this->complaint);
        Mail::to($this->complaintUser)->send($email);
    }
}

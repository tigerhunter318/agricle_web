<?php
  
namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  
class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $details;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $bccAry = [];
        return $this->subject('【もぐリコ】登録完了のお知らせ')
                    ->from($address = 'info-support@mogrico.com', $name = 'info-support')      
                    ->bcc($bccAry)                         
                    ->view('emails.myRegisterMail');
    }
}
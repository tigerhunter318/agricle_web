<?php
  
namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
  
class PublicMail extends Mailable
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
        return $this->subject('【もぐりこ】審査結果のご報告☆保存版☆ ID/パスワードのご連絡')
                    ->from($address = 'info-support@mogrico.com', $name = 'info-support')      
                    ->bcc($bccAry)                         
                    ->view('emails.myPublicMail');
    }
}
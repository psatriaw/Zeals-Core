<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersonalizedDemo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $fname;
    public $cname;
    public $email;
    public $phone;
    public $notes;

    public function __construct($fullName,$email,$companyName,$phoneNumber,$notes)
    {
        $this->fname = $fullName;
        $this->cname = $companyName;
        $this->email = $email;
        $this->phone = $phoneNumber;
        $this->notes = $notes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'type' => 'personal_demo',
            'title' => 'New Demo Request',
            // 'text' => 
            //     'Nama PIC : '.$this->fname.'<br>'
            //     .'Perusahaan : '.$this->cname.'<br>'
            //     .'Email : '.$this->email.'<br>'
            //     .'No. Telp : '.$this->phone.'<br>'
            //     .'Notes :<br><br>'
            //     .$this->notes,
            'fname'=>$this->fname,
            'cname'=>$this->cname,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'notes'=>$this->notes,
            'logo' => public_path('logo.png')
        ];
        return $this
            ->subject('Internal Email')
            ->markdown('backend.email.demo')
            ->with(
                $data
            );
    }
}

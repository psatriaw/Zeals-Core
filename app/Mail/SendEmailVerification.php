<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $name_user;
    public $code_verification;

    public function __construct($name_user, $code_verification)
    {
        //
        $this->name_user = $name_user;
        $this->code_verification = $code_verification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'type' => 'email_verification',
            'title' => 'Verifikasi Akun',
            'text_top' => 'Hai <b>' . $this->name_user . '</b>, Selamat bergabung bersma <b>Urun Mandiri</b> <br> mohon memasukkan nomor di bawah ini sebagai langkah verifikasi yang dibutuhkan',
            'code_verification' => $this->code_verification,
            'text_bottom' => 'Anda mendapatkan email ini karena Anda terdaftar di platform Urun Mandiri. Setiap email yang dikirimkan ke alamat ini merupakan bentuk interaksi guna pemberitahuan informasi terkait kejadian yang perlu diketahui oleh pengguna kami pada platform',
            'logo' => public_path('logo.png')
        ];

        return $this->from('mandiriurun@gmail.com')
            ->subject('Verifikasi Akun Urun Mandiri')
            ->view('backend.email.index')
            ->with(
                $data
            );
    }
}

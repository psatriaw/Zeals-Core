<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRUPSFinal extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $waktu;
    protected $hasil;
    protected $penerbit;
    protected $campaign;

    public function __construct($ea, $hasil, $penerbit, $campaign)
    {
        //
        $this->email = $ea;
        $this->hasil = $hasil;
        $this->penerbit = $penerbit;
        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data = [
            'type' => 'rups-final',
            'title' => 'Pemberitahuan RUPS Final',
            'text_top' => 'Hai, Perusahaan yang Anda danai telah melakukan Rapat Umum Pemegang Saham (RUPS) secara final, maka Kami informasikan hasilnya seperti berikut :',
            'hasil' => $this->hasil,
            'penerbit' => $this->penerbit,
            'campaign' => $this->campaign,
            'text_bottom' => 'Anda mendapatkan email ini karena Anda terdaftar di platform Urun Mandiri. Setiap email yang dikirimkan ke alamat ini merupakan bentuk interaksi guna pemberitahuan informasi terkait kejadian yang perlu diketahui oleh pengguna kami pada platform',
            'logo' => public_path('logo.png')
        ];


        return $this->from('mandiriurun@gmail.com', 'Urun Mandiri')
            ->subject('RUPS Final ' . $data['campaign'])
            ->view('backend.email.rups-final', $data);
    }
}

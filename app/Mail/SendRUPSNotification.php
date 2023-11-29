<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRUPSNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $email;
    protected $waktu;
    protected $agenda;
    protected $penerbit;
    protected $campaign;

    public function __construct($ea, $waktu, $agenda, $penerbit, $campaign)
    {
        //
        $this->email = $ea;
        $this->waktu = $waktu;
        $this->agenda = $agenda;
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
            'type' => 'rups-notification',
            'title' => 'Pemberitahuan RUPS',
            'text_top' => 'Hai Perusahaan yang Anda danai akan melakukan Rapat Umum Pemegang Saham (RUPS), Anda diaharapkan hadir dalam rapat tersebut. Berikut Kami informasikan informasi terkait informasi RUPS perusahaan yang Anda danai :',
            'waktu' => $this->waktu,
            'agenda' => $this->agenda,
            'penerbit' => $this->penerbit,
            'campaign' => $this->campaign,
            'text_bottom' => 'Anda mendapatkan email ini karena Anda terdaftar di platform Urun Mandiri. Setiap email yang dikirimkan ke alamat ini merupakan bentuk interaksi guna pemberitahuan informasi terkait kejadian yang perlu diketahui oleh pengguna kami pada platform',
            'logo' => public_path('logo.png')
        ];


        return $this->from('mandiriurun@gmail.com', 'Urun Mandiri')
            ->subject('Jadwal RUPS ' . $this->campaign)
            ->view('backend.email.rups-notif', $data);
    }
}

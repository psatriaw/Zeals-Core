<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $invoice_number;
    protected $nama_user;
    protected $tipe_pembayaran;
    protected $nominal;
    protected $email;
    protected $status;

    public function __construct($email, $invoice_number, $nama_user, $tipe_pembayaran, $nominal, $status, $bank, $va_number)
    {
        //
        $this->invoice_number = $invoice_number;
        $this->nama_user = $nama_user;
        $this->tipe_pembayaran = $tipe_pembayaran;
        $this->nominal = $nominal;
        $this->status = $status;
        $this->email = $email;
        $this->bank = $bank;
        $this->va_number = $va_number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'type' => 'invoice',
            'title' => 'Invoice Notification',
            'text_top' => 'Hai ' . $this->nama_user . '. Kamu baru saja melakukan permintaan transaksi dengan nomor transaksi <b>' . $this->invoice_number . '</b>. Silahkan melakukan pembayaran sesuai dengan tagihan di bawah ini',
            'text_top_success' => 'Hai ' . $this->nama_user . '. Kamu baru saja berhasil melakukan transaksi dengan nomor transaksi <b>' . $this->invoice_number . '</b> sebesar Rp. ' . number_format($this->nominal),
            'invoice_number' => $this->invoice_number,
            'nominal' => $this->nominal,
            'status' => $this->status,
            'bank' => $this->bank,
            'va_number' => $this->va_number,
            'text_bottom' => 'Anda mendapatkan email ini karena Anda terdaftar di platform Urun Mandiri. Setiap email yang dikirimkan ke alamat ini merupakan bentuk interaksi guna pemberitahuan informasi terkait kejadian yang perlu diketahui oleh pengguna kami pada platform',
            'logo' => public_path('logo.png')
        ];

        return $this->from('mandiriurun@gmail.com')
            ->subject('Invoice ' . $this->invoice_number)
            ->view('backend.email.invoice')
            ->with(
                $data
            );
    }
}

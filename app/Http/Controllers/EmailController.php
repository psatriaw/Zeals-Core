<?php

namespace App\Http\Controllers;

use App\Http\Models\UserModel;
use App\Mail\SendEmailVerification;
use App\Mail\SendInvoice;
use App\Mail\SendRUPSFinal;
use App\Mail\SendRUPSNotification;
use App\Mail\SendRUPSSementara;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{

    //
    public function sendVerificationCode(Request $request)
    {
        $email = $request->email;
        $id_user = $request->id_user;
        $user = UserModel::where('email', $email)->first();
        $code_verification = DB::table('tb_email_confirmation')->where('id_user', $id_user)->first();

        $kirim = Mail::to($email)->send(new SendEmailVerification($user->first_name, $code_verification->code));

        return response([
            'status' => 'success',
            'response' => 'success send activation!'
        ]);
    }

    public function sendInvoiceInformation($email, $invoice_number, $nama_user, $tipe_pembayaran, $nominal, $status, $bank, $va_number)
    {
        try {
            $kirim = Mail::to($email)->send(new SendInvoice($email, $invoice_number, $nama_user, $tipe_pembayaran, $nominal, $status, $bank, $va_number));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendRUPSNotification($emailAll, $waktu, $agenda, $penerbit, $campaign)
    {
        try {
            foreach ($emailAll as $ea) {
                $kirim = Mail::to($ea)->send(new SendRUPSNotification($ea, $waktu, $agenda, $penerbit, $campaign));
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function SendRUPSSementara($emailAll, $hasil, $penerbit, $campaign)
    {
        try {
            foreach ($emailAll as $ea) {
                $kirim = Mail::to($ea)->send(new SendRUPSSementara($ea, $hasil, $penerbit, $campaign));
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function SendRUPSFinal($emailAll, $hasil, $penerbit, $campaign)
    {
        try {
            foreach ($emailAll as $ea) {
                $kirim = Mail::to($ea)->send(new SendRUPSFinal($ea, $hasil, $penerbit, $campaign));
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

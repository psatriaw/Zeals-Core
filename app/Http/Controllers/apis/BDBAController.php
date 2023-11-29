<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Models\BDBACompany;
use App\Models\BDBAJudger;
use App\Models\BDBAScoring;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BDBAController extends Controller
{
        public function register(Request $request)
        {

                $data = array(
                        "full_name"     => $request->input("full_name"),
                        "institution"   => $request->input("institution"),
                        "email"         => $request->input("email"),
                        "title"         => $request->input("title"),
                        "password"      => md5($request->input("password")),
                        "phone_number"  => $request->input("phone_number"),
                        "status"        => $request->input("status"),
                        "role"        => $request->input("role"),
                        "created_at"  => time(),
                        "updated_at"   => time(),
                );

                $rules  = array(
                        "full_name"     => "required",
                        "institution"   => "required",
                        "title"         => "required",
                        "email"         => ["required", Rule::unique('tb_bdba_judger')],
                        "password"      => 'required',
                        "phone_number"  => 'required',
                );

                $messages = array(
                        "full_name.required"            => "Please fill your name field!",
                        "institution.required"          => "Please fill institution name field!",
                        "title.required"                => "Please fill title field!",
                        "email.required"                => "Please fill email field!",
                        "email.email"                   => "Please fill email field with valid email format, example: name@domain.com",
                        "email.unique"                  => "Email already used by another user! Please select another valid email",
                        "password.required"             => "Please fill password field!",
                        "phone_number.required"         => "Please fill phone field!",
                );

                $validator = Validator::make($data, $rules, $messages);

                if ($validator->fails()) {
                        return response()->json([
                                'status' => 'error',
                                "messages"  => 'Your email has been registered by another user!'
                        ]);
                } else {
                        $createuser     = BDBAJudger::create($data);
                        if ($createuser) {
                                $dataemail                    = $data;
                                $dataemail['user_id'] = $createuser->id;
                                Mail::send('emails.bdba_change_password', $dataemail, function ($mail) use ($dataemail) {
                                        $emailsetting = new Setting();

                                        $sender             = $emailsetting->where("code_setting", "email_sender_name")->first()->setting_value;
                                        $senderaddress      = $emailsetting->where("code_setting", "email_sender_address")->first()->setting_value;

                                        $mail->from($senderaddress, $sender);
                                        $mail->to($dataemail['email'], $dataemail['full_name']);
                                        $mail->subject('Zeals Asia Digital Brand Award - Password Reset');
                                });
                                return response()->json([
                                        'status' => 'success',
                                        "messages"  => 'Account successfully created! Please reset the password from the link we send to your E-mail!',
                                ]);
                        } else {
                                return response()->json([
                                        'status' => 'error',
                                        "messages"  => 'Failed to create account'
                                ]);
                        }
                }
        }

        public function getListJudger()
        {
                $token = str_replace('Bearer ', '', request()->header('Authorization'));

                $user = BDBAJudger::where('token', $token)->first();
                if ($user) {
                        $judger = BDBAJudger::where('role', '!=', 'admin')->get();
                        if ($judger) {
                                return response()->json([
                                        'status' => 'success',
                                        'user' => $judger,
                                ]);
                        } else {
                                return response()->json([
                                        'status' => 'error',
                                        'message' => 'Error response',
                                ]);
                        }
                } else {
                        return response()->json([
                                'status' => 'error',
                                'message' => '404 response',
                        ]);
                }
        }

        public function destroy($id)
        {
                $company = BDBACompany::find($id);

                if (!$company) {
                        return response()->json(['message' => 'Company not found'], 404);
                }

                $company->delete();

                return response()->json(['message' => 'Company deleted successfully']);
        }

        public function destroyJury($id)
        {
                $jury = BDBAJudger::find($id);

                if (!$jury) {
                        return response()->json([
                                'status' => 'success',
                                'message' => 'jury not found'
                        ], 404);
                }
                $jury->status = 'inactive';
                $jury->save();

                return response()->json([
                        'status' => 'success',
                        'jury' => $jury,
                        'message' => 'Jury has been deactive'
                ]);
        }

        function generateRandomToken($length = 32)
        {
                return Str::random($length);
        }

        function addCompany(Request $request)
        {
                $companyName = $request->input('company_name');
                $award_nomination = $request->input('award_nomination');

                $data = array(
                        'company_name' => $companyName,
                        'award_nomination' => $award_nomination,
                        "created_at"  => time(),
                        "updated_at"   => time(),
                );

                $addCompany = BDBACompany::create($data);

                if ($addCompany) {
                        return response()->json([
                                'status' => 'success',
                                'company_name' => $companyName,
                                'award_nomination' => $award_nomination,
                                'message' => 'Company Stored!',
                        ]);
                } else {
                        return response()->json([
                                'status' => 'error',
                                'message' => 'Something Wrong!',
                        ]);
                }
        }

        public function getCompany()
        {
                $token = str_replace('Bearer ', '', request()->header('Authorization'));

                $user = BDBAJudger::where('token', $token)->first();
                if ($user) {
                        $company = BDBACompany::all();
                        if ($company) {
                                return response()->json([
                                        'status' => 'success',
                                        'company' => $company,
                                ]);
                        } else {
                                return response()->json([
                                        'status' => 'error',
                                        'message' => 'Error response',
                                ]);
                        }
                } else {
                        return response()->json([
                                'status' => 'error',
                                'message' => '404 response',
                        ]);
                }
        }

        public function login(Request $request)
        {
                $email = $request->input('email');
                $password = $request->input('password');
                $user = BDBAJudger::where('email', $email)->first();

                if ($user && $user->password === md5($password)) {
                        if ($user->status === 'active') {

                                $token = $this->generateRandomToken(32);
                                $user->token = $token;
                                $user->save();
                                if ($user->role === 'admin') {
                                        $response = [
                                                'status' => 'success',
                                                'token' => $token,
                                                'user' => $user,
                                        ];
                                } else {
                                        $response = [
                                                'status' => 'success',
                                                'token' => $token,
                                                'user' => $user,
                                        ];
                                }
                        } else {
                                $response = [
                                        'status' => 'error',
                                        'message' => 'Your account still not been activated',
                                ];
                        }
                } else {
                        $response = [
                                'status' => 'error',
                                'message' => 'Invalid credentials / your account still not been activated',
                        ];
                }

                return response()->json($response, $user ? 200 : 401);
        }

        public function getUser()
        {
                $token = str_replace('Bearer ', '', request()->header('Authorization'));

                $user = BDBAJudger::where('token', $token)->first();

                if (!$user) {
                        return response()->json(['message' => 'Unauthorized'], 401);
                }

                return response()->json([
                        'status' => 'success',
                        'user' => $user,
                ]);
        }

        public function resetPassword(Request $request)
        {
                $newPass = $request->input('password');
                $id = $request->input('id');

                $user = BDBAJudger::find($id);

                if (!$user) {
                        return response()->json([
                                'status' => 'error',
                                'message' => 'User not found',
                        ]);
                }
                $rules = [
                        'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-_@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ];

                $messages = [
                        'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
                ];

                $validator = Validator::make(['password' => $newPass], $rules, $messages);

                if ($validator->fails()) {
                        return response()->json([
                                'status' => 'error',
                                'message' => $validator->errors()->first(),
                        ]);
                }

                $user->password = md5($newPass);
                $user->status = 'active';
                $user->save();

                $findJudger = BDBAJudger::where('id', $id)->first();

                if ($findJudger) {
                        $full_name = $findJudger->full_name;
                        $email = $findJudger->email;

                        $dataemail = [
                                'full_name' => $full_name,
                                'email' => $email,
                        ];

                        Mail::send('emails.bdba_confirm_change_password', $dataemail, function ($mail) use ($dataemail) {
                                $emailsetting = new Setting();

                                $sender = $emailsetting->where("code_setting", "email_sender_name")->first()->setting_value;
                                $senderaddress = $emailsetting->where("code_setting", "email_sender_address")->first()->setting_value;

                                $mail->from($senderaddress, $sender);
                                $mail->to($dataemail['email'], $dataemail['full_name']);
                                $mail->subject('Zeals Asia Digital Brand Award - Confirm Password Change');
                        });

                        // Email sent successfully
                        return response()->json([
                                'status' => 'success',
                                'message' => 'Password has change, now you can login with your account',
                        ]);
                } else {
                        // User with the given ID not found
                        return response()->json([
                                'status' => 'error',
                                'message' => 'User not found',
                        ]);
                }
        }

        public function StoreScore(Request $request)
        {
                $user = $request->input('user');
                $scores = $request->input('scores');
                $company_id = $request->input('company_id');
                $data = array(
                        'judger_id' => $user['id'],
                        'company_id' => $company_id,
                        'score' => json_encode($scores),
                        "created_at"  => time(),
                        "updated_at"   => time(),
                );
                if ($user) {
                        BDBAScoring::create($data);
                        return response()->json([
                                'status' => 'success',
                                'message' => 'Data Stored!',
                        ]);
                } else {
                        return response()->json([
                                'status' => 'error',
                                'message' => "You are not authenticate",
                        ]);
                }
        }

        public function getScoring()
        {
                $token = str_replace('Bearer ', '', request()->header('Authorization'));

                $user = BDBAJudger::where('token', $token)->first();

                $data = BDBAScoring::where('judger_id', $user->id)
                        ->join('tb_bdba_company', 'tb_bdba_scoring.company_id', '=', 'tb_bdba_company.id')
                        ->select('tb_bdba_scoring.*', 'tb_bdba_company.company_name as company_name', 'tb_bdba_company.award_nomination as award_nomination')
                        ->get();
                return response()->json([
                        'status' => 'success',
                        'score' => $data,
                ]);
        }

        public function getAllScoring()
        {
                $token = str_replace('Bearer ', '', request()->header('Authorization'));

                $user = BDBAJudger::where('token', $token)->first();

                $data = BDBAScoring::where('judger_id', $user->id)
                        ->join('tb_bdba_company', 'tb_bdba_scoring.company_id', '=', 'tb_bdba_company.id')
                        ->select('tb_bdba_scoring.*', 'tb_bdba_company.company_name as company_name')
                        ->get();
                return response()->json([
                        'status' => 'success',
                        'score' => $data,
                ]);
        }

        public function getDetailScoring($id)
        {
                $token = str_replace('Bearer ', '', request()->header('Authorization'));

                $user = BDBAJudger::where('token', $token)->first();

                $data = BDBAScoring::where('judger_id', $user->id)
                        ->where('id', $id)
                        ->get();

                return response()->json([
                        'status' => 'success',
                        'companyInfo' => $data,
                ]);
        }

        public function getDetailAdmin($id)
        {
                $data = BDBAScoring::where('company_id', $id)
                        ->join('tb_bdba_judger', 'tb_bdba_scoring.judger_id', '=', 'tb_bdba_judger.id')
                        ->join('tb_bdba_company', 'tb_bdba_scoring.company_id', '=', 'tb_bdba_company.id')
                        ->select('tb_bdba_scoring.*', 'tb_bdba_judger.full_name as full_name', 'tb_bdba_company.company_name as company_name')
                        ->get();

                return response()->json([
                        'status' => 'success',
                        'companyInfo' => $data,
                ]);
        }
}

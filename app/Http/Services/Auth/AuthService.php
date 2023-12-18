<?php

namespace App\Http\Services\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Mail\OtpMailer;
use GuzzleHttp\Middleware;
use App\Mail\DeviceChanged;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\MailOTP;
use Illuminate\Support\Facades\DB;

use GuzzleHttp\Handler\CurlHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;
use Stancl\Tenancy\Database\Models\TenantDatabase;
use Stancl\Tenancy\Database\Models\CentralDatabase;

class AuthService
{
    public function onLogin(Request $request): array | Request
    {
        if ($request->has('email') && $request->has('password')) {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            $previousPage = $request->input('previousPage');
            if (Auth::attempt($credentials, $request->rememberme)) {
                return $this->login($previousPage);
            // } 
            // elseif (Auth::guard('tenant')->attempt($credentials, $request->rememberme)) {
                // return $this->login($previousPage);
            } else {
                return ["status" => 404, "error" => "Invalid Credentials"];
            }
        } else {
            return ["status" => 404, "error" => "Invalid Credentials"];
        }
    }

    private function login($previousPage)
    {
        return $this->saveUserAgent($previousPage);
    }

    /**
     * Undocumented function
     * @var \App\Models\User $user
     * @var \App\Models\Tenants\User $user
     * @return void
     */
    private function saveUserAgent($previousPage)
    {
        try {
            $user = User::find(auth()->id());
            return ['status' => 200, 'user' => $user, 'redirectUrl' => 'client/dashboard'];
        } catch (\Exception $e) {
            // Handle the exception, e.g., log the error
            Log::error($e->getMessage());
            // Return an error response to the client
            return ['error' => 'Failed to create token', 'status' => 500];
        }
    }

    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|required|unique:users',
                'password' => 'required|min:8',
            ]);
            if ($validator->fails()) {
                return ['error' => $validator->errors(), 'status' => 401];
            }

            $data = $request->only([
                'email',
                'password',
                'names'
            ]);

            $data['password'] = bcrypt($data['password']);
            $previousPage = $request->input('previousPage');
            
            $user = new User();
            $newUser = $user->create($data);        
            if ($newUser) {
                $user->notify(new SignupActivate($newUser));
                // Temporal token
                auth()->login($newUser);
                return ['user' => $newUser, 'status' => 201, 'redirectUrl' => '/onboarding/choose-domain-n-email'];
            } else {
                return ['Creation error', 'status' => 400];
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            // Return an error response to the client
            return ['error' => 'Unable to sign you up', 'status' => 500];
        }
    }

    public function pre_login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $users = new User();
        $user = $users->where('email', $credentials['email'])->first();
        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                if ($user->visits !== null) {
                    if ($user->device_id !== $this->getUserAgent()) {
                        $otp = $this->genOTP();
                        $crypted = Crypt::encryptString($otp);
                        $serverAgent = $_SERVER['HTTP_USER_AGENT'];
                        Mail::to($user->email)->send(new DeviceChanged($otp, $serverAgent));
                        return ['status' => 422, 'message' => 'Device changed', 'otp' => $crypted];
                    } else {
                        return $this->login($request);
                    }
                } else {
                    return $this->login($request);
                }
            } else {
                $response = ["status" => 404, "error" => "Invalid Credentials"];
                return $response;
            }
        } else {
            $response = ['status' => 404, "error" => 'Invalid Credentials'];
            return $response;
        }
    }

    private function getUserAgent()
    {
        $serverAgent = $_SERVER['HTTP_USER_AGENT'];
        $firstParenthesis = strpos($serverAgent, '(');
        $secondParenthesis = strpos($serverAgent, ')');
        $string = substr($serverAgent, $firstParenthesis, $secondParenthesis - $firstParenthesis + 1);
        return $string;
    }

    private function generateOTP($email)
    {
        $otp = rand(1000, 9999);
        $characters = str_split($otp);
        $spacedOTP = implode(' ', $characters);
        $emailOtp = DB::table('email_otps');
        $foundMail = $emailOtp->where('email', $email)->first();

        if (!empty($foundMail)) {
            $emailOtp->where('id', $foundMail->id)->update(['email' => $email, 'otp' => $otp, "created_at" =>  Carbon::now(), "updated_at" => Carbon::now()]);
        } else {
            $emailOtp->insert(['email' => $email, 'otp' => $otp, "created_at" =>  Carbon::now(), "updated_at" => Carbon::now()]);
        }

        return $spacedOTP;
    }

    public function savenSendOtp(Request $request)
    {
        #TODO: Check if any user has email already.
        // If tenant has email whether
        try {
            $previousPage = $request->input('previousPage');
            $email = $request->get('email');
            $users = new User();
            if (strpos($previousPage, '/edit') !== false) { //tenant() && strpos($previousPage, '/edit') !== false
                // If tenant and also edit flag
                    // Check both user and userTenant
                // Elseif only tenant incoming
                    // Check only user
                // Elseif !tenant
                    // Check only user
                $connectionName = 'mysql';
                $userMail = $users->where('email', $email)->first();
                $userRole = $users->where('role', 'Admin')->first();
                $userCentral = DB::connection($connectionName)->table('users')->where('email', $email)->first();
                if ($userMail && $userCentral && $userRole) {
                    return ['msg' => 'Email Already in use!', 'status' => 401];
                } else {
                    return $this->helpSavenSend($email, $previousPage);
                }
            } else {
                $userMail = $users->where('email', $email)->first();
                if(empty($userMail)) {
                    return $this->helpSavenSend($email);
                }
                else {
                    return ['msg' => 'Email Already in use!', 'status' => 401];
                }
            }
        
            
        } catch (ValidationException $e) {
            // Handle validation errors
            return ['error' => 'Email already in use', 'status' => 401];
        } catch (\Throwable $e) {
            // Handle other exceptions
            Log::error($e->getMessage());
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }

    private function helpSavenSend($email, $previousPage = null) {
        // If validation passes, proceed with generating and sending OTP
        $otp = $this->generateOTP($email);
    
        // Determine the email type (e.g., Registration or Forgot Password)
        $emailType = [
            'header' => 'Verify Your E-mail Address',
            'body' => "You're almost ready to get started. Please find below your OTP",
        ];
    
        // Check if there's a tenant and fetch the brand name, or use 'Zebraline' as the default
        $brand = 'Zebraline';
        $domainName = 'support@zebraline.ai';
        // if (tenant()) {
        //     $tenant = DB::connection('mysql')->table('tenants')->where('id', tenant()->id)->first();
        //     $domain = DB::connection('mysql')->table('webcreations')->where('tenant_id', tenant()->id)->first();
        //     if ($domain !== null && !strpos($previousPage, '/edit')) {
        //         $tenantDomain = $domain->domainName;
        //         $domainName = "<inquiry@$tenantDomain>";
        //     }
        //     if ($tenant) {
        //         $tenantData = json_decode($tenant->site_data);
        //         if (isset($tenantData->brandName) && !strpos($previousPage, '/edit') !== false) {
        //             $brand = $tenantData->brandName;
        //             $themeColor = $tenantData->themeColor;
        //         }
        //     }
        // }
        // if (tenant() !== null && strpos($previousPage, '/edit') === false) {
        //     return $this->sendOtpMail($domainName, $email, $otp, $brand, $emailType, $themeColor);
        // } else {
            // Send the OTP via email
            Mail::to($email)->send(new OtpMailer($otp, $brand, $emailType, $domainName));
            return ['message' => 'OTP sent successfully', 'status' => 200];
        // }
    }

    public function sendOtpMail($domainName, $email, $otp, $brand, $type, $themeColor) {
        try {
            $handler = new CurlHandler();
            $client = new \GuzzleHttp\Client();
            $tapMiddleware = Middleware::tap(function ($request) {});
            $asset = view('mails.otp', compact('otp', 'brand', 'type', 'domainName', 'themeColor'))->render();
            $data = ["from" => $domainName, "to" => $email, "subject" => "Verify Email", "html" => $asset];
            $response = $client->request('POST', 'https://api.forwardemail.net/v1/emails', [
                'form_params' => $data,
                'handler' => $tapMiddleware($handler),
                'auth' => ['27740a92ffff97ab03a096ab:', ''],
            ]);


            $body = $response->getBody();
            $data = json_decode($body, true);
            if (isset($data['error'])) {
                return ['msg' => 'Domain is not approved', 'status' => 500];
            } else {
                if ($data['status'] == 'queued') {
                    return ['msg' => $data, 'status' => 200];
                } else {
                    return ['msg' => $data, 'status' => 500];
                }
            }
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|required',
                'otp' => 'required'
            ]);
            if ($validator->fails()) {
                return ['error' => $validator->errors(), 'status' => 401];
            } else {
                $email = $validator->validated()['email'];
                $otp = $validator->validated()['otp'];
                $verifier = DB::table('email_otps')->where([['email', $email], ['otp', $otp]])->first();
                if ($verifier) {
                    DB::table(('email_otps'))->delete($verifier->id);
                    return ['status' => 200];
                } else {
                    return ['status' => 401];
                }
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            // Return an error response to the client
            return ['error' => 'Unable to verify otp', 'status' => 500];
        }
    }

    private function genOTP()
    {
        return sprintf("%06d", mt_rand(1, 999999));
    }

    // public function resetPassword($request)
    // {
    //     $data = $this->passwordResetDataValidation($request);
    //     if ($data->fails()) {
    //         return ['status' => 501, 'error' => $data->errors()->all()];
    //     } else {
    //         $input = $data->validated();
    //         $verifyUserEmail = $this->confirmEmail($input['email']);

    //         if($verifyUserEmail['status'] == 200) {//verified
    //             $encryptedPass = bcrypt($request->password);
    //             $updateUser = $verifyUserEmail['user']->update(['password' => $encryptedPass]);
    //             if ($updateUser == true) {
    //                 return ['status' => 200, 'msg' => 'Password successfully reset.'];
    //             }
    //         } else {//!verified
    //             return ['status' => 404, 'error' => 'User with this email not found.'];
    //         }
    //     }
    // }


    // /**
    //  * This function request for old password in order to change to new one
    //  */
    // public function changePassword(Request $request)
    // {
    //     $status = 401;
    //     $response = ['error' => 'Unauthorised'];
    //     $user = Auth::user();
    //     if ($user) {
    //         // dd($request->oldPass, $user->password);
    //         $password = $user->password;
    //         $old_pass = $request->oldPass;
    //         if (Hash::check($old_pass, $password)) {
    //             // The passwords match...
    //             $data = $request->password;

    //             $newPass = $request->user()->fill([
    //                 'password' => Hash::make($data)
    //             ])->save();
    //             return [
    //                 'user' => $newPass,
    //                 'message' => 'Password Changed Successfully'
    //             ];
    //         } else {
    //             return ['error' => $status];
    //         }
    //     } else {
    //         return response()->json($response);
    //     }
    // }
}

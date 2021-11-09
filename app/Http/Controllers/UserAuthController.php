<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Mail\PasswordReset;
class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $data['password'] = Hash::make($request['password']);
        $data['remember_token'] = Str::random(10);
        $data['email'] = $request['email'];
        $user = User::create($data);

        $encrypt_method = "AES-256-CBC";
        $secret_key = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $string = json_encode(array(["email"=>$data["email"], "password"=>$data["password"]]));
        $encryptToken = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $encryptToken = base64_encode($encryptToken);

        $to_email = $data['email'];

        Mail::to($to_email)->send(new Gmail($request['email'], $encryptToken));

        if (Mail::failures()) {
            return response('VErr');
        }
        $response = ["success" => 'true', 'message' => 'Registeration Success', 'user' => $user];
        return response($response, 200);
    }

    public function verify(Request $request)
    {
        $data = $request->json()->all();
        $encrypt_method = "AES-256-CBC";
        $secret_key = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $decryptToken = openssl_decrypt(base64_decode($data["token"]), $encrypt_method, $key, 0, $iv);

        $decryptTokenArray = json_decode($decryptToken);
        $email = ($decryptTokenArray[0]->email);
        $user = new User();
        $user->verified($email);
        return response(['ddd' => $email]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $verified = $user->verified;
            if($verified === null) {
                $response = ["message" => "not verified"];
                return response($response, 422);
            }
            if ($verified == 1) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = ['success' => 'true', 'message' => 'Login Success', 'token' => $token, 'user' => $user];
                    return response($response, 200);
                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function home(Request $request) {
        return response(['user' => auth()->user()]);
    }

    public function sendresetpasswordemail(Request $request)
    {
        $data = $request->json()->all();
        $sent_email = $data['email'];
        $user = User::where('email', $sent_email)->get();
        // $request['password'] = Hash::make($request['password']);

        $encrypt_method = "AES-256-CBC";
        $secret_key = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        // $string = json_encode(array(["email" => $request["email"], "password" => $request['password']]));
        $string = json_encode(array(["email"=>$data["email"]]));
        $encryptToken = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $encryptToken = base64_encode($encryptToken);

        if($user->count() >= 0) {
            Mail::to($sent_email)->send(new PasswordReset($sent_email, $encryptToken));
            $response = ["success" => 'true', 'message' => 'Send Resetpassword Email Success'];
            return response($response, 200);
        }
        else {
            return response("Invalid Email", 500);
        }

    }

    public function setresetpassword(Request $request)
    {
        $data = $request->json()->all();
        $encrypt_method = "AES-256-CBC";
        $secret_key = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $secret_iv = '7aE3OKIZxusugQdpk3gwNi9x63MRAFLgkMJ4nyil88ZYMyjqTSE3FIo8L5KJghfi';
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $decryptToken = openssl_decrypt(base64_decode($data["token"]), $encrypt_method, $key, 0, $iv);

        $decryptTokenArray = json_decode($decryptToken, true);

        $email = ($decryptTokenArray[0]['email']);
        return response(['dd'=> $data['password']]);

        $password = Hash::make($data['password']);
        $user = User::where('email', $email)->update(['password' => $password]);
        return response('OK');
    }
}
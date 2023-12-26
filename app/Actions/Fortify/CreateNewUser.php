<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Mail;
use App\Models\Msg;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();
        
        $user = User::create([
            'kind' => 1,
            'name' => $input['name'],
            'namek' => $input['namek'],
            'email' => $input['email'],
            'postnum' => $input['postnum'],
            'address' => $input['address'],
            'phonenum' => $input['phonenum'],
            '_pref' => $input['_pref'],
            '_city' => $input['_city'],
           
            '_store' => '',
            '_store_photo_path' => 'store.jpg',
            'profile_photo_path' => 'default.png',
            '_birth' =>  '1988-12-2',
            '_sex' =>  '',
          
            '_address' =>  '',
            '_build' =>  '',

            '_charge_name' =>  '',
            '_charge_namek' =>  '',
            '_charge_phonenum' =>  '',
            '_charge_mail' =>  '',

            '_private' =>  0,
            '_agree' =>  0,
            '_main_deli' =>  0,
            
            '_state' =>  0,
            '_category' =>  0,
            '_category_sub' =>  0,
            '_agree1' =>  0,
            '_agree2' =>  0,
            '_agree3' =>  0,
            '_upfile1' =>  '',
            '_upfile2' =>  '',
            '_upfile3' =>  '',
            '_upfile4' =>  '',
            '_upfile5' =>  '',
            '_url1' =>  '',
            '_url2' =>  '',
            '_url3' =>  '',
            '_introduce' =>  '',
            '_delivery' =>  '',
            '_condition_photo_1' =>  '',
            '_condition_photo_2' =>  '',
            '_condition_photo_3' =>  '',
            '_condition_photo_4' =>  '',
            '_condition_title_1' =>  '',
            '_condition_title_2' =>  '',
            '_condition_title_3' =>  '',
            '_condition_title_4' =>  '',
            '_condition_con_1' =>  '',
            '_condition_con_2' =>  '',
            '_condition_con_3' =>  '',
            '_condition_con_4' =>  '',
            '_handling' =>  '',
            '_company' =>  '',
            '_size' =>  '',
            '_kind' =>  '',
            '_fax' =>  '',
            '_fax_flag' =>  '',
            '_msg_flag' =>  '',
            '_comment_flag' =>  '',

            '_boss' =>  '',
            '_bank_kind' =>  0,
            '_bank_code' =>  '',
            '_bank_name' =>  '',
            '_bank_num' =>  '',
            '_account_kind' =>  0,
            '_account_num' =>  '',
            '_account_name' =>  '',
            '_yamato_code' =>  '',
            '_yamato_client_code' =>  '',
            '_yamato_num' =>  '',

            'password' => Hash::make($input['password']),
            'passwordtip' => $input['passwordtip'],
        ]);

        $msg["user_id"] = $user->id;
        $msg["name"] = $user->name;
        $msg["email"] = $user->email;
        $msg["title"] = "【もぐリコ】登録完了のお知らせ";
        
        $mst_con = '<!DOCTYPE html>
            <html>
                <head>
                    <title>【もぐリコ】パスワード再設定のお知らせ</title>
                </head>
                <body>    
                
                    <br>
                    '.$user->name.'　様<br>    
                    <br>
                    <br>
                    この度は「もぐリコ」にご登録いただき、誠にありがとうございます。<br>
                    会員登録が完了しました。<br>
                    <br>
                    下記URLより、もぐリコにアクセスしてください。<br>
                    https://mogrico.com/login<br>
                    <br>
                    
                    ご利用いただくなかでお困りごとがございましたらお気軽にご連絡ください。<br>
                    ＜お問い合わせ＞<br>
                    https://mogrico.com/inquiry<br>
                    <br>
                    
                    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
                    もぐリコ<br>
                    https://mogrico.com/<br>
                    <br>
                    ＜会社＞<br>
                    株式会社Happiness Trade<br>
                    福岡県筑後市野町518-1<br>
                    <br>
                    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
                    <br>
                    ※このメールは送信専用です。返信をいただいても届きません。<br>
                    ※内容に心あたりがない場合は、お問い合わせ窓口からご連絡ください。<br>
                
                
                </body>
            </html>';

        $msg["msg"] = $mst_con;
        $msg["send_dt"] = date("Y-m-d H:i:s");
        $msg["state"] = 1;
        $msg["alret_dt"] = date("Y-m-d H:i:s");

        Msg::create($msg);

        $detail = [];
        $bccAry = [];
        $details = $input;
        $details["msg_con"] = $mst_con;
        
        // Mail::to($details["email"])
        //         ->bcc($bccAry)
        //         ->send(new \App\Mail\RegisterMail($details));
  
        return $user;
    }
}

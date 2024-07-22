<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\registrationRequest;
use App\Models\ChatMessage;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    
    public function register(registrationRequest $req){
        
        $req->file('pfp')->store('public/profile_pics');
        $pfp_name = $req->file('pfp')->hashName();

        $user = User::create(array_merge($req->validated(), [
            'password' => Hash::make($req->password), 
            'pfp' => $pfp_name, 
            'lang_s' => $req->lang_s, 
            'lang_t' => $req->lang_t
        ]));

        if($user){
           
            Auth::login($user);
            return response()->json(['status' => 200, 'message' => 'user is registreted!', 'user_id' => $user->id]);
        }

        return response()->json(['status' => 422, 'message' => 'user is failed to be registreted!']);

    }

    public function login(loginRequest $req)
    {
        $formFields = $req->only(['email', 'password']);
        
        
        if (Auth::attempt($formFields)) {
            $user = User::where("email", $formFields['email'])->get();
            return response()->json(['status' => 200, 'message' => 'user is logged in!', 'user_id' => $user[0]->id]);
        }
        return response()->json(['status' => 422, 'message' => 'Неверная почта или пароль']);
    }


    public function logout(){

            Auth::logout();

            return;

    }

    // Все языки из Базы данных

    public function get_langs(){

        return Language::all();


    }

    // Проверка на существования пользователя по id
    // и вывод данных о нём, если существует

    public function get_user($id)
    {
        $exists = User::where('id', '=', $id)->exists();

        if($exists){
            return User::find($id);
        }

        return 'no_user_found';

    }

    // Вывод возможных собеседников

    public function get_users()
    {
        
        return User::where([
            ['id', '<>', Auth::user()->id],
            ['lang_t', '=', Auth::user()->lang_s],
            ['lang_s', '=', Auth::user()->lang_t],
        ])
        ->get();

    }

    // Вывод чатов пользователей

    public function get_existant_chats()
    {
        $users = User::where('id', '<>', Auth::user()->id)->get();

        // список всех собеседников
        $send = [];

        foreach($users as $user){
            $exists = ChatMessage::where([
                ['sender_id', '=', Auth::user()->id],
                ['reciewer_id', '=', $user->id],
            ])->orWhere([
                ['reciewer_id', '=', Auth::user()->id],
                ['sender_id', '=', $user->id],
            ])
            ->exists();

            if($exists){
                $send[] = $user;
            }
            
        }

        return $send;

    }


}

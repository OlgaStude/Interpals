<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\registrationRequest;
use App\Http\Resources\userResource;
use App\Models\ChatMessage;
use App\Models\Language;
use App\Models\Language_s;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    
    public function register(registrationRequest $req){
        
        $req->file('pfp')->store('public/profile_pics');
        $pfp_name = $req->file('pfp')->hashName();
        $user = User::create(array_merge($req->validated(), ['password' => Hash::make($req->password), 'pfp' => $pfp_name, 'lang_s' => $req->lang_s, 'lang_t' => $req->lang_t]));

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

        try{
            Auth::logout();
            return response()->json(['status' => 200, 'message' => 'user is logged out!']);
            

        } catch(QueryException $e){
            return response()->json(['status' => 422, 'message' => $e]);
        }


    }


    public function get_langs(){

        return Language::all();


    }

    public function get_user($id)
    {
        $exists = User::where('id', '=', $id)->exists();

        if($exists){
            return User::find($id);
        }
        return 'no_user_found';

    }

    public function get_users()
    {
        
        return User::where([
            ['id', '<>', Auth::user()->id],
            ['lang_t', '=', Auth::user()->lang_s],
            ['lang_s', '=', Auth::user()->lang_t],
            ])->get();

    }


    public function get_existant_chats()
    {
        $users = User::where('id', '<>', Auth::user()->id)->get();

        $send = [];

        foreach($users as $user){
            $exists = ChatMessage::where([
                ['sender_id', '=', Auth::user()->id],
                ['reciewer_id', '=', $user->id],
            ])->orWhere([
                ['reciewer_id', '=', Auth::user()->id],
                ['sender_id', '=', $user->id],
            ])->exists();
            if($exists){
                $send[] = $user;
            }
            
        }

        

        return $send;

    }


}

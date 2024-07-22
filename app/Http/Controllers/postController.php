<?php

namespace App\Http\Controllers;

use App\Http\Requests\postRequest;
use App\Http\Resources\postResource;
use App\Models\posts;
use Illuminate\Support\Facades\Auth;

class postController extends Controller
{
    
    public function makePost(postRequest $req){
        
        posts::create([
            'users_id' => Auth::user()->id, 
            'lang' => $req->lang, 
            'text' => $req->text
        ]);

        return;

    }

    // Получить посты для ленты

    public function getPosts(){

        $posts = posts::join('users', 'users.id', '=', 'posts.users_id')
        ->select(
            'posts.id as id',
            'posts.lang as lang',
            'posts.text as text',
            'posts.created_at as created_at',
            'users.id as user_id',
            'users.name as name',
            'users.surname as surname',
            'users.pfp as pfp'
        )
        ->where('lang', '=', Auth::user()->lang_s)
        ->orWhere('lang', '=', Auth::user()->lang_t)
        ->orderBy('posts.id', 'desc')
        ->get();

        return postResource::collection($posts);

    }

    // Получить посты одного пользователя для его страницы

    public function getPosts_by_user($id){


        $posts = posts::join('users', 'users.id', '=', 'posts.users_id')
        ->select(
            'posts.id as id',
            'posts.lang as lang',
            'posts.text as text',
            'posts.created_at as created_at',
            'users.id as user_id',
            'users.name as name',
            'users.surname as surname',
            'users.pfp as pfp'
        )
        ->where('posts.users_id', '=', $id)
        ->orderBy('posts.id', 'desc')
        ->get();

        return postResource::collection($posts);


    }

}

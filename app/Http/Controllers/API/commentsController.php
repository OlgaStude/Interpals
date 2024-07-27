<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\commentRequest;
use App\Http\Resources\commentResource;
use App\Models\Comment;
use App\Models\Dislike;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class commentsController extends Controller
{
    
    public function addComment(commentRequest $req){

        Comment::create([
            'posts_id' => $req->post_id, 
            'users_id' => Auth::user()->id, 
            'text' => $req->text, 
            'likes' => 0, 
            'dislikes' => 0
        ]);

        $comments = Comment::where('posts_id', '=', $req->post_id)
            ->orderBy('likes', 'desc')
            ->get();
        
        return commentResource::collection($comments);
    }


    public function getComments(Request $req){


        if($req->filter == 'time'){
            $comments = Comment::where('posts_id', '=', $req->post_id)
            ->orderBy('created_at', 'desc')
            ->get();
        }
        if($req->filter == 'usefullness'){
            $comments = Comment::where('posts_id', '=', $req->post_id)
            ->orderBy('likes', 'desc')
            ->get();
        }

        return commentResource::collection($comments);

    }


    public function likeComment(Request $req)
    {
            $exists = Like::where([
                ['users_id', '=', Auth::user()->id],
                ['comments_id', '=', $req->id],
            ])
            ->exists();

            if (!$exists) {
                Comment::where("id", $req->id)->increment("likes");

                Like::create([
                    'users_id' => Auth::user()->id, 
                    'comments_id' => $req->id
                ]);

                $exists = Dislike::where([
                    ['users_id', '=', Auth::user()->id],
                    ['comments_id', '=', $req->id],
                ])
                ->exists();

                if ($exists) {
                    Comment::where("id", $req->id)->decrement("dislikes");

                    Dislike::where([
                        ['users_id', '=', Auth::user()->id],
                        ['comments_id', '=', $req->id],
                    ])
                    ->delete();
                }
            } else {
                Comment::where("id", $req->id)->decrement("likes");

                Like::where([
                    ['users_id', '=', Auth::user()->id],
                    ['comments_id', '=', $req->id],
                ])
                ->delete();
            }
        
    }


    public function dislikeComment(Request $req)
    {
            $exists = Dislike::where([
                ['users_id', '=', Auth::user()->id],
                ['comments_id', '=', $req->id],
            ])
            ->exists();
            
            if (!$exists) {
                Comment::where("id", $req->id)->increment("dislikes");
                
                Dislike::create([
                    'users_id' => Auth::user()->id, 
                    'comments_id' => $req->id
                ]);

                $exists = Like::where([
                    ['users_id', '=', Auth::user()->id],
                    ['comments_id', '=', $req->id],
                ])
                ->exists();

                if ($exists) {
                    Comment::where("id", $req->id)->decrement("likes");

                    Like::where([
                        ['users_id', '=', Auth::user()->id],
                        ['comments_id', '=', $req->id],
                    ])
                    ->delete();

                }
            } else {
                Comment::where("id", $req->id)->decrement("dislikes");

                Dislike::where([
                    ['users_id', '=', Auth::user()->id],
                    ['comments_id', '=', $req->id],
                ])
                ->delete();
                
            }
        
    }

}

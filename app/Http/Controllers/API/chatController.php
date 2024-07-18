<?php

namespace App\Http\Controllers\API;

use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\chatMessageRequest;
use App\Http\Resources\CMResource;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class chatController extends Controller
{
    
    public function create_message(chatMessageRequest $req){

        $message = ChatMessage::create(['read_msg' => 0, 'sender_id' => Auth::user()->id, 'reciewer_id' => $req->receiver_id, 'message' => $req->message]);

        if($message){

            broadcast(new NewMessage(Auth::user()));
           
            return response()->json(['status' => 200, 'message' => 'message is created!']);
        }

        return response()->json(['status' => 422, 'message' => 'message is not created!']);

    }


    public function getChatMessages($id){

        

    
            if($id != Auth::user()->id){
                $messages = ChatMessage::where([
                    ['sender_id', '=', Auth::user()->id],
                    ['reciewer_id', '=', $id],
                ])->orWhere([
                    ['reciewer_id', '=', Auth::user()->id],
                    ['sender_id', '=', $id],
                ])->orderBy('id', 'asc')->get();
        
                broadcast(new NewMessage(Auth::user()));

                return CMResource::collection($messages);
            }
    
            return 'false';

        

    }
    

}

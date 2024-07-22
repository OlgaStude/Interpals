<?php

namespace App\Http\Controllers\API;

use App\Events\NewMessage;
use App\Events\ReadMessage;
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

           

            return response()->json(['status' => 200, 'message' => 'message is created!']);
        }

        return response()->json(['status' => 422, 'message' => 'message is not created!']);

    }


    public function getChatMessages($id){

    
            if($id != Auth::user()->id){
        
                broadcast(new NewMessage(Auth::user()->id, $id));

                return;
            }
    
            return 'false';
    }


    public function readMessages($last_id, $sender_id, $receiver_id){

        if($last_id == 0){
            ChatMessage::where([
                ['sender_id', '=', $sender_id],
                ['reciewer_id', '=', $receiver_id],
            ])->update(['read_msg' => 1]);

            $messages = ChatMessage::where([
                ['sender_id', '=', $sender_id],
                ['reciewer_id', '=', $receiver_id],
            ])->orWhere([
                ['reciewer_id', '=', $sender_id],
                ['sender_id', '=', $receiver_id],
            ])->orderBy('id', 'asc')->get();

            broadcast(new ReadMessage(CMResource::collection($messages)));
            
            return CMResource::collection($messages);
            
        }
        ChatMessage::where([
            ['sender_id', '=', $sender_id],
            ['reciewer_id', '=', $receiver_id],
        ])->update(['read_msg' => 1]);

        $messages = ChatMessage::where([
            ['sender_id', '=', $sender_id],
            ['reciewer_id', '=', $receiver_id],
        ])->orWhere([
            ['reciewer_id', '=', $sender_id],
            ['sender_id', '=', $receiver_id],
        ])->orderBy('id', 'asc')->get();

        broadcast(new ReadMessage(CMResource::collection($messages)));

        return;

    }
    

}

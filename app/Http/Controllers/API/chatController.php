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

        ChatMessage::create([
            'read_msg' => 0, 
            'sender_id' => Auth::user()->id, 
            'reciewer_id' => $req->receiver_id, 
            'message' => $req->message
        ]);

        return;

    }

    // Отправка сигнала: новые сообщения получены
    // Отправка данных для проверки: для какого
    // чата предназначены сообщения

    public function getChatMessages($id){
        
            broadcast(new NewMessage(Auth::user()->id, $id));

            return;

    }

    // Изменяет статус сообщений собеседника на "прочитано",
    // затем выводит все сообщения из чата

    public function readMessages($page_load, $sender_id, $receiver_id){

            ChatMessage::where([
                ['sender_id', '=', $sender_id],
                ['reciewer_id', '=', $receiver_id],
            ])
            ->update(['read_msg' => 1]);

            $messages = ChatMessage::where([
                ['sender_id', '=', $sender_id],
                ['reciewer_id', '=', $receiver_id],
            ])->orWhere([
                ['reciewer_id', '=', $sender_id],
                ['sender_id', '=', $receiver_id],
            ])
            ->orderBy('id', 'asc'
            )->get();

            broadcast(new ReadMessage(CMResource::collection($messages)));
            
            // $page_load проверяет, если страница была только-что 
            // загружена или обновлена

            if($page_load == 1){            
                return CMResource::collection($messages);
            }           

            return;

    }
    

}

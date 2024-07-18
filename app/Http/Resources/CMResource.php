<?php

namespace App\Http\Resources;

use App\Models\ChatMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CMResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->reciewer_id == Auth::user()->id){
            $test = true;
            if(!$this->read_msg){
                ChatMessage::where('id', $this->id)->update(['read_msg' => 1]);
            }
            return [
                'id' => $this->id,
                'messager_id' => $this->sender_id,
                'messager_name' => User::find($this->sender_id)->name,
                'messager_surname' => User::find($this->sender_id)->surname,
                'messager_pfp' => User::find($this->sender_id)->pfp,
                'is_received_message' => $test,
                'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
                'text' => $this->message
            ];
        } else {
            $test = false;
            return [
                'id' => $this->id,
                'messager_id' => $this->sender_id,
                'messager_name' => User::find($this->sender_id)->name,
                'messager_surname' => User::find($this->sender_id)->surname,
                'messager_pfp' => User::find($this->sender_id)->pfp,
                'is_received_message' => $test,
                'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
                'text' => $this->message,
                'read_msg' => $this->read_msg
            ];
        }
        
    }
}

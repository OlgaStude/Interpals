<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class chatMessageResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        return parent::toArray($request);
        if($this->reciewer_id == Auth::user()->id){
            $test = true;
        } else {
            $test = false;
        }
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'sender_name' => User::find($this->sender_id)->name,
            'sender_surname' => User::find($this->sender_id)->surname,
            'sender_pfp' => User::find($this->sender_id)->pfp,
            'receiver_id' => $this->reciewer_id,
            'receiver_name' => User::find($this->reciewer_id)->name,
            'receiver_surname' => User::find($this->reciewer_id)->surname,
            'receiver_pfp' => User::find($this->reciewer_id)->pfp,
            'is_received_message' => $test,
            'time' => $this->created_at
        ];
    }
}

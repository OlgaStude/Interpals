<?php

namespace App\Http\Resources;

use App\Models\ChatMessage;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class userResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $exists = ChatMessage::where([
            ['sender_id', '=', Auth::user()->id],
            ['reciewer_id', '=', $this->id],
        ])->orWhere([
            ['reciewer_id', '=', Auth::user()->id],
            ['sender_id', '=', $this->id],
        ])->orderBy('id', 'asc')->exists();

        if($exists){
            return parent::toArray($request);

        }

    }
}

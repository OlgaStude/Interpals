<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class postResource extends JsonResource
{
    // /**
    //  * Transform the resource into an array.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    //  */
    public function toArray($request)
    {
        $comments = Comment::where('posts_id', '=', $this->id)->orderBy('likes', 'desc')->get();

        return [
            'id' => $this->id,
            'lang'=> $this->lang,
            'text' => $this->text,
            'created_at' => Carbon::parse($this->created_at)->format('d.m.Y'),
            'user_id' => $this->user_id,
            'name' => $this->name,
            'surname' => $this->surname,
            'pfp' => $this->pfp,
            'comments' => commentResource::collection($comments)
        ];
    }
}

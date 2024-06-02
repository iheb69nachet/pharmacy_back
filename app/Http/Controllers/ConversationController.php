<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
class ConversationController extends Controller
{
     
    public function store()
    {
        $conversation = Conversation::create([
            'message' => request('message'),
            'group_id' => request('group_id'),
            'user_id' => request("user_id"),
        ]);
        // $conversation->load('user');
        // broadcast(new NewMessage($conversation))->toOthers();
        $v=Conversation::where('id',$conversation->id)->with('user')->get();
        return $v;
        // return $conversation->with('user');
    }


   
   
}

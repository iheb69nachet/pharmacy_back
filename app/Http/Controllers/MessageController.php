<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $sender_name = $request->input('sender_name');
        $reciever_name = $request->input('reciever_name');
        $messageData = Message::create(["message"=>$message,"sender_name"=>$sender_name,"receiver_name"=>$reciever_name]);
       

        event(new MessageSent($message,$sender_name,$reciever_name));
        return response()->json(['status' => 'Message sent!']);
    }
    public function index($user1,$user2){
        $sender = $user1; // Replace with actual sender name
        $receiver = $user2; // Replace with actual receiver name
        
        $messages = Message::where(function($query) use ($sender, $receiver) {
            $query->where('sender_name', $sender)->where('receiver_name', $receiver);
        })->orWhere(function($query) use ($sender, $receiver) {
            $query->where('sender_name', $receiver)->where('receiver_name', $sender);
        })->get();
        return response()->json($messages);
    }
}

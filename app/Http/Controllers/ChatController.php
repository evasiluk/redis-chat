<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Message;
use App\Events\RoomMessage;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("room_access")->only(['room']);
    }

    public function rooms()
    {
        $rooms = auth()->user()->rooms;
        return view('chat.rooms', compact('rooms'));
    }

    public function room(Room $room)
    {
        return view('chat.room', compact('room'));
    }

    public function save(Request $request)
    {
        $request->validate([
            "body" => "required|min:3"
        ]);


        if(auth()->user()->rooms->contains($request->room)) {
            $new_message = Message::create([
                "body" => $request->body,
                "room_id" => $request->room,
                "user_id" => auth()->id()
            ]);

            broadcast(new RoomMessage($new_message))->toOthers();
        }
    }

    public function roomMessages(Room $room)
    {
        if(auth()->user()->rooms->contains($room->id)) {
            return $room->messages;
        }
    }
}

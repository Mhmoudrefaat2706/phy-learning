<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.messages.list', compact('messages'));
    }
    public function toggleRead(Message $message)
    {
        $message->status = $message->status === 'unread' ? 'read' : 'unread';
        $message->save();

        return response()->json(['success' => true,
        'message' => 'Message status updated successfully',
        'status' => $message->status]);
    }


}

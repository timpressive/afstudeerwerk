<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Conversation;

class ConversationController extends Controller
{
    public function index() {
        return view('messages.index')->with(['conversations' => Conversation::mine()]);
    }

    public function create($user_id = false) {
        $conversation = new Conversation();
        $conversation->save();

        DB::table('conversation_users')->insert([
            'conversation_id'   => $conversation->id,
            'user_id'           => Auth::user()->id,
        ]);

        if ($user_id) {
            DB::table('conversation_users')->insert([
                'conversation_id'   => $conversation->id,
                'user_id'           => $user_id,
            ]);
        }

        return redirect()->route('conversations.show', ['id' => $conversation->id]);
    }

    public function show($id) {
        $conversation = Conversation::find($id);
        $conversation->setSeen(Auth::user()->id);
        if ($conversation != null) {
            if (!$conversation->isRecipient()) { return redirect()->route('conversations.index'); }

            $conversation->alt_title = '';
            foreach ($conversation->recipients() as $index => $recipient) {
                if ($recipient->id != Auth::user()->id) {
                    if ($index > 0 && $conversation->alt_title != '') { $conversation->alt_title.=', '; }
                    $conversation->alt_title .= $recipient->username;
                }
            }

            return view('messages.show')->with(['conversation' => $conversation]);
        } else { return redirect()->route('conversations.index'); }
    }

    public function destroy($id) {
        Conversation::destroy($id);
        return redirect()->route('conversations.index');
    }

    public function destroyIfEmpty($id) {
        $conversation = Conversation::find($id);
        if (count($conversation->messages()) == 0) {
            Conversation::destroy($conversation->id);
        }
    }

    public function addRecipients(Request $request, $conversation_id) {
        foreach ($request->input('invite') as $user_id) {
            DB::table('conversation_users')->insert([
                'conversation_id'   => $conversation_id,
                'user_id'           => intval($user_id),
            ]);
        }
        return json_encode(Conversation::find($conversation_id)->recipients());
    }

    public function leaveConversation($conversation_id) {
        DB::table('conversation_users')
            ->where(['conversation_id' => $conversation_id, 'user_id' => Auth::user()->id])
            ->delete();

        return redirect()->route('conversations.index');
    }

    public function setTitle(Request $request, $conversation_id) {
        $title = $request->input('title');
        $conversation = Conversation::find($conversation_id);
        $conversation->title = $title;
        $conversation->save();
    }
}

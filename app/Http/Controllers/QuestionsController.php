<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Http\Request;
use Pusher\Pusher;

class QuestionsController extends Controller
{
    public function QuestionInsertPage()
    {
        $questions = DB::table('questions')->get();

        return view('questioninput', compact(['questions']));
    }

    public function QuestionAdd(Request $request)
    {
        $question = $request->question;
        $answer = $request->answer;

        try {
            DB::table('questions')->insert([
                'question' => $question,
                'answer' => $answer,
                'isImage' => 0,
            ]);

            return redirect()->back()->with('success', 'ဝင်သွားပြီအမရေ၊ ကျန်တာလေးတွေထပ်ထည့်ပေးပါအုံး');
        }
        catch (Exception $e) {
            return redirect()->back()->with('error', 'အမရေ ဘာဖြစ်လဲမသိဘူး ပြန်ထည့်ကြည့်ပါအုံး၊ ထပ်စမ်းလို့မှ မရရင် ကျွန်တော့်ကို ပြောနော်');
        }
    }

    public function QuestionCorrect(Request $request)
    {
        $team = $request->team;
        $pos = $request->pos;

        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
          );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data['message'] = 'updates';

        $place = DB::table('special_pos')->where('in_pos', $pos)->get();
        $place = $place[0];

        if ($place->type == 1) {
            DB::table('user_pos')->where('number', $team)->update(['pos' => $place->out_pos]);
        }

        $pusher->trigger('main-channel', 'refresh-event', $data);

        $playerTurn = new PlayerController();
        $playerTurn->NextPlayer();
    }

    public function QuestionWrong(Request $request)
    {
        $team = $request->team;
        $pos = $request->pos;

        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
          );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data['message'] = 'updates';

        $place = DB::table('special_pos')->where('in_pos', $pos)->get();
        $place = $place[0];

        if ($place->type == 0) {
            DB::table('user_pos')->where('number', $team)->update(['pos' => $place->out_pos]);
        }

        $pusher->trigger('main-channel', 'refresh-event', $data);

        $playerTurn = new PlayerController();
        $playerTurn->NextPlayer();
    }
}

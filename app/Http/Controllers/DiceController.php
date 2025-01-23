<?php

namespace App\Http\Controllers;

use App\Events\MainDashboardRefreshEvent;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class DiceController extends Controller
{
    public function ShowDicePage()
    {
        $teamNumber = Auth::user()->number;
        $color = DB::table('user_pos')->where('number', $teamNumber)->select(['color', 'pos'])->get();
        $color = $color[0];
        $currentPlayer = DB::table('current_player')->get();
        $currentPlayer = $currentPlayer[0]->current_player;

        $winner = DB::table('winner')->where('number', $teamNumber)->get();
        if (count($winner) > 0) {
            $winner = true;
        } else {
            $winner = false;
        }

        return view('dice', compact('teamNumber', 'color', 'currentPlayer', 'winner'));
    }

    public function getRandomNumber(Request $request)
    {
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

        $gp = $request->gp;
        $random = $request->randomNumber;

        $current = DB::table('user_pos')->where('number', $gp)->get('pos');
        $current = $current[0]->pos;

        $newpos = $current + $random;

        if ($newpos > 58) {
            $newpos = 58;
        }

        if ($newpos != 58) {
            DB::table('user_pos')->where('number', $gp)->update(['pos' => $newpos]);

            $data['message'] = 'user dices';

            $pusher->trigger('main-channel', 'refresh-event', $data);

            $special = DB::table('special_pos')->where('in_pos', $newpos)->get();
            if (count($special) > 0) {
                $randomQuestion = DB::table('questions')
                    ->where('count', function ($query) {
                        $query->from('questions')
                            ->selectRaw('MIN(count)');
                    })
                    ->inRandomOrder()
                    ->first();

                DB::table('questions')->where('id', $randomQuestion->id)->update(['count' => $randomQuestion->count+1]);

                $question = $randomQuestion->question;
                $isImage = $randomQuestion->isImage;
                $answer = $randomQuestion->answer;
                $gp = Auth::user()->number;

                $data1['message'] = [$question, $answer, $gp, $newpos, $isImage];

                $data2['message'] = [$question, $isImage, $gp];

                $pusher->trigger('main-channel', 'refresh-event', $data);

                sleep(1.5);
                $pusher->trigger('main-question-channel', 'question-event', data: $data1);
                $pusher->trigger('player-question-channel', 'question-event', data: $data2);
            }
            else {
                $playerTurn = new PlayerController();
                $playerTurn->NextPlayer();
            }
        }
        else {
            DB::table('user_pos')->where('number', $gp)->update(['pos' => $newpos]);

            DB::table('winner')->insert([
                'number' => $gp,
                'status' => 0
            ]);

            $pusher->trigger('main-channel', 'refresh-event', 'win');
        }

    }
}

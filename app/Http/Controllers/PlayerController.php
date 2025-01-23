<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class PlayerController extends Controller
{
    public function NextPlayer()
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

        $currentPlayer = DB::table('current_player')->get();
        $currentPlayer = $currentPlayer[0]->current_player;

        $totalPlayer = DB::table('user_pos')->get();

        $pusher->trigger('player-refresh-channel', 'refresh-event', $currentPlayer);

        if ($currentPlayer < count($totalPlayer)) {
            $currentPlayer += 1;
        } else {
            $currentPlayer = 1;
        }

        $winner = DB::table('winner')->where('number', $currentPlayer)->get();
        while(count($winner) > 0) {
            if ($currentPlayer < count($totalPlayer)) {
                $currentPlayer += 1;
            } else {
                $currentPlayer = 1;
            }
            $winner = DB::table('winner')->where('number', $currentPlayer)->get();
        }

        $data['message'] = $currentPlayer;

        $pusher->trigger('current-player-channel', 'permission-event', $data);

        DB::table('current_player')->where('id', 1)->update(['current_player' => $currentPlayer]);

        $pusher->trigger('main-channel', 'refresh-event', $data);
    }

    public function SentPermissionAgain()
    {
        $currentPlayer = DB::table('current_player')->get();
        $currentPlayer = $currentPlayer[0]->current_player;

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

        $data['message'] = $currentPlayer;

        $pusher->trigger('current-player-channel', 'permission-event', $data);
    }

    public function RefreshPage()
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

        $data['message'] = 'just a refresh function';

        $pusher->trigger('player-refresh-channel', 'refresh-event', $data);

    }

    public function WinnerPageGoBack(){
        return redirect()->route('snakeboard');
    }

    public function ResetGame()
    {
        DB::table('user_pos')->truncate();
        DB::table('users')->truncate();
        DB::table('winner')->truncate();
        DB::table('current_player')->where('id', 1)->update(['current_player' => 0]);

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

        $pusher->trigger('player-refresh-channel', 'refresh-event', -1);
        $pusher->trigger('main-channel', 'refresh-event', -1);
    }
}

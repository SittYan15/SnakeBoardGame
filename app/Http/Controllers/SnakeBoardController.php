<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SnakeBoardController extends Controller
{
    public function SnakeBoard()
    {
        $users = DB::table('users')
                ->join('user_pos', 'users.number', 'user_pos.number')
                ->join('position', 'user_pos.pos', 'position.pos')
                ->select([
                    'users.number',
                    'user_pos.color',
                    'position.pos',
                    'position.tops', 'position.tope', 'position.lefts', 'position.lefte'
                ])
                ->get();

        $currentPlayer = DB::table('current_player')->get();
        $currentPlayer = $currentPlayer[0]->current_player;

        $winners = DB::table('winner')->get();
        if (count($winners) > 0) {
            for ($i = 0; $i < count($winners); $i++) {
                if ($winners[$i]->status == 0) {
                    DB::table('winner')->where('id', $winners[$i]->id)->update(['status' => 1]);
                    return view('winner', compact('winners'));
                }
            }
        }

        return view('snakeboard', compact('users', 'currentPlayer'));
    }

    public function GridView(Request $request, $top, $left)
    {
        $users = DB::table('users')
        ->join('user_pos', 'users.number', 'user_pos.number')
        ->join('position', 'user_pos.pos', 'position.pos')
        ->select([
            'users.number',
            'user_pos.color',
            'position.pos',
            'position.tops', 'position.tope', 'position.lefts', 'position.lefte'
        ])
        ->get();

        return view('example', compact('users', 'top', 'left'));
    }
}

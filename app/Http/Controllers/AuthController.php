<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function SignUp()
    {
        return view('signup');
    }

    public function Login(Request $request)
    {
        $gp = $request->gpnumber;

        $oldUser = User::where('number', $gp)->first();

        if ($oldUser) {
            DB::table('user_pos')->where('number', $gp)->update(['pos' => 0]);
            Auth::login($oldUser);
        } else {
            $newUser = User::create([
                'number' => $gp,
                'email' => 'example@gmail.com',
                'password' => 'password',
            ]);

            DB::table('user_pos')->insert([
                'number' => $gp,
                'pos' => 0,
                'color' => $this->generateRandomColor(),
            ]);

            Auth::login($newUser);
        }

        return redirect()->route('dicepage');
    }

    public function generateRandomColor() {

        $red = rand(0, 255);
        $green = rand(0, 255);
        $blue = rand(0, 255);

        return sprintf("#%02X%02X%02X", $red, $green, $blue);
    }
}

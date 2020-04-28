<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        $id = Auth::user()->id;
        $active = DB::select('SELECT * FROM users WHERE id = '. $id);
        $active = json_decode(json_encode($active), true)[0];
        $questions = $this->fetch_questions();
        return view('home')->with([
            'active_user' => $active,
            'questions' => $questions
        ]);
    }

    public function fetch_questions(){
        $all = DB::select('SELECT * FROM inquiries');
        $all = json_decode(json_encode($all), true);
        return $all;   
    }
}

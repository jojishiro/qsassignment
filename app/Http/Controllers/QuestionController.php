<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function add(Request $req){
        $question = htmlspecialchars($req->input('question_body'));
        $type = $req->input('answer_type');
        $date = date('Y-m-d H:i:s');
        DB::table('inquiries')->insert([
            'query_id' => null,
            'query_answer' => $type,
            'query_status' => '',
            'query_username' => '',
            'query_date' => $date,
            'query_body' => $question
        ]);
        return redirect('/admin');
    }

    public function remove(Request $req){
        $id = $req->input('qid');
        DB::table('inquiries')
            ->where('query_id', $id)
            ->delete();
        return redirect('/admin');
    }

    public function edit(Request $req){
        $body = $req->input('body');
        $answer = $req->input('answer');
        $status = $req->input('status');
        $username = $req->input('username');
        $id = $req->input('qid');
        $date = date('Y-m-d H:i:s');
        DB::table('inquiries')
            ->where('query_id', $id)
            ->update([
                'query_answer' => $answer, 
                'query_status' => $status, 
                'query_username' => $username,
                'query_date' => $date,
                'query_body' => $body
            ]);
        return redirect('/admin');
    }

    public function uedit(Request $req){
        $answer = htmlspecialchars($req->input('uanswer'));
        $userid = $req->input('uid');
        $user = DB::select('SELECT name FROM users WHERE id = ' . $userid);
        $name = json_decode(json_encode($user), true)[0]['name'];
        $id = $req->input('qid');
        $date = date('Y-m-d H:i:s');
        DB::table('inquiries')
            ->where('query_id', $id)
            ->update([
                'query_answer' => $answer, 
                'query_status' => 'Pending', 
                'query_username' => $name,
                'query_date' => $date
            ]);
        return redirect('/admin');
    }

    public function approved($id){
        $date = date('Y-m-d H:i:s');
        DB::table('inquiries')
            ->where('query_id', $id)
            ->update([
                'query_status' => 'Approved', 
                'query_date' => $date
            ]);
        return redirect('/admin');
    }

    public function rejected($id){
        $date = date('Y-m-d H:i:s');
        DB::table('inquiries')
            ->where('query_id', $id)
            ->update([
                'query_status' => 'Rejected', 
                'query_date' => $date
            ]);
        return redirect('/admin');
    }

    public function make_csv(){
        $fname = 'question.csv';
        $questions = DB::select('SELECT * FROM inquiries');
        $qs = json_decode(json_encode($questions), true);

        header('Content-type: text/csv');
        header("Content-Disposition: attachment; filename=$fname");
        $fp = fopen('php://output', 'w');
        $index = 0;
        foreach($qs as $q){
            fputcsv($fp, array(
                    $q['query_id'], $q['query_body'], 
                    $q['query_answer'], $q['query_status'],
                    $q['query_date'], $q['query_username']
                ));
        }
        fclose($fp);
    }
}

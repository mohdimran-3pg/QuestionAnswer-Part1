<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Question;
use DB;

class QuestionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = DB::select("SELECT t.id, t.title, t.description, COUNT(t.id) AS TOTAL_ANS , 
                              t.created_at AS question_created_date, s.description, 
                              s.created_at AS answer_created_at 
                              FROM questions t 
                              LEFT JOIN answers s ON s.question_id = t.id 
                              GROUP BY t.id 
                              ORDER BY s.created_at DESC"); 
       $arrQuestion = array();
       foreach($questions as $question) {

            $arrAnswer = array();
            $answers = DB::select("SELECT s.id, s.description, s.created_at
                                     FROM answers s 
                                     WHERE s.question_id = ? 
                                     ORDER BY s.created_at DESC", [$question->id]); 
            if (count($answers) > 0) {
                foreach($answers as $answer) {
    
                    $arrAnswer[] = array("title"=>$answer->description,
                                         "created_at" => date("d-m-Y h:m A", strtotime($answer->created_at)),   
                                         "id" => $answer->id);
                }
            }
            
            $arrQuestion[] = array("title"=>$question->title,
                                 "created_at" => date("d-m-Y h:m A", strtotime($question->question_created_date)),   
                                 "id" => $question->id,
                                 "answers" => $arrAnswer);
       }
       return response()->json($arrQuestion, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),
                                      ["title" => "required",
                                       "description" => "required"]);
        
        if ($validation->fails()) 
        {
            return response()->json($validation->errors(), 400);
        } 
        else 
        {
            $objQues = new Question();
            $objQues->title = $request->input("title");
            $objQues->description = $request->input("description");   
            $objQues->save(); 
            return response()->json(["error" => "Question added successfully"], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id);
        if ($question != null)
        {
            $arrQues = array("title" => $question->title,
                                "description" => $question->description,
                                "created_at" => date("d-m-yy h:m A", strtotime($question->created_at)));
            
            return response()->json($arrQues, 200);
        }
        else 
        {
            return response()->json(["error" => "Question not found"], 200);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);

        if ($question != null)
        {
            $total = count($question->answers);
            if($total == 0) {
                $question->delete();
                return response()->json(["msg" => "Question deleted successfully"], 200);
            }
            else
            {
                return response()->json(["error" => "Question can not be deleted because it has ".$total. " answers" ], 400);
            }
        }
        else 
        {
            return response()->json(["error" => "Question not found"], 400);
        }
    }
}

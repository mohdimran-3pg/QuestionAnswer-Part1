<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Question;
use App\Answer;
use DB;

class AnswerApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                                      ["question_id" => "required",
                                       "description" => "required"]);
        
        if ($validation->fails()) 
        {
            return response()->json($validation->errors(), 400);
        } 
        else 
        {
            $objQues = Question::find($request->input("question_id"));
            if ($objQues != null)
            {
                $objAns = new Answer();
                $objAns->question_id = $request->input("question_id");
                $objAns->description = $request->input("description");
                
                $objQues->answers()->save($objAns);   
                return response()->json(["msg" => "Answer added successfully"], 200);
            }
            else 
            {
                return response()->json(["error" => "Question not found"], 400);
            }
            
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
        //
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
        //
    }
}

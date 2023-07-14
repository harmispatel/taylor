<?php

namespace App\Http\Controllers;

use App\Models\ModelComment;
use Illuminate\Http\Request;

class ModelCommentController extends Controller
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

        $input=$request->except('_token');
       // echo "<pre>";print_r($input);exit;
        try{

            ModelComment::create($input);
            flash(translate('Comment Added Successfully'))->success();
            return redirect()->back();
        }catch (\Exception $e) {
            return $e->getMessage();
        }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelComment  $modelComment
     * @return \Illuminate\Http\Response
     */
    public function show(ModelComment $modelComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelComment  $modelComment
     * @return \Illuminate\Http\Response
     */
    public function edit(ModelComment $modelComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModelComment  $modelComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModelComment $modelComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelComment  $modelComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelComment $modelComment)
    {
        //
    }
}

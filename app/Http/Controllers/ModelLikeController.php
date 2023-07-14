<?php

namespace App\Http\Controllers;

use App\Models\ModelLike;
use Illuminate\Http\Request;

class ModelLikeController extends Controller
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
        try{
            $input=$request->all();
            $modelLikeDetails=ModelLike::where('model_id',$request->model_id)->where('user_id',auth()->user()->id)->first();
            $input['user_id']=auth()->user()->id;
            if(!empty($modelLikeDetails)){
                $modelLikeDetails->update($input);
            }else{
                ModelLike::create($input);
            }
            $data['success']=1;
            json_encode($data);
        }catch (\Exception $e) {
            $data['success']=0;
            json_encode($data);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelLike  $modelLike
     * @return \Illuminate\Http\Response
     */
    public function show(ModelLike $modelLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelLike  $modelLike
     * @return \Illuminate\Http\Response
     */
    public function edit(ModelLike $modelLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModelLike  $modelLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModelLike $modelLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelLike  $modelLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelLike $modelLike)
    {
        //
    }
}

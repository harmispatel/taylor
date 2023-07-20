<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\{ModelAlbum};

class ModelAlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums=ModelAlbum::latest()->paginate(5);
        return view('frontend.user.model.model-albums',compact('albums'));
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
        $input=$request->except('_token','albumId');
        $input['model_id']=auth()->id();
        if($request->albumId != 0){
            $albums=ModelAlbum::find($request->albumId);
            $albums->update($input);
            flash(translate('Albums Updated Successfully'))->success();

        }else{
            ModelAlbum::insert($input);
            flash(translate('Albums Added Successfully'))->success();
        }
        return redirect()->back();
    }
    public function getAlbumDetails(Request $request){
        try{

            $albums=ModelAlbum::find($request->albumId);

            if(!empty($albums)){
                $result['success']=1;
                $success=1;
                $result['albums']=$albums;
                return $result;

            }
            else{
                $result['success']=0;
                return $result;
            }
        }catch (\Exception $e) {
            $result['success']=0;
            return $result;
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

        try{

            $albums=ModelAlbum::find(Crypt::decrypt($id));
            if($albums){
                $albums->delete();
                flash(translate('Albums Delete Successfully'))->success();
            }
            else{
                flash(translate('No Data Found for Delete This record'))->error();
            }
            return redirect()->back();
        }catch (\Exception $e) {
            flash(translate('Something Went Wrong !'))->error();
            return redirect()->back();
        }
    }
}

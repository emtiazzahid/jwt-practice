<?php

namespace App\Http\Controllers;

use App\Note;
use App\Transformer\NoteTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Spatie\Fractal\Fractal;
use League\Fractal\TransformerAbstract;

class NoteController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
//        return $user;

        $note = $user->note()->create([
            'title' => $request->title,
            'note' => $request->note,
        ]);

//        return $note;
        return fractal($note, new NoteTransformer());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return Note::find($id);
        try{
            $note = Note::findOrFail($id);
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => 'Note not Found']);
        }
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if ($user->id != $note->user_id){
            return response()->json(['error' => 'Unauthorized user', 401]);
        }

        return fractal($note, new NoteTransformer());

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
        try{
            $note = Note::findOrFail($id);
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => 'Note not Found']);
        }

        $user = JWTAuth::toUser(JWTAuth::getToken());
        if ($user->id != $note->user_id){
            return response()->json(['error' => 'Unauthorized user', 401]);
        }

        $note->title = $request->title;
        $note->note = $request->note;
        $note->save();
        return fractal($note, new NoteTransformer());
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
            $note = Note::findOrFail($id);
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => 'Note not Found']);
        }

        $user = JWTAuth::toUser(JWTAuth::getToken());
        if ($user->id != $note->user_id){
            return response()->json(['error' => 'Unauthorized user', 401]);
        }

        $note->delete();
        return response()->json(['status' => 'true', 200]);
    }
}

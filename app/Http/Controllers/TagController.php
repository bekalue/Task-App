<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /tags maps to TagController@index
     */
    public function index()
    {
        return Auth::user()->tags;
    }

    /**
     * Store a newly created resource in storage.
     * POST /tags maps to TagController@store
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        $tag = new Tag;
        $tag->name = $validatedData['name'];
        $tag->user_id = Auth::id();
        $tag->save();

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     * GET /tags/{id} maps to TagController@show
     */
    public function show(Tag $tag)
    {
        return $tag;
    }

    /**
     * Update the specified resource in storage.
     * PUT /tags/{id} maps to TagController@update
     */
    public function update(Request $request, Tag $tag)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        $tag->update($validatedData);

        return response()->json($tag, 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /tags/{id} maps to TagController@destroy
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(null, 204);
    }
}

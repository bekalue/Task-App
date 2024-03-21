<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /user maps to UserController@index
     */
    public function index()
    {
        return Auth::user();
    }

    /**
     * Update the specified resource in storage.
     * PUT /user maps to UserController@update
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /user maps to UserController@destroy
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->forceDelete();

        return response()->json(null, 204);
    }
}

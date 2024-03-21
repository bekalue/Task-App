<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /tasks maps to TaskController@index
     */
    public function index()
    {
        return Auth::user()->tasks;
    }

    /**
     * Store a newly created resource in storage.
     * POST /tasks maps to TaskController@store
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $validatedData['user_id'] = Auth::id();

        $task = Task::create($validatedData);

        // Attach tags to the task
        $task->tags()->attach($request->input('tag_ids'));

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     * GET /tasks/{id} maps to TaskController@show
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     * PUT /tasks/{id} maps to TaskController@update
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $task->update($validatedData);

        // Sync tags for the task
        $task->tags()->sync($request->input('tag_ids'));

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /tasks/{id} maps to TaskController@destroy
     */
    public function destroy(Task $task)
    {
        // Detach all tags from the task
        $task->tags()->detach();
        
        $task->delete();

        return response()->json(null, 204);
    }
}

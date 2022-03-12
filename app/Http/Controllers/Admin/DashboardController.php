<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::latest('id')->paginate(20);

        return view('admin.dashboard', ['todos' => $todos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
           'task' => ['required'],
           'deadline' => ['required'],
        ]);

        $todo = Todo::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Successfully created!',
            'data' => $todo
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $todo
        ]);
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
        $todo = Todo::findOrFail($id);

        $data = $request->validate([
            'task' => ['required'],
            'deadline' => ['required'],
        ]);

        $todo->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);

        $todo->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted!',
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array']
        ]);

        Todo::whereIn('id', $request->ids)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted!',
        ]);
    }
}

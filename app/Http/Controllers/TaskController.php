<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('priority', 'asc')->get();

        return view('tasks', ['tasks' => $tasks]);
    }

    /**
     * Store a newly created task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }

        // get latest priority from the tasks list
        $latestTask = Task::latest()->first();
        // get priority value as the identifier of the latest task
        $latestPriority = $latestTask && $latestTask->id ? $latestTask->id : 0;

        $task = new Task;
        $task->name = $request->name;
        $task->priority = $latestPriority + 1;
        $task->save();

        return redirect('/');
    }

    /**
     * The method reordering list of tasks by passed array of task`s priorities
     * from AJAX request and returns JSON with result
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id_array' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }

        $task_id_array = $request->task_id_array;

        for ($i = 0; $i < count($task_id_array); $i++) {
            Task::where('id', $task_id_array[$i])
                ->update(['priority' => $i + 1]);
        }

        return response()->json("The list of tasks reordered successfully.", 200);
    }

    /**
     * Update task with passed data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // get data from request
        $inputData = $request->all();

        $validator = Validator::make($inputData, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }

        $id = (empty($inputData['id'])) ? null : $inputData['id'];
        $name = (empty($inputData['name'])) ? null : $inputData['name'];
        $priority = (empty($inputData['priority'])) ? null : $inputData['priority'];
        $project = (empty($inputData['project'])) ? null : $inputData['project'];

        $task = Task::find($id);

        if (empty($task)) {
            return redirect('/')
                ->withInput()
                ->withErrors("The task with id ${id} does not exist!");
        }

        (!empty($name)) ? $task->name = $name : null;
        (!empty($priority)) ? $task->priority = $priority : null;
        (!empty($project)) ? $task->project = $project : null;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified task by passed identifier.
     *
     * @param  {id}
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();

        return redirect('/');
    }
}

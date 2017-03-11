<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Tasks;
use App\Http\Models\Status;
use App\Http\DTO\Task;
use Illuminate\Routing\ResponseFactory;
use Exception;

//This controller handles all the crud operations of the Todo items
class TaskController extends Controller
{
    public function getAllTasks()
    {
        $retval= array();
        $tasks = Tasks::with('status')->orderBy('id', 'desc')->get();
        foreach($tasks as $task)
        {
            $task_ret = new Task();
            $task_ret->id = $task->TaskId;
            $task_ret->description = $task->Description;
            $task_ret->status = $task->Status->Name;
            $task_ret->createDate = $task->CreateTime;
            array_push($retval, $task_ret);
        }
        return response()
                        ->json(['success' => true, 'tasks' => $retval])
                        ->header('Access-Control-Allow-Origin','*');
    }

    public function createTask(Request $request)
    {
        $currTime = date("F j, Y, g:i:s a");
       try
       {
            if($request->description == '')
            {
                throw new Exception('Description cannot be empty !!');
            }
            $task = new Tasks();
            $status = 'Pending';
            if($request->status != '')
            {
                $status = $request->status;
            }
            $statusId = Status::where('Name', $status)->first()->StatusId;
            $task->Description = $request->description;
            $task->StatusId = $statusId;
            $task->CreateTime = $currTime;
            $task->UpdateTime = $currTime;
            $task->save();
            return response()->json(['success' => true, 'last_insert_id' => $task->TaskId])
            ->header('Access-Control-Allow-Origin','*');
       }
       catch(Exception $e)
       {
           return response()->json(['success' => false, 'error_message' => $e->getMessage()])
           ->header('Access-Control-Allow-Origin','*');
       }
    }

    public function updateTask(Request $request)
    {
        try
        {
            $currTime = date("F j, Y, g:i:s a");
            $task = Tasks::find($request->id);
            if(is_null($task))
            {
                return response()->json(['success' => false, 'error_message' => 'Invalid Task id - '.$request->id])
                ->header('Access-Control-Allow-Origin','*');
            }
            $task->Description = $request->description;
            $status = Status::where('Name', $request->status)->first();
            if(is_null($status))
            {
                return response()->json(['success' => false, 'error_message' => 'Invalid Status -'.$request->status.'. Accepted values: Pending, Complete'])
                ->header('Access-Control-Allow-Origin','*');
            }
            $statusId = $status->StatusId;
            $task->StatusId = $statusId;
            $task->UpdateTime = $currTime;
            $task->save();
            
            return response()->json(['success' => true, 'last_updated_id' => $task->TaskId])
            ->header('Access-Control-Allow-Origin','*');
        }
        catch(Exception $e)
        {
            return response()->json(['success' => false, 'error_message' => $e->getMessage()])
            ->header('Access-Control-Allow-Origin','*');
        }
    }

    public function deleteTask(Request $request)
    {
        $taskid = $request->id;
        $task = Tasks::find($taskid);
        if(is_null($task))
        {
            return response()->json(['success' => false, 'error_message' => 'Invalid Task id - '.$request->id])
            ->header('Access-Control-Allow-Origin','*');
            
        }
        $task->delete();
        return response()->json(['success' => true, 'last_deleted_id' => $task->TaskId])
        ->header('Access-Control-Allow-Origin','*');
    }
}
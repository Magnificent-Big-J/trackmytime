<?php

namespace App\Http\Controllers;

use App\Project;
use App\Timer;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return Project::mine()->with('timers')->where('status',0)->get()->toArray();
    }
    public function store(Request $request)
    {
        // returns validated data as array
        $data = $request->validate(['name' => 'required|min:2|max:100']);
        // merge with the current user ID

        $data = array_merge($data, ['user_id' => auth()->user()->id]);
        $project = Project::create($data);

        //return project created project with empty timers
        return $project ? array_merge($project->toArray(), ['timers' => []]) : false;
    }
    public function breakdown()
    {

        $projects = Project::all();

        return $projects;
    }
    public function updateDuration()
    {
        $projects = Project::mine()->where('status',0)->get();
        $count_up = 0;
        foreach ($projects as $project){

            $total = 0;
            $tasks = Timer::mine()
                ->where('project_id',$project->id)
                ->whereNotNull('ended_at')
                ->get();

            foreach ($tasks as $task){
                $diff = strtotime($task->started_at) - strtotime($task->ended_at);
                $total += abs($diff);

            }
            if($total){
                $duration =  gmdate('H:i:s', $total);

                $pro = Project::findOrFail($project->id);
                $pro->duration = $duration;
                $pro->save();
                $count_up++;
            }
        }
        if($count_up){
            return ['message'=>'Project(s) Updated Successfully'];
        }
        return ['message'=>'No Project(s) To Update'];
    }
    public function markAsComplete($id)
    {
        $project = Project::findOrFail($id);
        $project->status = 1;
        $project->save();
        return ['message'=>'Project is completed'];
    }
    public function completedAndNot()
    {
        $complete = Project::mine()->where('status',1)->count();
        $incomplete = Project::mine()->where('status',0)->count();

        return ['complete'=>$complete,'incomplete'=>$incomplete];

    }
    public function dashboard()
    {
        return view('dashboard');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Timer;
use App\Project;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function  store(Request $request,$id)
    {
            $data = $request->validate([
                    'name'=>'required|min:3|max:100'
             ]);

            $timer = Project::mine()->findOrFail($id)
                     ->timers()
                    ->save(new Timer([
                        'name'=>$data['name'],
                        'user_id'=> Auth::id(),
                        'started_at'=> new Carbon()
                    ]));

            return $timer->with('project')->find($timer->id);
    }
    public function running()
    {
        return Timer::with('project')->mine()->first() ?? [];
    }

    public function stopRunning()
    {

        if($timer = Timer::mine()->running()->first()){
            /*$t = Timer::find($timer->id);
            $t->ended_at = Carbon::now();
            $t->save();*/
            $timer->update(['ended_at'=>Carbon::now()]);
        }

        return $timer;
    }
    public function destroy($id)
    {
        $task = Timer::findOrFail($id);
        $task->delete();
        return ['message'=>'Successfully Deleted'];
    }
    public function update(Request $request,$id){
        $task = Timer::findOrFail($id);
        $task->name = $request->name;
        $task->save();
        return ['message'=>'Successfully Updated'];
    }
}

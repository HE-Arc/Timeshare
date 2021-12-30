<?php

namespace App\Http\Controllers;

use App\Models\Management;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('timetables.index');
    }

    /**
     * get the managements list of a timetable
     */
    public function timetablesManagers(Request $request)
    {
        return Management::where('timetable', '=', $request->timetableId)
                           ->join('users', 'users.id', '=', 'management.manager')
                           ->select('management.id', 'email')
                           ->get();
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
        $request->validate([
            'mail' => 'required',
            'timetable' => 'required|integer'
        ]);

        // get the user related to the email
        $newManager = User::where('email', '=',$request->mail)->first();
        if ($newManager === null) {
            return redirect()->route('managements.index');
        }

        // check that the chosen timetable exists and is mine
        $timetable = Timetable::where('id', '=', $request->timetable)->first();
        if($timetable === null){
            return redirect()->route('managements.index');
        }
        if($timetable->author != Auth::id()){
            return redirect()->route('managements.index');
        }

        // check if new manager is not the author
        if($timetable->author == $newManager->id){
            return redirect()->route('managements.index');
        }

        $management = new Management();
        $management->timetable = $request->timetable;
        $management->manager = $newManager->id;
        $management->save();

        return redirect()->route('managements.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Management  $management
     * @return \Illuminate\Http\Response
     */
    public function show(Management $management)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Management  $management
     * @return \Illuminate\Http\Response
     */
    public function edit(Management $management)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Management  $management
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Management $management)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Management  $management
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $man = Management::find($id);
        $man->delete();

        return redirect()->route('managements.index')
                        ->with('success','Management deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Management;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
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
     * get all the events of a timetable
     */
    public function timetablesEvents(Request $request){
        return Event::where('timetable', '=', $request->timetable)
                      ->join('users', 'users.id', '=', 'events.author')
                      ->select('name', 'email', 'events.id', 'events.author', 'timetable',
                               'description', 'begin', 'end', 'isPublic', 'validated')
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
            'title' => 'required',
            'begin' => 'required|date',
            'end' => 'required|date|after:begin',
            'timetable' => 'required|integer'
        ]);

        $timetable = Timetable::where('id', '=', $request->timetable)->first();

        // check that the current timetable exists
        if($timetable == null)
            return redirect()->route('events.index');

        $newEvent = new Event();
        $newEvent->timetable = $timetable->id;
        $newEvent->author = Auth::id();
        $newEvent->description = $request->title;
        $newEvent->begin = $request->begin;
        $newEvent->end = $request->end;
        $newEvent->isPublic = true;             // TODO

        if($timetable->author == Auth::id())
            $newEvent->validated = true;
        else if(Management::where([['manager', '=', Auth::id()],
                                   ['timetable', '=', $timetable->id]])->exists())
            $newEvent->validated = true;
        else
            $newEvent->validated = false;

        $newEvent->isExclusive = false;     // TODO
        $newEvent->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $timetable = Timetable::where('id', '=', $event->timetable)->first();

        // check that the current timetable exists
        if($timetable == null)
            return redirect()->route('events.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        if(!$this->checkManageRights($event))
            return redirect()->route('events.index');

        $event->update($request->all());

        echo 'ouiii';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);

        if(!$this->checkManageRights($event))
            return redirect()->route('events.index');

        $event->delete();
    }

    private function checkManageRights($event){
        $timetable = Timetable::where('id', '=', $event->timetable)->first();
        if($timetable == null)
            return false;
        if($timetable->author != Auth::id()){
            $isManager = Management::where([['manager', '=', Auth::id()],
                                            ['timetable', '=', $timetable->id]]
                                            )->exists();
            if(!$isManager)
                return false;
        }

        return true;
    }
}

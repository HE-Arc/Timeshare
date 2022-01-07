<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\Management;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class TimetableController extends Controller
{
    private $currentSharedTimetable = null;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$timetables = Timetable::where("management.manager", '=', Auth::id())
        //             ->join("management", 'management.timetable', '=', 'timetables.id');

        //$timetables = DB::table('management')
        //                  ->join('timetables', 'timetables.id', '=', 'management.timetable')
        //                  ->select('timetables.*')
        //                  ->get();

        $manageTimetables = Management::join('timetables', 'timetables.id', '=', 'management.timetable')
                                  ->where('management.manager', '=', Auth::id())
                                  ->get();

        $myTimetables = Timetable::where('author', '=', Auth::id())->get();
        $sharedTimetable = $this->currentSharedTimetable;
        return inertia('Timetables/Index', compact('myTimetables', 'manageTimetables', 'sharedTimetable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Nothing here, the form is integrated in index
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
            'isPublic' => 'required|boolean',
            'title' => 'required|min:2|max:15'
        ]);

        $timetable = new Timetable();
        $timetable->isPublic = $request->isPublic;
        $timetable->author = Auth::id();
        $timetable->title = $request->title;
        $timetable->save();

        return redirect()->route('timetables.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function show(Timetable $timetable)
    {
        $this->currentSharedTimetable = $timetable;
        return $this->index();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function edit(Timetable $timetable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timetable $timetable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timetable $timetable)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\OnlineSession;
use App\TimeRecord;
use Illuminate\Foundation\Console\PackageDiscoverCommand;
use Illuminate\Http\Request;

class OnlineSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function userOnline($timeID, $username) {
        $session = OnlineSession::where("timeID", '=', $timeID)->where("username", '=', $uuid)->get();
        if($session == null) {
            return false;
        } else {
            return $session;
        }
    }
    public function getCurrentTimeID() {
            return 1;
    }
    public function uploadMassSessions(Request $request) {
        $data = $request->json()->all();
        //$players = $request->input("players");
        //echo $players;
        //var_dump($data);
        $players = $data["players"];
        $server = $data["server"];
        $requestInterval = 15;
        //var_dump($players);
        $timeLast = TimeRecordController::getLastTimeID($server);
        $lastTimeRecord = TimeRecord::where("server", "=", $server)->where("timeID", $timeLast)->first();

        //sessiontime = (past session time + diff(last time record, now)(max 15)
        $timeString = data["time"];
        $time = new \DateTime($timeString);
        $timeRecord = TimeRecordController::createNewTimeRecord($server, $time);
        $timeID = $timeRecord["timeID"];
        TimeRecordController::timeBetweenLast($server, $timeRecord, $lastTimeRecord);
        foreach($players as $player) {
            echo $player["username"];

            $pastSession = false; //$this->userOnline($timeID-1, $player["uuid"]);
            $pastTime = 0;
            if ($pastSession["time"] != 0) {
                $pastTime = $pastSession["time"];
            }

            $thisSessionTime = 15; //make sure the difference between the pastTimeID is more than 15 min,
            // to avoid it going in a loop adding more time than there was. Also if there's more submissions than there's supposed to be. 15 min will be the max. On server startup it will keep track of the time.
            //submit at x:00, x:15, x:30, x:45 - to make it even.
            $record = new OnlineSession;
            $record->username = $player["username"];
            //$record->uuid = $player["uuid"];
            $record->recordID = 0; //auto increment
            //$record->recordTime = time();
            $record->sessionLength = $pastTime + $thisSessionTime;
            $record->timeID = $timeID;
            $record->save();

        }
        echo "test";

        //check if there was a previous record for the past record ID for each username,
        // if there was then it would delete that record and add the session length of that record
        // to create a current record with the times added up. This would allow you to get  the session time rounded up to 15 minutes.
        // it would also record the amount of blocks mined in a new table that is connected to each session, as well as blocks mined for each hour in order to measure activity
        // this would allow recording activity per session, as well as per hour.
        //Every hour blocks mined, PVP kills or attacks, and number of chat messages and commands will be recorded.

        //all chats and commands will be recorded, if there's another player mentioned in the chat it will tag them,
        //this should let us see relations between players

        // Record ID | username | record time | Time ID (incremental every 15 minutes) | session length

    }
}

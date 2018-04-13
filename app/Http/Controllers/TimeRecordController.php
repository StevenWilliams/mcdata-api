<?php

namespace App\Http\Controllers;

use App\TimeRecord;
use Illuminate\Http\Request;

class TimeRecordController extends Controller
{
    /**
     * So the time record controller will have TimeID | Server | DateTime
     * To get the latest time it would find the last timeID of the requested server
     * id is the record ID, it's autoincrement, timeID is an incremental for each server.
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static function timeBetweenLast($server, $time1, $time2) {

        $dt1 = $time1->dateTime; // new \DateTime($time1->dateTime);
        $dt2 = new \DateTime($time2->dateTime); //new \DateTime($time2->dateTime);
        $interval = $dt1->diff($dt2);
        $minutes = $interval->format("%i:%S");
        //$minutesInt = (int) $minutes;
        //var_dump($dt1);
        //var_dump($dt2);
        //var_dump($interval);
        return $interval;
        //var_dump($time2->dateTime);

    }
    public static function getLastTimeID($server) {
        $timeRecord = TimeRecord::where("server", "=", $server)->orderBy("timeID", "desc")->first();
        //var_dump($timeRecord);
        //var_dump($timeRecord["timeID"]);
        if($timeRecord["timeID"] == null) {
            $lastTimeID = 0;
        } else {
            $lastTimeID = $timeRecord["timeID"];
        }
        return $lastTimeID;
    }
    public static function createNewTimeRecordCurrentTime($server) {
        $datetime = new \DateTime();
        self::createNewTimeRecord($server, $datetime);
    }
    //create time record for current time
    public static function createNewTimeRecord($server, $datetime) {
        $lastTime = self::getLastTimeID($server);
        $timerecord = new TimeRecord();
        $timerecord->dateTime = $datetime; // new \DateTime();
        $timerecord->server = $server;
        $timerecord->timeID = $lastTime + 1;
        $timerecord->save();
        $time2 = TimeRecord::where("timeID", "=", $lastTime)->where("server", "=", $server)->first();

        self::timeBetweenLast($server, $timerecord, $time2 );
        //var_dump($timerecord);
       //getLastTimeID($server);
        return $timerecord;
    }
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $server = $request->input("server");
        $datetime = $request->input("datetime");
        $dateTime = new \DateTime($datetime);

        $timerecord = $this->createNewTimeRecord($server, $dateTime);
        //var_dump($timerecord);
        echo "timeID: ". $timerecord->timeID . " primaryID: " . $timerecord->id;
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
}

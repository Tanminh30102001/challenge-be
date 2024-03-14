<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\Status;
use App\Models\TypeofTask;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function getAllStatus(){
        return  Status::all();
    }
    public function addStatus(Request $request){
        $data= $request->all();
        $status=new Status();
        $status->name=$data["name"];
        $status->desc=$data["desc"];
        $status->save();
    }
    public function update(Request $request,$id){
        $data= $request->all();
        $status=Status::find($id);
        if ($status!=null) {
        $status->name=$data["name"];
        $status->desc=$data["desc"];
        $status->save();
        return response()->json(["message"=> "Update Success"],200);
        }
        return response()->json(["message"=> "Not found status"],400);
    }
    public function deleteStatus($id){
        $status=Status::find($id);
        if ($status!=null) {
            $status->delete();
            return response()->json(["message"=> "Delete Success"],200);
        }
        return response()->json(["message"=> "Not found status"],400);
    }
    public function getAllType(){
        return TypeofTask::all();
    }
    public function getAllPriority(){
        return Priority::all();
    }
}

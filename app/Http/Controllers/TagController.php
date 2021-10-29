<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

DB::enableQueryLog();




class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        $result =  Tag::orderBy('id', 'desc')->get();
        if($result->isNotEmpty()){
            $query = DB::getQueryLog();
            //Log query DB
            Log::create([
                'user' => $user->email,
                'action' => 'Get All Tags',
                'query' => DB::table('tags')->toSql(),
                'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '', '--')
            ]);
            return response([
                'message' => 'Data Loaded ',
                'result' => $result,
                'get' => true,
            ], 200);
        }else{
            return 'no result';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function countTags(Request $request)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        $result =  Tag::all()->count();
        if($result != 0){
            $query = DB::getQueryLog();
            //Log query DB
            Log::create([
                'user' => $user->email,
                'action' => 'Get All Tags Count',
                'query' => DB::table('tags')->toSql(),
                'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '', '--')
            ]);
            return response([
                'message' => 'Data Loaded',
                'result' => $result,
                'count' => true,
            ], 200);
        }else{
            return 'no result';
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        if ($user->tokenCan('admin:data_entry')) {
            $request->validate([
                'tag_no' => 'required',
                'staff_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'appointment' => 'required',
                'department' => 'required',
                'address' => 'required',
                'vehicle_type' => 'required',
                'vehicle_model' => 'required',
                'vehicle_color' => 'required',
                'vehicle_plate_no' => 'required',
                'vehicle_chasis_no' => 'required',
                'authorized_staff_name' => 'required',
                'authorized_staff_appointment' => 'required',
                'slug'=> 'required',
            ]);
            $result =  Tag::create($request->all());
            if($result){
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Create Tag',
                    'query' => $query[0]['query'],
                    'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '', json_encode([$request->tag_no, $request->staff_name]))
                ]);
                return response([
                    'message' => 'New Vehicle Tag Creation Successful',
                    'creation' => true
                ], 200);
            }else{
                return response([
                    'message' => 'New Vehicle Tag Creation Failed',
                    'creation' => false
                ], 200);
            }
        }else{
            return response('Forbidden',  403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $xFind)
    {
        
		$user = $request->user();
		if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
		 $result =  Tag::where('tag_no', '=', $xFind)
                ->orWhere('id', '=', $xFind)
                ->get();
        if($result){
            $query = DB::getQueryLog();
            //Log query DB
            Log::create([
                'user' => $user->email,
                'action' => 'Get Specific Tag',
                'query' => $query[0]['query'],
                'parameters'=> $xFind
            ]);
            return response([
                'message' => 'Data Loaded ',
                'result' => $result,
                'find' => true,
            ], 200);			
        }else{
            return response([
                'message' => 'TagNo Not Found ',
                'result' => $result,
                'find' => false,
            ], 200);	
        }
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
        $user = $request->user();
		if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        if ($user->tokenCan('admin:data_entry')) {
            $tag = Tag::find($id);
            if($tag){
                $tag->update($request->all());
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Update Tag',
                    'query' => $query[0]['query'],
                    'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '-', json_encode([$request->tag_no, $request->staff_name]))
                ]);
                return response([
                    'message' => 'Vehicle Tag Update Successful ',
                    'result' => $tag,
                    'update' => true,
                ], 200);
            }else{
                return response([
                    'message' => 'Error Updating Tag',
                    'update' => false,
                ], 200);
            }
        }else{
            return response('Forbidden',  403);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,$id)
    {
        $user = $request->user();
		if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        if ($user->tokenCan('admin:super')) {
			$tag = Tag::find($id);
            $result =  Tag::destroy($id);
            if($result){
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Delete Tag',
                    'query' => $query[0]['query'],
                    'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '', json_encode([$tag->tag_no, $tag->staff_name]))
                ]);
				return response([
                    'message' => 'Deleted Tag',
                    'delete' => true,
                ], 200);
            }else{
				return response([
                    'message' => 'Error Deleting Tag',
                    'delete' => false,
                ], 200);
            }
        }else{
            return response('Forbidden',  403);
        }
    }

    /**
     * Search resource from storage.
     *
     * @param  str  $identifier
     * @return \Illuminate\Http\Response
     */
    // LOG SEARCH QUERY
    public function search(Request $request, $identifier)
    {
		$user = $request->user();
		if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        $result =  Tag::where('tag_no', 'like', '%'.$identifier.'%')
                ->orWhere('vehicle_plate_no', 'like', '%'.$identifier.'%')
                ->get();
        if($result->isNotEmpty()){
            // Create a row in table with username and identifier in checking log
            $query = DB::getQueryLog();
            //Log query DB
            Log::create([
                'user' => $user->email,
                'action' => 'Search / Scan Tag',
                'query' => $query[0]['query'],
                'parameters'=> $identifier
            ]);
            return response([
                'message' => 'Data Loaded ',
                'result' => $result,
                'search' => true,
            ], 200);			
        }else{
            return response([
                'message' => 'TagNo Not Found ',
                'result' => $result,
                'search' => false,
            ], 200);	
        }
    }
}

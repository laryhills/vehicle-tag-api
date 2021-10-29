<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Fields;
class LogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'register' => false
            ], 200);
        }
        if ($user->tokenCan('admin:super')) {
            $result = Log::where('action', 'not like', 'Get All Tags Count')
				->where('action', 'not like', 'Get All Tags')
				->where('action', 'not like', 'Get Specific Tag')
                ->orderBy('created_at', 'desc')
				->take(40)
				->get();
            if ($result->isNotEmpty()) {                
                
			return response([
                'result' => $result,
                'getLog' => true
            ], 200);
            } else {
                return response([
                'message' => 'No Logs to display',
                'getLog' => false
            ], 200);
            }
        }else{
            return response([
                'message' => 'You are not Authorized',
                'getLog' => false
            ], 200);
        }
    }
	
	 public function destroy(Request $request)
    {
        $user = $request->user();
		if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'get' => false
            ], 200);
        }
        if ($user->tokenCan('admin:super')) {
			$result =  Log::destroy();
            if($result){
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Cleared Log',
                    'query' => $query[0]['query'],
                    'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '-', '--')
                ]);
				return response([
                    'message' => 'Cleared Log',
                    'clearLog' => true,
                ], 200);
            }else{
				return response([
                    'message' => 'Error Clearing Log',
                    'clearLog' => false,
                ], 200);
            }
        }else{
            return response('Forbidden',  403);
        }
    }
}

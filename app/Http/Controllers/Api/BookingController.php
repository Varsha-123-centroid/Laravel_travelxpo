<?php
namespace App\Http\Controllers\Api;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class BookingController extends Controller
{
    use ApiHelpers; // <---- Using the apiHelpers Trait

    //

    public function getMarkupAmt(Request $request): JsonResponse
    {
        $agentType = $request->input('agent_type');
         // Get the branch ID based on agent type and agent ID
        $markup_percent = DB::table('markup_percent')
            ->get();
			
 foreach($markup_percent as $key=>$val)
 {
	
	$data[$val->markup_for]= $val->markup_amt; 
	
 }
 return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    


    public function booking(Request $request): JsonResponse
    {

    }

  
}

<?php
namespace App\Http\Controllers\Api;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class AgentController extends Controller
{
    use ApiHelpers; // <---- Using the apiHelpers Trait

    //

    public function getBalance(Request $request): JsonResponse
    {
        $agentType = $request->input('agent_type');
        $agentId = $request->input('agent_id'); 
         // Get the branch ID based on agent type and agent ID
        $branchId = DB::table('branch')
            ->where('user_type', $agentType)
            ->where('user_id', $agentId)
            ->value('id');

        // Get the balance from the payment table based on the last inserted branch ID
		$data['balance'] = DB::table('cash_balance')
             ->where('branch_id', $branchId)
             ->orderBy('id', 'desc')
             ->limit(1)
             ->value('balance');
        
            return $this->onSuccess($data, 'Balance Retrieved');
 
    }
    
  
}

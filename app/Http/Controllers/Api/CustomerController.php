<?php
namespace App\Http\Controllers\Api;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class CustomerController extends Controller
{
    use ApiHelpers; // <---- Using the apiHelpers Trait

    //
public function customerAuth(Request $request): JsonResponse
{
    $email = $request->input('email');
    $password = $request->input('password');

    // Check if a customer with the provided email exists
    $existingCustomer = DB::table('b2c_customer_login')->where('email', $email)->first();

    if ($existingCustomer) {
        // Customer exists, perform login
        if ($existingCustomer->password === $password) {
            // Password matches; return the customer ID or token
            return response()->json(['customer_id' => $existingCustomer->id], 200);
        } else {
            // Password does not match; return an error
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    } else {
        // Customer does not exist, perform registration
        // Insert the new customer into the "b2c_customer_login" table
        $customerId = DB::table('b2c_customer_login')->insertGetId([
            'email' => $email,
            'password' => $password, // Note: This is not secure; use password hashing
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return the newly created customer ID
        return response()->json(['customer_id' => $customerId], 201);
    }
}
  
}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class MembershipController extends Controller
{
    public function registerMember(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'merchant_name' => 'required|string|max:255',
            'type_business' => 'required|string|max:255',
        ]);

        try {
            // Insert data into the database
            DB::table('members')->insert([
                'user_id' => $validated['user_id'],
                'merchant_name' => $validated['merchant_name'],
                'type_business' => $validated['type_business'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Return success response
            return response()->json(['message' => 'Member registered successfully'], 201);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'Failed to register member', 'details' => $e->getMessage()], 500);
        }
    }
}

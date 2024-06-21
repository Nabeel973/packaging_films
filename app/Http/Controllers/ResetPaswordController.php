<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPaswordController extends Controller
{
    public function reset(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Assuming the user is authenticated
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        // Update the user's password
        $user->password = Hash::make($request->input('password'));
        $user->change_password = 1;
        $user->save();

        // Update the session to indicate the password has been changed
        session(['change_password' =>  $user->change_password]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    public function index(){
       return view('user_management.index'); 
    }

    public function list(){
        $users = User::join('roles','roles.id','users.role_id')
                ->select('users.*','roles.name as role')->get();
        return response()->json($users);
     }

     public function add(){

        $roles = DB::table('roles')->where('id','>',1)->select('id','name')->get();
        return view('user_management.add',compact('roles'));
     }

     public function submit(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role,
        ]);

        // Assign role to the user
        $role = DB::table('roles')->where('id',$request->role)->first();        
        $user->assignRole($role->name);

        // Redirect to a specific route with success message
        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function edit($id){
        $user = User::find($id);
        $roles = $roles = DB::table('roles')->where('id','>',1)->select('id','name')->get();
        return view('user_management.edit',compact('roles','user'));
    }

    public function update(Request $request, $id)
    {
        // Fetch the user to be updated
        $user = User::find($id);
    
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }
    
        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;
    
        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->role_id = $request->role;
        $user->save();
    
        // Assign role to the user
        $role = DB::table('roles')->where('id', $request->role)->first();
        $user->syncRoles([$role->name]);
    
        // Redirect to a specific route with success message
        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }
    
}

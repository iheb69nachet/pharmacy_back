<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // if (!auth()->user() || !auth()->user()->hasRole('admin')) {
            //     throw UnauthorizedException::forRoles(['admin']);
            // }
            return $next($request);
        });
    }
    public function listAll(){
            $users=User::select('id', 'username', 'email')->get();
            return response()->json([
                'success' => true,
                'data' =>  $users,
                
            ]);
        }
    public function AddUser(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
    public function getById(User $id){
        $user=$id;
        $id->role=$id->getRoleNames();
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
    public function EditUser(Request $request,$id){
         // Fetch the user by id
         $user = User::findOrFail($id);

         // Define validation rules
         $validator = Validator::make($request->all(), [
             'username' => 'sometimes|string|max:255|unique:users,username,' . $id,
             'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
             'password' => 'nullable|string|min:6|confirmed',
             'role' => 'sometimes|string|exists:roles,name',
         ]);
 
         // Check if validation fails
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 422);
         }
 
        $user->update($request->all());
        if($request->role){
            $user->syncRoles([$request->role]);
        }
         $user->save();
 
         // Return success response
         return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
     }
     public function destroy($id)
     {
         try {
             $user = User::findOrFail($id);
             $user->delete();
             return response()->json(['message' => 'User deleted successfully'], 200);
         } catch (\Exception $e) {
            return response()->json(['message' => 'User not found or could not be deleted'], 404);
         }
     }
    }


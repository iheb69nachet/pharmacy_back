<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    public function listAll(){
        $users=Customer::select('id', 'first_name','last_name','email','company')->get();
        return response()->json([
            'success' => true,
            'data' =>  $users,
            
        ]);
    }
public function AddUser(Request $request){
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'company' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:customers,email',
    ]);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    $user = Customer::create([
        'first_name' => $request->first_name,
        'email' => $request->email,
        'last_name' =>  $request->last_name,
        'company' =>  $request->company,
    ]);
    return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
}
public function getById(Customer $id){
    $user=$id;
    return response()->json([
        'success' => true,
        'data' => $user
    ]);
}
public function EditUser(Request $request,$id){
     // Fetch the user by id
     $user = Customer::findOrFail($id);

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
         $user = Customer::findOrFail($id);
         $user->delete();
         return response()->json(['message' => 'User deleted successfully'], 200);
     } catch (\Exception $e) {
        return response()->json(['message' => 'User not found or could not be deleted'], 404);
     }
 }
}

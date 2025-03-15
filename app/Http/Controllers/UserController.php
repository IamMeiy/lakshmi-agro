<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /* Below code to redirect to user page */
    public function index(){
        return view('user');
    }

    /* Below code to get the data */
    public function getData(){
        $users = User::all();
        return DataTables::of($users)->make(true);
    }

    /* Below code to store the data */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {   
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'User Created!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /* Below code to edit the data */
    public function edit($id){
        $user = User::find($id);
        if($user){
            return response()->json($user);
        }
    }

    /* Below code to update the data */
    public function update(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$request->user_id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        
        DB::beginTransaction();
        $user = User::find($request->user_id);
        try {   
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if($request->password){
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            DB::commit();
            $message = ['status' => 'success', 'message' => 'User Updated!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }
    
    /* Below code to delete the data */
    public function delete($id){
        $user = User::find($id);
        if($user){
            $user->delete();
            $message = ['status' => 'success', 'message' => 'User Deleted!'];
        }
        else{
            $message = ['status' => 'error', 'message' => 'User Not Deleted !'];
        }
        return response()->json($message);
    }
}

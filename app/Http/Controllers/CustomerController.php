<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\User;

class CustomerController extends Controller
{

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // function __construct()
    // {
    //     $this->middleware('permission:landlord-list', ['only' => ['index', 'show', 'dashboard']]);
    //     $this->middleware('permission:landlord-create', ['only' => ['create', 'store']]);
    // }

    public function index()
    {
        return User::get();
    }

    public function dashboard(Request $request)
    {
        return Auth('api')->user();
    }

    public function signUp(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'bail|unique:users|required|email:rfc,dns|max:255',
            'password' => 'required',
            'role_id' => 'required'
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return  response()->json(["message" => $validator->errors()], 400);
        }


        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role_id = $request->role_id;
        $user->password = bcrypt($request->password);
        $user->save();


        $role = '';
        $roleDetails = '';
        if ($request->role_id == 1) {
            $role = 'Super Admin';
            $roleDetails = 'access super admin backend';
        } else if ($request->role_id == 2) {
            $role = 'Admin';
            $roleDetails = 'access admin app';
        } else if ($request->role_id == 3) {
            $role = 'landlord';
            $roleDetails = 'access landlord app';
        } else if ($request->role_id == 4) {
            $role = 'renter';
            $roleDetails = 'access renter app';
        }


        return response(array("message" => "Account Created.", "data" => [
            "user" => $user,
            // "token" => $user->createToken($roleDetails)->accessToken
            "token" => $user->createToken($roleDetails, [$role])->accessToken

        ]), 200);
    }


    public function signIn(Request $request)
    {
        $rules = [
            'email' => 'required|email:rfc,dns|max:255',
            'password' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return  response()->json(["message" => $validator->errors()], 400);
        }

        if (User::where('email', $request->email)->count() <= 0) return response(array("message" => "Email number does not exist"), 400);

        $user = User::where('email', $request->email)->first();

        if (password_verify($request->password, $user->password)) {

            $role = '';
            $roleDetails = '';
            if ($user->role_id == 1) {
                $role = 'Super Admin';
                $roleDetails = 'access super admin backend';
            } else if ($user->role_id == 2) {
                $role = 'Admin';
                $roleDetails = 'access admin app';
            } else if ($user->role_id == 3) {
                $role = 'landlord';
                $roleDetails = 'access landlord app';
            } else if ($user->role_id == 4) {
                $role = 'renter';
                $roleDetails = 'access renter app';
            }

            return response(array("message" => "Sign In Successful", "data" => [
                "user" => $user,
                // "token" => $user->createToken($roleDetails)->accessToken
                "token" => $user->createToken($roleDetails, [$role])->accessToken
                // "token" => $customer->createToken('access admin backend', ['staff'])->accessToken
            ]), 200);
        } else {
            return response(array("message" => "Wrong Credentials."), 400);
        }
    }
}

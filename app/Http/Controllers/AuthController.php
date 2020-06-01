<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use  App\Subscriber;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {
           
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Store a new subscriber.
     *
     * @param  Request  $request
     * @return Response
     */
    public function addSubscriber(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name'  =>  'required|string',
            'email' =>  'required|email|unique:subscriber',
            'dob'   =>  'required',
            'mobile'=>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'city'  =>  'required|string',
            'amount'=>  'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        try {
           
            $subscriber         = new Subscriber;
            $subscriber->name   = $request->input('name');
            $subscriber->email  = $request->input('email');
            $subscriber->dob    = $request->input('dob');
            $subscriber->mobile = $request->input('mobile');
            $subscriber->city   = $request->input('city');
            $subscriber->amount = $request->input('amount');
            $subscriber->save();

            //return successful response
            return response()->json(['data' => $subscriber, 'message' => 'Subscriber Added Succesfully'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Subscriber Registration Failed!'], 409);
        }

    }

    
}
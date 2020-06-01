<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use  App\User;
use  App\Subscriber;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all Subscriber.
     *
     * @return Response
     */
    public function allSubscriber()
    {
         return response()->json(['data' =>  Subscriber::all()], 200);
    }

    /**
     * Get one Subscriber.
     *
     * @return Response
     */
    public function singleSubscriber($id)
    {
        try {
            $subscriber = Subscriber::findOrFail($id);

            return response()->json(['data' => $subscriber], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'subscriber not found!'], 404);
        }

    }

    /**
     * Update Subscriber.
     *
     * @return Response
     */
    public function updateSubscriber(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'id'    =>  'required',
            'name'  =>  'required|string',
            'email' =>  'required|email|unique:subscriber,email,' . $request->input('id'),
            'dob'   =>  'required',
            'mobile'=>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'city'  =>  'required|string',
            'amount'=>  'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        try {
           
            $subscriber         = Subscriber::find($request->input('id'));
            $subscriber->name   = $request->input('name');
            $subscriber->email  = $request->input('email');
            $subscriber->dob    = $request->input('dob');
            $subscriber->mobile = $request->input('mobile');
            $subscriber->city   = $request->input('city');
            $subscriber->amount = $request->input('amount');
            $subscriber->save();

            //return successful response
            return response()->json(['data' => $subscriber, 'message' => 'Subscriber Updated Succesfully'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Subscriber Updated Failed!'], 409);
        }
    }

        /**
     * Confirmed Subscriber.
     *
     * @return Response
     */
    public function confirmSubscriber(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'id'    =>  'required',
        ]);

        try {
           
            $subscriber              = Subscriber::find($request->input('id'));
            $subscriber->confirmed   = 1;
            $subscriber->save();

            //return successful response
            return response()->json(['data' => $subscriber, 'message' => 'Subscriber Confirmed Succesfully'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Subscriber Confirmed Failed!'], 409);
        }
    }

    
       /**
     * Delete Subscriber.
     *
     * @return Response
     */
    public function deleteSubscriber(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'id'    =>  'required',
        ]);

        try {
           
            $subscriber = Subscriber::where('id',$request->input('id'))->delete();

            //return successful response
            return response()->json(['data' => $subscriber, 'message' => 'Subscriber Delete Succesfully'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Subscriber Delete Failed!'], 409);
        }
    }

}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class AuthController extends Controller
{
    /**
     * Register new user
     * @param  [string] name
     * @param  [string] nickname
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] data
     */
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'nickname' => 'required|unique:users|max:30',
            'email' => 'required|max:191|unique:users',
            'password' => 'required|min:7',
            'password_confirmation' => 'required|min:7|same:password',
        ]);

        // Check if validation failed
        if ($validator->fails()) {
            return response([
                'success' => false,
                'error' => $validator->errors(),
            ], Response::HTTP_UNAUTHORIZED);
        } else {
            // Save User to Database
            $user = new User;
            $user->name = $request->name;
            $user->nickname = $request->nickname;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $success['token'] = $user->createToken('AppName')->accessToken;
            $token = $user->createToken('AppName')->accessToken;

            return response([
                'success' => true,
                'message' => 'You account was successfully created!',
                'user' => $user,
                'token' => $token,

            ], Response::HTTP_CREATED);
        }
    }

    public function signIn(Request $request)
    {
        $log_status = false;
        $email = $request->email;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //user sent their email
            if (Auth::attempt(['email' => $email, 'password' => $request->password])) {
                $user = Auth::user();
                $log_status = true;
                $token = $user->createToken('AppName')->accessToken;

                // Update user record with date last logged in
                $datenow =  Carbon::now();
                $user->last_login = $datenow;
                $user->save();

                return response([
                    'success' => $log_status,
                    'message' => 'Authentication successful',
                    'user' => $user,
                    'token' => $token,
                ], Response::HTTP_CREATED);
            } else {
                return response([
                    'success' => $log_status,
                    'message' => 'Authentication failed',
                    'error' => 'Unauthorised',
                ], Response::HTTP_UNAUTHORIZED);
            }
        } else {
            // failed to supply email field
            return response([
                'success' => $log_status,
                'message' => 'Authentication failed',
                'error' => 'Unauthorised',
            ], Response::HTTP_UNAUTHORIZED);
        }

    }

    // Sign Out
    public function signOut()
    {
        Auth::logout();

        return response([
            'success' => true,
            'message' => 'You have successfully signed out!',
        ], Response::HTTP_CREATED);
    }

    /**
     * Get User
     * @return [string] data
     */
    public function getUser($id)
    {
        $data = User::where('id', $id)->with('gameplays')->first();
        return response([
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * Get Users
     * @return [string] data
     */
    public function getUsers()
    {
        $data = User::with('gameplays')->get();
        return response([
            'data' => $data,
        ], Response::HTTP_OK);
    }
}

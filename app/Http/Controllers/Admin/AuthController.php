<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProfileResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($this->attemptLogin($request)) {

            $user = $this->guard('api')->user();
            $user->generateToken();


            return response()->json([
                'id' => $user->id,
                'token' => $user->api_token,
            ]);
        } else {
            return $this->sendFailedLoginResponse($request);
        }
    }

    public function user(Request $request)
    {
        return new ProfileResource($request->user());
    }
}

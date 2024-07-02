<?php

namespace App\Http\Controllers;

use App\Events\InvitationCreated;
use App\Events\JoinInvitationCreated;
use App\Models\JoinInvitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function createSuperAdmin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function inviteEmployee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'invited_email' => 'required|string|email|max:255|unique:join_invitations',
            'invited_name' => 'required|string',
            'app_url' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $email = $request->input('invited_email');
        $name = $request->input('invited_name');
        $app_url = $request->input('app_url');

        $domain = substr(strrchr($email, "@"), 1);

// Generate the token
        $now = Carbon::now()->format('YmdHis'); // 14 characters
        $domainPart = substr($domain, 0, 6); // Take up to 6 characters from the domain

// Ensure exactly 20 characters by adjusting parts
        $token = substr($now . $domainPart, 0, 20);

        $joinInvitation = JoinInvitation::create([
            'invited_email' => $email,
            'app_url' => $app_url,
            'invited_name' => $name,
            'token' => $token,
            'user_id' => Auth::user()->id,
        ]);


        event(new JoinInvitationCreated($joinInvitation));

        return response()->json([
            'message' => 'invitation sent successfully',
            'token' => $token
        ]);
    }


    public function getEmployees(Request $request)
    {
        $name = $request->input('name');
        $sortDirection = $request->input('sort', 'asc');

        $employees = User::employees($name, $sortDirection)->get();

        return response()->json(['employees' => $employees]);
    }

    public function getCompanyEmployees(Request $request)
    {
        $name = $request->input('name');
        $company_id = Auth::user()->company_id;
        $sortDirection = $request->input('sort');

        $employees = User::employees($name, $sortDirection)->where('company_id', $company_id)->get();

        return response()->json(['auth user Company employees' => $employees]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\InvitationCreated;
use App\Events\JoinInvitationCreated;
use App\Models\EventLog;
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

    public function getEmployees(Request $request)
    {
        $name = $request->input('name');
        $sortDirection = $request->input('sort');

        $employees = User::employees($name, $sortDirection)->get();

        return response()->json(['employees' => $employees]);
    }

    public function getSuperAdmins(Request $request)
    {
        $name = $request->input('name');
        $sortDirection = $request->input('sort');

        $admins = User::superAdmins($name, $sortDirection)->get();

        return response()->json(['admins' => $admins]);
    }

    public function getCompanyEmployees(Request $request)
    {
        $name = $request->input('name');
        $company_id = Auth::user()->company_id;
        $sortDirection = $request->input('sort');

        $employees = User::employees($name, $sortDirection)->where('company_id', $company_id)->get();

        return response()->json(['employees' => $employees]);
    }

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

    public function validateEmployeeCreation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'password' => 'required|string|min:6',
            'token' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $invitation = JoinInvitation::where('token', $request->token)->first();

        if ($invitation) {
            EventLog::create([
                'event_name' => 'invitation validated',
                'triggered_by_name' => $invitation->invited_name,
                'triggered_by_role' => 'employee',
                'invited_employee_name' => $invitation->invited_name,

            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $invitation->invited_email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
                'company_id' => $invitation->company_id,
            ]);
            if ($user) {
                EventLog::create([
                    'event_name' => 'employee confirmed',
                    'triggered_by_name' => $invitation->invited_name,
                    'triggered_by_role' => 'employee',
                    'invited_employee_name' => $invitation->invited_name,
                ]);
                $invitation->status = 'validated';
            }

            $invitation->save();

            return response()->json([
                'message' => 'employee validated successfully',
                'user' => $user
            ]);


        } else {
            if ($validator->fails()) {
                return response()->json([
                    'error' => 'no invitation found',
                ], 422);
            }
        }

    }

    public function inviteEmployee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'invited_email' => 'required|string|email|max:255|unique:join_invitations',
            'invited_name' => 'required|string',
            'app_url' => 'required|string',
            'company_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $email = $request->input('invited_email');
        $name = $request->input('invited_name');
        $app_url = $request->input('app_url');
        $company_id = $request->input('company_id');

        $local_part = strstr($email, '@', true);


// Generate the token
        $now = Carbon::now()->format('YmdHis'); // 14 characters
        $local_part = substr($local_part, 0, 6); // Take up to 6 characters from the domain

        $combined = $now . $local_part;

        $shuffled = str_shuffle($combined);
        $token = substr($shuffled, 0, 20);

        $joinInvitation = JoinInvitation::create([
            'invited_email' => $email,
            'app_url' => $app_url,
            'invited_name' => $name,
            'token' => $token,
            'user_id' => Auth::user()->id,
            'company_id' => $company_id,
        ]);


        event(new JoinInvitationCreated($joinInvitation));

        return response()->json([
            'message' => 'invitation sent successfully',
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'email' => 'required|string|email',
            'phone_number' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user = User::findOrFail(Auth::user()->id);

        $user->update($request->all());

        return response()->json([
            'message' => 'user updated successfully',
            'user' => $user
        ]);

    }

}

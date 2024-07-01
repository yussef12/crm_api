<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('sort')) {
            $sortField = $request->input('sort');
            if ($sortField === 'name') {
                $query->orderBy('name');
            } elseif ($sortField === '-name') {
                $query->orderByDesc('name');
            }
        }


        $companies = $query->get();

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'name' => 'required|string',
            'size' => 'required|in:small,medium,big',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }


        $company = Company::create([
            'name' => $request->input('name'),
            'size' => $request->input('size'),
        ]);

        return response()->json([
            'message' => 'Company created successfully.',
            'company' => $company,
        ], 201);
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);

        return $company;
    }

    public function update(Request $request, $id)
    {
        $validator =Validator::make($request->all(), [
            'name' => 'required|string',
            'size' => 'required|in:small,medium,big',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json([
            'message' => 'Company updated successfully.',
            'company' => $company
        ]);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->employees()->exists()) {
            return response()->json([
                'error' => 'Cannot delete company with employees.'
            ], 422);
        }

        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.'
        ]);
    }
}

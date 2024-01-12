<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function index()
    {
        return Website::all();
    }

    public function show($id)
    {
        return Website::findOrFail($id);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => 'required|url|unique:websites,url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new website
        $website = Website::create($validator->validated());

        return response()->json(['message' => 'Website created successfully', 'data' => $website], 201);
    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'url' => 'required|url|unique:websites,url,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update the website
        $website = Website::findOrFail($id);
        $website->update($validator->validated());

        return response()->json(['message' => 'Website updated successfully', 'data' => $website]);
    }

    public function destroy($id)
    {
        $website = Website::findOrFail($id);
        $website->delete();
        return response()->json(['message' => 'Website deleted successfully']);
    }
}

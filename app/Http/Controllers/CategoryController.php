<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->get();

        return response()->json([
            "status" => "sukses",
            "data" => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validated->errors()
            ]);
        }

        $category = Category::query()->create([
            "name" => $request->name
        ]);

        return response()->json([
            "status" => "sukses",
            "data" => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::query()->findOrFail($id);

        return response()->json([
            "status" => "sukses",
            "data" => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validated->errors()
            ]);
        }

        $category = Category::query()->findOrFail($id);

        $category->update([
            "name" => $request->name
        ]);

        return response()->json([
            "status" => "sukses",
            "data" => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::query()->findOrFail($id);

        $category->delete();

        return response()->json([
            "status" => "sukses menghapus",
            "data" => $category
        ]);
    }
}

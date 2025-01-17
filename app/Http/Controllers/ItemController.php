<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $items = Item::query()->with('category')->get();

        return response()->json([
            "status" => "sukses mengambil data item",
            "data" => $items
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
            "category_id" => "required|exists:categories,id",
            "name" => 'required|string',
            "price" => 'required|numeric',
            "description" => 'nullable|string'
        ]);

        if ($validated->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $item = Item::query()->create([
            "category_id" => $request->category_id,
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description
        ]);

        return response()->json([
            "status" => "sukses membuat data item",
            "data" => $item
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::query()->findOrFail($id);

        return response()->json([
            "status" => "sukses mengambil data item",
            "data" => $item
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
            "category_id" => "required|exists:categories,id",
            "name" => 'required|string',
            "price" => 'required|numeric',
            "description" => 'nullable|string'
        ]);

        if ($validated->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $item = Item::query()->findOrFail($id);

        $item->update([
            "category_id" => $request->category_id,
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description
        ]);

        return response()->json([
            "status" => "sukses membuat data item",
            "data" => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::query()->findOrFail($id);

        $item->delete();

        return response()->json([
            "status" => "sukses menghapus data item",
            "data" => $item
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Customer::query()->get();

        return response()->json([
            "status" => "sukses mengambil data customer",
            "data" => $supplier
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
            "name" => "required|string",
            "phone" => "required|numeric|min:10",
            "email" => "nullable|email",
            "addresses" => "nullable|string"
        ]);

        if($validated->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $supplier = Customer::query()->create([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "addresses" => $request->addresses
        ]);

        return response()->json([
            "status" => "sukses menambah data customer",
            "data" => $supplier
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Customer::query()->findOrFail($id);

        return response()->json([
            "status" => "sukses mengambil data customer",
            "data" => $supplier
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
            "name" => "required|string",
            "phone" => "required|numeric|min:10",
            "email" => "nullable|email",
            "addresses" => "nullable|string"
        ]);

        if ($validated->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $supplier = Customer::query()->findOrFail($id);

        $supplier->update([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "addresses" => $request->addresses
        ]);

        return response()->json([
            "status" => "sukses mengupdate data customer",
            "data" => $supplier
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Customer::query()->findOrFail($id);

        $supplier->delete();

        return response()->json([
            "status" => "sukses menghapus data customer",
            "data" => $supplier
        ]);
    }
}

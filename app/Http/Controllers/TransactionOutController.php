<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionOutController extends Controller
{
    public function index()
    {
        $transactions = Transaction::query()->where('transaction_type', 'out')->get();

        return response()->json([
            "status" => "sukses mengambil data transaksi",
            "data" => $transactions
        ]);
    }

    public function show(string $id)
    {

        $transactions = Transaction::query()->with('items')->where('transaction_type', 'in')->find($id);

        return response()->json([
            "status" => "sukses mengambil data barang masuk",
            "data" => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $getRequest = $request->all();

        $validated = Validator::make($getRequest, [
            'transaction_type' => 'required|in:out',
            'transaction_date' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array',
        ]);

        if ($validated->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $userId = $request->user()->id;

        $transaction = Transaction::create([
            'transaction_type' => "out",
            'transaction_date' => $request->transaction_date,
            'user_id' => $userId,
            'supplier_id' => $request->supplier_id,
            'total_amount' => 0,
        ]);

        $totalAmount = 0;

        foreach ($getRequest['items'] as $item) {

            $itemData = Item::query()->find($item['id']);
            $subtotal = $item['quantity'] * $itemData->price;

            $transaction->items()->attach($item['id'], [
                "quantity" => $item['quantity'],
                "subtotal" => $subtotal
            ]);

            $quantityAdded = $itemData->quantity - $item['quantity'];

            $itemData->update([
                "quantity" => $quantityAdded
            ]);

            $totalAmount += $subtotal;
        }

        $transaction->update(['total_amount' => $totalAmount]);

        return response()->json([
            'message' => 'Transaction created successfully',
            'data' => $transaction->load('items'),
        ]);
    }


    public function update(Request $request, Transaction $barang_masuk)
    {
        $transaction = $barang_masuk;
        $getRequest = $request->all();


        $validated = Validator::make($getRequest, [
            'transaction_date' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validated->errors()
            ]);
        }

        $totalAmount = 0;
        $syncData = [];

        $transaction->update([
            'transaction_type' => "out",
            'transaction_date' => $request->transaction_date,
            'customer_id' => $request->customer_id,
        ]);

        foreach ($transaction->items as $item) {
            $item->update([
                'quantity' => $item->quantity - $item->pivot->quantity
            ]);
        }

        foreach ($getRequest['items'] as $item) {
            $itemData = Item::query()->findOrFail($item['id']);
            $subtotal = $item['quantity'] * $itemData->price;

            $syncData[$item['id']] = [
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];

            $itemData->update([
                "quantity" => $itemData->quantity - $item['quantity']
            ]);

            $totalAmount += $subtotal;
        }

        $transaction->items()->sync($syncData);
        $transaction->update(['total_amount' => $totalAmount]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction updated successfully',
            'data' => $transaction->load('items'),
        ]);
    }


    public function destroy(Transaction $barang_masuk)
    {
        $barang_masuk->items()->detach();
        $barang_masuk->delete();

        return response()->json([
            "status" => "menghapus data barang keluar",
            "data" => $barang_masuk->load('items')
        ]);
    }
}

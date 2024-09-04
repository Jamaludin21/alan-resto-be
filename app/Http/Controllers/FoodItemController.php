<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodItemController extends Controller
{
    public function index()
    {
        $foodItems = FoodItem::all();
        foreach ($foodItems as $item) {
            if ($item->image) {
                $item->image = base64_encode($item->image);
            }
        }
        return response()->json($foodItems);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $foodItem = new FoodItem();
        $foodItem->name = $request->name;
        $foodItem->price = $request->price;
        $image = $request->file('image');
        if ($image) {
            $imageData = $image->store('images', 'public');
            $foodItem->image = $imageData;
        }
        $foodItem->save();

        return response()->json($foodItem, 201);
    }

    public function show($id)
    {
        $foodItem = FoodItem::findOrFail($id);
        if ($foodItem->image) {
            $foodItem->image = base64_encode($foodItem->image);
        }
        return response()->json($foodItem);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $foodItem = FoodItem::findOrFail($id);

        $foodItem->name = $request->name;
        $foodItem->price = $request->price;
        $image = $request->file('image');
        if ($image) {
            $imageData = $image->store('images', 'public');
            $foodItem->image = $imageData;
        }
        $foodItem->save();

        return response()->json($foodItem);
    }

    public function destroy($id)
    {
        $foodItem = FoodItem::findOrFail($id);
        $foodItem->delete();

        return response()->json(null, 204);
    }
}

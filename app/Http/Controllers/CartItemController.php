<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    // GET all cart items
    public function index()
    {
        return CartItem::with('product')->get();
    }

    // POST add item to cart
    public function store(Request $request)
    {
        $cartItem = CartItem::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json($cartItem, 201);
    }

    // GET one cart item
    public function show(CartItem $cart)
    {
        return $cart->load('product');
    }

    // PUT/PATCH update cart item
    public function update(Request $request, CartItem $cart)
    {
        $cart->update([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return $cart;
    }

    // DELETE cart item
    public function destroy(CartItem $cart)
    {
        $cart->delete();

        return response()->json([
            'message' => 'Cart item deleted successfully'
        ]);
    }
}
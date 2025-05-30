<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Str;
use Helper;

class CartController extends Controller
{
    protected $product = null;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function addToCart(Request $request){
        if (empty($request->slug)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }

        $product = Product::where('slug', $request->slug)->first();

        if (empty($product)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }

        // Check if the product is in stock at all
        if ($product->stock <= 0) {
            request()->session()->flash('error', 'Product is currently out of stock.');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)
                            ->where('order_id', null)
                            ->where('product_id', $product->id)
                            ->first();

        if($already_cart) {
            $new_quantity = $already_cart->quantity + 1;

            // Check if adding one more item exceeds total stock
            if ($new_quantity > $product->stock) {
                return back()->with('error', 'The requested quantity exceeds available stock. Only ' . $product->stock . ' units are available.');
            }

            $already_cart->quantity = $new_quantity;
            // Recalculate amount to ensure accuracy, assuming product price is before discount for calculation here
            // If price in cart should already reflect discount, use $already_cart->price * $new_quantity
            $already_cart->amount = ($product->price - ($product->price * $product->discount) / 100) * $new_quantity;
            $already_cart->save();

        } else {
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100); // Price after discount
            $cart->quantity = 1; // Always adding 1 for this method

            // Check if adding the first item (quantity 1) exceeds stock (should be caught by $product->stock <= 0, but safe)
            if ($cart->quantity > $product->stock) {
                return back()->with('error', 'Cannot add this item due to insufficient stock.');
            }
            $cart->amount = $cart->price * $cart->quantity;
            $cart->save();

            // This line updates wishlists, ensure cart_id column exists in wishlist table
            Wishlist::where('user_id', auth()->user()->id)->where('cart_id', null)->update(['cart_id' => $cart->id]);
        }

        request()->session()->flash('success','Product successfully added to cart');
        return back();
    }

    public function singleAddToCart(Request $request){
        $request->validate([
            'slug'  => 'required',
            'quant' => 'required|array',
            'quant.1' => 'required|integer|min:1',
        ]);

        $quantity_to_add = $request->quant[1];

        $product = Product::where('slug', $request->slug)->first();

        if (empty($product)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }

        // Initial stock check for the requested quantity
        if ($quantity_to_add > $product->stock) {
            return back()->with('error', 'You can only add a maximum of ' . $product->stock . ' of this item. Requested quantity exceeds stock.');
        }
        
        $already_cart = Cart::where('user_id', auth()->user()->id)
                            ->where('order_id', null)
                            ->where('product_id', $product->id)
                            ->first();

        if($already_cart) {
            $new_total_quantity = $already_cart->quantity + $quantity_to_add;

            // Check if the new total quantity exceeds total stock
            if ($new_total_quantity > $product->stock) {
                return back()->with('error', 'Adding this quantity would exceed available stock. You currently have ' . $already_cart->quantity . ' in your cart and can add ' . ($product->stock - $already_cart->quantity) . ' more.');
            }

            $already_cart->quantity = $new_total_quantity;
            // Recalculate amount based on new total quantity and cart's stored price
            $already_cart->amount = $already_cart->price * $new_total_quantity;
            $already_cart->save();

        } else {
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = $quantity_to_add;
            
            // This check ensures that the quantity being added initially does not exceed stock
            if ($cart->quantity > $product->stock) {
                return back()->with('error', 'Requested quantity is more than available stock.');
            }
            $cart->amount = ($cart->price * $cart->quantity);
            $cart->save();
        }

        request()->session()->flash('success','Product successfully added to cart.');
        return back();
    }

    public function cartDelete(Request $request){
        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->delete();
            request()->session()->flash('success','Cart successfully removed');
            return back();
        }
        request()->session()->flash('error','Error please try again');
        return back();
    }

    public function cartUpdate(Request $request){
        if($request->quant){
            $error = []; // Use array literal for consistency
            $success = '';

            foreach ($request->quant as $k => $quant) {
                $id = $request->qty_id[$k];
                $cart = Cart::find($id);

                // Ensure cart and its associated product exist
                if($quant > 0 && $cart && $cart->product) {
                    // Check if the updated quantity exceeds total stock
                    if($quant > $cart->product->stock){
                        request()->session()->flash('error','Cannot update to ' . $quant . ' items for ' . $cart->product->title . '. Only ' . $cart->product->stock . ' available.');
                        return back(); // Stop and return on first error
                    }
                    
                    $cart->quantity = $quant;
                    
                    // Recalculate amount using the product's price and discount from the product model
                    $after_price = ($cart->product->price - ($cart->product->price * $cart->product->discount) / 100);
                    $cart->amount = $after_price * $quant;
                    $cart->save();
                    $success = 'Cart successfully updated!'; // This will only show if all updates are successful
                } else {
                    $error[] = 'Invalid quantity or cart item encountered!';
                }
            }

            // Display errors and success messages
            if(!empty($error)){
                request()->session()->flash('error', implode(' ', $error));
            }
            if(!empty($success)){
                request()->session()->flash('success', $success);
            }
            return back();
        } else {
            return back()->with('error', 'Cart update data is invalid!');
        }
    }

    public function checkout(Request $request){
        return view('frontend.pages.checkout');
    }
}
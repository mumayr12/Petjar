<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=['user_id','order_number','sub_total','quantity','delivery_charge','status','total_amount','first_name','last_name','country','post_code','address1','address2','phone','email','payment_method','payment_status','shipping_id','coupon'];

    public function cart_info(){
        return $this->hasMany('App\Models\Cart','order_id','id');
    }

    public static function getAllOrder($id){
        return Order::with('cart_info')->find($id);
    }

    // This method should count "active" orders based on your definition.
    // A common definition is orders that are not yet delivered or cancelled.
    public static function countActiveOrder(){
        return self::whereNotIn('status', ['delivered', 'cancelled'])->count();
    }

    /**
     * Get the count of new or recently received orders.
     * You'll need to define what 'new' or 'received' means for your application.
     * Common statuses for new orders might be 'new', 'pending', or 'received'.
     */
    public static function countNewReceivedOrder()
    {
        return self::where('status', 'new')
                    ->orWhere('status', 'pending')
                    ->count();
        // Adjust 'new' and 'pending' to match the actual status values in your database for new orders.
    }

    /**
     * Get the count of orders currently being processed.
     */
    public static function countProcessingOrder()
    {
        return self::where('status', 'processing')->count();
        // Adjust 'processing' to match your actual database status.
    }

    /**
     * Get the count of delivered orders.
     */
    public static function countDeliveredOrder()
    {
        return self::where('status', 'delivered')->count();
        // Adjust 'delivered' to match your actual database status.
    }

    /**
     * Get the count of cancelled orders.
     */
    public static function countCancelledOrder()
    {
        return self::where('status', 'cancelled')->count();
        // Adjust 'cancelled' to match your actual database status.
    }

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function shipping(){
        return $this->belongsTo(Shipping::class,'shipping_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
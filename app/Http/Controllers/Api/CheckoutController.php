<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckoutResource;
use App\Models\Coupons;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $coupon = Coupons::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where('expiry_date', '>=', now())
            ->first();

        if (!$coupon) {
            return new CheckoutResource(false, "Invalid or expired coupon code.", null);
        }

        $discountAmount = ($coupon->discount / 100) * $request->total_price;
        $totalAfterDiscount = $request->total_price - $discountAmount;

        return new CheckoutResource(true, "Coupon applied!", $totalAfterDiscount);
    }
}

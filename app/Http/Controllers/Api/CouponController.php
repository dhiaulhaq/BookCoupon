<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupons::latest()->paginate(5);

        return new CouponResource(true, 'Coupons List', $coupons);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_prefix' => 'required',
            'discount' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $couponCode = Coupons::generateCode($request->code_prefix);

        $coupon = Coupons::create([
            'code' => $couponCode,
            'discount' => $request->discount,
            'expiry_date' => $request->expiry_date,
        ]);

        return new CouponResource(true, 'Coupon added!', $coupon);
    }

    public function show(Coupons $coupon)
    {
        return new CouponResource(true, 'Coupon found!', $coupon);
    }

    public function update(Request $request, Coupons $coupon)
    {
        $validator = Validator::make($request->all(), [
            'discount' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('code_prefix')) {
            $couponCode = Coupons::generateCode($request->code_prefix);

            $coupon->update([
                'code' => $couponCode,
                'discount' => $request->discount,
                'expiry_date' => $request->expiry_date,
            ]);
        } else {
            $coupon->update([
                'discount' => $request->discount,
                'expiry_date' => $request->expiry_date,
            ]);
        }

        return new CouponResource(true, 'Coupon updated!', $coupon);
    }

    public function destroy(Coupons $coupon)
    {
        $coupon->delete();
        return new CouponResource(true, "Coupon deleted!", null);
    }
}

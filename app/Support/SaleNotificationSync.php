<?php

namespace App\Support;

use App\Models\Order;
use App\Models\SaleNotification;
use Illuminate\Support\Str;

class SaleNotificationSync
{
    public static function fromOrder(Order $order): void
    {
        $order->loadMissing([
            'shipping:id,order_id,name,phone',
            'orderdetails' => fn ($q) => $q->with(['product:id,name,slug', 'product.image'])->limit(1),
        ]);

        $detail      = $order->orderdetails->first();
        $product     = $detail?->product;
        $productName = $detail?->product_name ?? ($product?->name ?? 'একটি পণ্য');
        $productSlug = $product?->slug ?? null;
        $image       = $product?->image?->image ?? null;

        $customerName = $order->shipping?->name;
        if (empty($customerName)) {
            $phone = $order->shipping?->phone;
            $customerName = $phone ? 'Customer (' . substr($phone, -4) . ')' : 'Customer';
        }

        $firstName = explode(' ', trim((string) $customerName))[0];

        SaleNotification::updateOrCreate(
            ['order_id' => $order->id],
            [
                'customer_name' => Str::limit($firstName, 100, ''),
                'product_name'  => Str::limit($productName, 100, ''),
                'product_image' => $image,
                'product_url'   => $productSlug ? route('product', $productSlug) : null,
                'is_real'       => 1,
                'is_active'     => 1,
                'created_at'    => $order->created_at ?? now(),
                'updated_at'    => now(),
            ]
        );
    }
}

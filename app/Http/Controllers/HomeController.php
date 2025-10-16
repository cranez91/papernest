<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request) {
        $products = Product::activeItems()
            ->basicInfo()
            ->with(['category:id,name'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        return Inertia::render('Home', [
            'products' => $products
        ]);
    }

    public function cart(Request $request) {
        $shipping_price = Setting::first()?->shipping_price ?? 0;
        $today = Carbon::now('America/Mexico_City')->format('Y-m-d');

        $coupons = Coupon::where('status', 'active')
            ->where('start_date', '<=' , $today)
            ->where('end_date', '>=' , $today)
            ->select(['code', 'description', 'min_total', 'discount_percentage'])
            ->get();

        return Inertia::render('Cart', [
            'shipping' => $shipping_price,
            'coupons' => $coupons
        ]);
    }

    public function about(Request $request) {
        $settings = Setting::first();
        
        if (!is_null($settings)) {
            $settings->whatsapp_web = "https://web.whatsapp.com/send?phone=+52{$settings->whatsapp_contact}";
            $settings->whatsapp_mobile = "https://api.whatsapp.com/send?phone=+52{$settings->whatsapp_contact}";
            $settings->messenger = "https://m.me/{$settings->facebook_id}";
            $settings->schedule = $settings->business_hours;
        }

        return Inertia::render('About', [
            'settings' => $settings
        ]);
    }

    public function termsOfService(Request $request) {
        return Inertia::render('TermsOfService');
    }

    public function privacyPolicy(Request $request) {
        return Inertia::render('PrivacyPolicy');
    }
}

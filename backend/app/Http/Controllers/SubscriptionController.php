<?php

namespace App\Http\Controllers;

use App\Services\PlanCatalog;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function plans(PlanCatalog $catalog)
    {
        return response()->json([
            'currency' => config('plans.currency'),
            'plans' => $catalog->publicPlans(),
            'checkout_enabled' => (bool) config('plans.checkout_enabled'),
        ]);
    }

    public function state(Request $request, PlanCatalog $catalog)
    {
        abort_unless($request->user()->school, 403, 'A school account is required.');
        return response()->json($catalog->state($request->user()->school));
    }

    public function billing()
    {
        return redirect(rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/') . '/billing');
    }
}

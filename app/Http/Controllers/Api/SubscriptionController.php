<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans = SubscriptionPlan::active()
            ->ordered()
            ->get()
            ->groupBy('type');

        return response()->json($plans);
    }
}

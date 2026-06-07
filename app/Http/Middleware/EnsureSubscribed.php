<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'pharmacist') {
            // Access is granted strictly if the current date is before or equal to the subscription expiration
            if ($user->subscription_ends_at && now()->lessThanOrEqualTo($user->subscription_ends_at)) {
                return $next($request);
            }

            // Expiry clean-up logic to allow renewal:
            if ($user->payment_status === 'paid') {
                $user->payment_status = 'pending';
                $user->payment_receipt = null;
                $user->save();

                return redirect()->route('billing.index')->with('error', 'Masa langganan Anda telah habis. Silakan lakukan pembayaran untuk memperpanjang akses.');
            }

            return redirect()->route('billing.index');
        }

        return $next($request);
    }
}

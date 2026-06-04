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
            if ($user->payment_status === 'paid') {
                if ($user->subscription_ends_at && $user->subscription_ends_at->isPast()) {
                    $user->payment_status = 'pending';
                    $user->payment_receipt = null;
                    $user->save();

                    return redirect()->route('billing.index')->with('error', 'Masa langganan Anda telah habis. Silakan lakukan pembayaran untuk memperpanjang akses.');
                }
            } else {
                return redirect()->route('billing.index');
            }
        }

        return $next($request);
    }
}

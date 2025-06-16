<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVerifiedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $document = $request->route('document');

        if ($document && $document->status === 'pending') {
            if (!$document->admin_id || $document->admin_id !== $request->user()->id) {
                return redirect()->route('admin.documents.show', $document)
                    ->with('error', 'You must verify this document before finalizing it.');
            }
        }

        return $next($request);
    }
}

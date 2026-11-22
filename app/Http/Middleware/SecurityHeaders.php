<?php

// NIM: 252410102049 - Gandhi MM
// Middleware Security Headers untuk perlindungan terhadap XSS, Clickjacking, MIME Sniffing

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Menambahkan header keamanan pada setiap respons HTTP.
     * Melindungi dari: Clickjacking, XSS, MIME Sniffing, dan lainnya.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mencegah Clickjacking (halaman tidak bisa di-embed dalam iframe)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Mencegah MIME-type sniffing oleh browser
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mengaktifkan filter XSS bawaan browser (lapisan pertahanan tambahan)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Content Security Policy - hanya mengizinkan resource dari origin sendiri
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; form-action 'self'; frame-ancestors 'none'");

        // Mencegah kebocoran informasi referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Mencegah akses fitur browser yang tidak diperlukan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Memaksa browser selalu menggunakan HTTPS (berguna di production)
        // $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}

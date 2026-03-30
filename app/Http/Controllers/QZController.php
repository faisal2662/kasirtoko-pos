<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QZController extends Controller
{

    public function cert()
    {
        return response(
            file_get_contents(public_path('qz/digital-certificate.txt'))
        )->header('Content-Type', 'text/plain');
    }

    public function sign(Request $request)
    {
        $privateKey = openssl_pkey_get_private(
            file_get_contents(public_path('qz/private-key.pem'))
        );

        $data = $request->input('data');

        if (!$privateKey) {
            return response('Invalid private key', 500);
        }

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA512);

        return response(base64_encode($signature))
            ->header('Content-Type', 'text/plain');
    }
}

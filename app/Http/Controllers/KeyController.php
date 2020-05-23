<?php

namespace App\Http\Controllers;

use App\Key;
use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class KeyController extends Controller
{
    /**
     * Gera uma chave publica e uma chave privada
     * @return \Illuminate\Http\JsonResponse
     */
    public function new()
    {
        try {
            $config = [
                'digest_alg' => 'sha512',
                'private_key_bits' => 1024,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ];

            $ssl = openssl_pkey_new ($config);

            //Extract the private key from $ssl to $private_key
            openssl_pkey_export($ssl, $private_key, NULL, $config);


            //Extract the public key from $ssl to $public_key
            $public_key = openssl_pkey_get_details($ssl);
            $public_key = $public_key["key"];

            return response()->json([
                "public_key" => $public_key,
                "private_key" => $private_key
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                "message" =>  $e->getMessage()
            ], 500);
        }
    }

}

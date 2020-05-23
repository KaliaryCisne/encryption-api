<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * recebe uma mensagem e uma chave publica e criptografa uma mensagem utilizando o algortimo de RSA em hexadecimal.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function encrypt(Request $request)
    {
        try {
            if (!$request->has("message") || !$request->has("public_key")) {
                return "Missing required parameters";
            }

            $message = $request->input('message');
            $public_key = $request->input('public_key');

            openssl_public_encrypt($message, $encrypted, $public_key);

            return response()->json([
                "result" => bin2hex($encrypted)
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Recebe uma mensagem criptografada e em hexadecimal e descriptografa a mensagem
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function decrypt(Request $request)
    {
        try {
            if (!$request->has("message") || !$request->has("private_key")) {
                return "Missing required parameters";
            }

            $message = $request->input('message');
            $private_key = $request->input('private_key');

            //Decrypted message using the private key and store the results in $decrypted
            openssl_private_decrypt(hex2bin($message), $decrypted, openssl_pkey_get_private($private_key, NULL));

            return response()->json([
                "message" => $decrypted
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "message" => $decrypted
            ], 500);
        }
    }

}

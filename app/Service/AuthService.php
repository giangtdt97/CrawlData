<?php


namespace App\Service;


use Illuminate\Support\Carbon;

class AuthService
{
    public function successAuthResponse($tokenResult){
        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
    }

    public function failAuthResponse($message, $statusCode = 403){

        return [
                'errors' => $message,
                'status_code' => $statusCode,
            ];
    }

    public function getUIDFromFirebase($idTokenString){
        // Launch Firebase Auth
        $auth = app('firebase.auth');
        // Retrieve the Firebase credential's token
//        $idTokenString = ;

        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $auth->verifyIdToken($idTokenString);

        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return $response = [
                'error' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ];

//            return $this->failAuthResponse($response, 401);

        } catch (InvalidToken $e) { // If the token is invalid (expired ...)

            return $response = [
                'error' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ];

//            return $this->failAuthResponse($response, 401);
        }

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        return $uid = $verifiedIdToken->getClaim('sub');
    }

}

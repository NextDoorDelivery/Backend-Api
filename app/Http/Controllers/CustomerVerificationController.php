<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerVerification;
use Exception;

class CustomerVerificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['sendPhoneNumber', 'getPhoneNumber', 'codeIsSent']]);
    }

    /**
     * Writes user's phone number with random verification code into CustomerVerification table.
     */
    public function sendPhoneNumber(Request $request){
        
        // The phone number must be at least 8 digits.
        $request->validate([
            'phoneNumber' => 'required|min:8',
        ]);
        
        try {
            // Generating random 6 digit number to save as verification code for the given phone number.
            $verificationCode = random_int(100000, 999999);

            $customerVerification = new CustomerVerification();
            $customerVerification->phoneNumber = $request['phoneNumber'];
            $customerVerification->verificationCode = $verificationCode;
            $customerVerification->sender = 1;
            $customerVerification->verified = false;
            $customerVerification->save();

            return response()->json(['response' => 'success'], 200);

        } catch (exception $e) {

            $response = null;
            // Return error details only in dev environment.
            $this->getEnvValues()['APP_ENV'] == 'dev'
            ? $response = response()->json(['response' => 'error', 'error'=>$e], 500)
            : $response = response()->json(['response' => 'internal error'], 500);

            return $response;
        }
    }

    public function getPhoneNumber(){

    

        try {
            // Generating random 6 digit number to save as verification code for the given phone number.

            $sms = CustomerVerification::where('sent', 0)->first();

            return response()->json(['response' => $sms], 200);

        } catch (exception $e) {

            $response = null;
            // Return error details only in dev environment.
            $this->getEnvValues()['APP_ENV'] == 'dev'
            ? $response = response()->json(['response' => 'error', 'error'=>$e], 500)
            : $response = response()->json(['response' => 'internal error'], 500);

            return $response;
        }
    }

    public function codeIsSent(Request $request){

        // The phone number must be at least 8 digits.
        $request->validate([
            'phoneNumber' => 'required|min:8',
        ]);

        try {
            // Generating random 6 digit number to save as verification code for the given phone number.
            
            CustomerVerification::where('phoneNumber', $request['phoneNumber'])->update(['sent' => 1]);

            return response()->json(['response' => 'success'], 200);

        } catch (exception $e) {

            $response = null;
            // Return error details only in dev environment.
            $this->getEnvValues()['APP_ENV'] == 'dev'
            ? $response = response()->json(['response' => 'error', 'error'=>$e], 500)
            : $response = response()->json(['response' => 'internal error'], 500);

            return $response;
        }
    }

}

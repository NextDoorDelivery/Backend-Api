<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerVerification;
use App\Models\LkVerificationStatus;
use Exception;

class CustomerVerificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['sendPhoneNumber']]);
    }

    /**
     * Writes user's phone number with random verification code into CustomerVerification table.
     */
    public function sendPhoneNumber(Request $request){

        $request->validate([
            'phoneNumber' => 'required|min:8',
        ]);

        try {
            // Generating random 6 digit number to save as verification code for the given phone number.
            $verificationCode = random_int(100000, 999999);

            // Getting the 'unverified' status id from the look up table.
            $unverifiedStatus = LkVerificationStatus::where('description', 'unverified')
                                ->first()['LkVerificationStatusId'];

            $customerVerification = new CustomerVerification();
            $customerVerification->phoneNumber = $request['phoneNumber'];
            $customerVerification->verificationCode = $verificationCode;
            $customerVerification->LkVerificationStatusId = $unverifiedStatus;
            $customerVerification->RegistrarDevice = NULL;
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
}

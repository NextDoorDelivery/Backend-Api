<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerVerification;
use App\Models\LkVerificationStatus;
use App\Models\LkMobileDeviceToken;
use Illuminate\Support\Str;

use Exception;

class CustomerVerificationController extends Controller
{

     /**
     * Writes user's phone number with random verification code into CustomerVerification table.
     */
    public function sendPhoneNumber(Request $request){

        $request->validate([
            'phoneNumber' => 'required|min:8',
        ]);

        echo($request['phoneNumber']);

        try {
            // Generating random 6 digit number to save as verification code for the given phone number.
            $verificationCode = random_int(100000, 999999);

            // Getting the 'unverified' status id from the look up table.
            $unverifiedStatus = LkVerificationStatus::where('description', 'unverified')
                                ->first()['LkVerificationStatusId'];

            /**
             * Deletes any existing unverified records with the same phone number.
             * There must be only 1 unverified record per phone number at a time.
            */
            CustomerVerification::where('PhoneNumber', $request['phoneNumber'])
                                ->where('LkVerificationStatusId', $unverifiedStatus)->delete();

            $customerVerification = new CustomerVerification();
            $customerVerification->phoneNumber = $request['phoneNumber'];
            $customerVerification->verificationCode = $verificationCode;
            $customerVerification->LkVerificationStatusId = $unverifiedStatus;
            $customerVerification->RegistrarDevice = NULL;
            $customerVerification->save();

            return response()->json(['response' => $customerVerification['phoneNumber']], 200);

        } catch (exception $e) {

            $response = null;
            // Return error details only in dev environment.
            $this->getEnvValues()['APP_ENV'] == 'dev'
            ? $response = response()->json(['response' => 'error', 'error'=>$e], 500)
            : $response = response()->json(['response' => 'internal error'], 500);

            return $response;
        }
    }

    /**
     * Checks the verify code against the phone number, it matches, and marks it as 'customer-verified' in DB.
     */
    public function verifyCode(Request $request){

        $request->validate([
            'phoneNumber' => 'required|min:8',
            'verification_code' => 'required|min:6|max:6',
            'device_uuid' => 'required'
        ]);

        try {
            // Getting the status id from the look up table.
            $customerVerifiedStatus = LkVerificationStatus::where('description', 'customer-verified')
                                      ->first()['LkVerificationStatusId'];

            $successStatus = LkVerificationStatus::where('description', 'success')
                             ->first()['LkVerificationStatusId'];


            $result = CustomerVerification::where('PhoneNumber', $request['phoneNumber'])
                      ->where('LkVerificationStatusId', $successStatus)->first();


            if($result == null){
                return response()->json(['response' => 'no record'], 500);
            }else if($result['LkVerificationStatusId'] == $customerVerifiedStatus){
                return response()->json(['response' => 'already verified'], 500);
            }

            if($request['verification_code'] == $result['VerificationCode']){

                // Delete any existing record in LkMobileDeviceToken for this phone number.
                LkMobileDeviceToken::where('PhoneNumber', $request['phoneNumber'])->delete();

                $result->DeviceUuid = $request['device_uuid'];
                $result->LkVerificationStatusId = $customerVerifiedStatus;
                $result->update();

                $lkDeviceTokenModel = new LkMobileDeviceToken();
                $lkDeviceTokenModel->PhoneNumber = $request['phoneNumber'];
                $lkDeviceTokenModel->DeviceUuid = $request['device_uuid'];
                $auth_token = bcrypt(Str::random(10));
                $lkDeviceTokenModel->MobileAuthToken = $auth_token;
                $lkDeviceTokenModel->save();

                $response = [
                    'response' => 'success',
                    'auth_token' => $auth_token
                ];

                return response()->json([$response], 200);

            }else{
                return response()->json(['response' => 'unable to verify'], 401);
            }

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LkMobileDeviceToken;
use App\Models\CustomerProfile;
use App\Helper\CustomerUtils;

class CustomerProfileController extends Controller
{

     /**
     * Creates new customer profile.
     */
    public function createCustomer(Request $request){

        $request->validate([
            'firstName' => 'required|string|min:1|max:100',
            'lastName' => 'required|string|min:1|max:100',
            'email' => 'required|email'
        ]);

        try {

            $lkMobileDeviceToken = LkMobileDeviceToken::where('MobileAuthToken', $request['mobile_token'])->first();

            $newCustomer = new CustomerProfile();

            $newCustomer->FirstName = $request['firstName'];
            $newCustomer->LastName = $request['lastName'];
            $newCustomer->PhoneNumber = $lkMobileDeviceToken['PhoneNumber'];
            $newCustomer->Email = $request['email'];
            $newCustomer->save();

            $response = [
                'response' => 'success',
                'customer_id' => $newCustomer->id,
            ];

            return response()->json([$response], 200);

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

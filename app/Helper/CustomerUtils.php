<?php

namespace App\Helper;

use App\Models\CustomerProfile;
use App\Models\LkMobileDeviceToken;

class CustomerUtils{

    public static function getCustomer($mobile_token){

        $lkMobileDeviceToken = LkMobileDeviceToken::where('MobileAuthToken', $mobile_token)->first();
        $customerProfile = CustomerProfile::where('PhoneNumber', $lkMobileDeviceToken['PhoneNumber'])->first();

        if($customerProfile != null){
            $customer = [
                'firstName' => $customerProfile['FirstName'],
                'lastName' => $customerProfile['LastName'],
                'email' => $customerProfile['Email'],
                'phoneNumber' => $customerProfile['PhoneNumber'],
            ];

            return $customer;
        }
        else{
            return 'Customer Utils - no customer profile found.';
        }
    }

}

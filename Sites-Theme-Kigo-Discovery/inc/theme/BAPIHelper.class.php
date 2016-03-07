<?php

namespace Discovery;

class BAPIHelper {
    private $bapiData;

    public function __construct(){
        //Initialize BAPI Solution data
        $this->bapiData = json_decode(get_option('bapi_solutiondata'));
    }

    public function getData(){
        return $this->bapiData;
    }

    public function getAddress(){
        /* Try and fetch main address, fallback to secondary address, aggregate address or coordinates */
        $ad1 = $this->bapiData->Office->Address1;
        $ad2 = $this->bapiData->Office->Address2;

        $coord_lat = $this->bapiData->Office->Latitude;
        $coord_lon = $this->bapiData->Office->Longitude;

        $city = $this->bapiData->Office->City;
        $state = $this->bapiData->Office->State;
        $region = $this->bapiData->Office->Region;
        $country = $this->bapiData->Office->Country;

        $address = $ad1 ? : $ad2;
        $address = $address ? : $city .' '. $state .' '. $region .' '. $country;
        $address = $address ? : $coord_lat.','.$coord_lon;

        return trim($address);
    }
    
    public function getTelephone() {
        $telephone = $this->bapiData->PrimaryPhone ? : $this->bapiData->Office->PrimaryPhone;
        return $telephone;
    }
    
    public function getSiteLogo(){
        return $this->bapiData->SolutionLogo;
    }

    public function getName(){
        return $this->bapiData->SolutionName;
    }
    public function getTextDataArray(){
        $textDataArray = [];
        if ( function_exists( 'getTextDataArray') )  {
            /* we get the array of textdata */
            $textDataArray = getTextDataArray();
        }
        return $textDataArray;
    }
}
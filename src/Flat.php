<?php

namespace Seasoul\Phpcave\Projectcity;

        class Flat {

            //cвойства
            protected $PAY_RENT = 2.15;             // m2/UAH +
            private $PAY_HEATING_WINTER = 6.45;     // m2/UAH +
            private $PAY_HEATING_SUMMER = 1.15;     // m2/UAH +
            private $PAY_ELECTRIC_LIGHT_1 = 0.28;   // kW/UAH +
            private $PAY_ELECTRIC_LIGHT_2 = 0.32;   // kW/UAH +
            private $PAY_ELECTRIC_LIGHT_3 = 0.98;   // kW/UAH +
            private $PAY_HOT_WATER = 41.35;         // m3/UAH +
            private $PAY_COLD_WATER = 1.3;          // m3/UAH +
            private $AMOUNT_OF_WATER = 9.8;         // m3/UAH +
            private $PAY_NATURAL_GAS = 7.85;        // m3/UAH +
            public $firstNameOwner;                 // information
            public $secondNameOwner;                // information
            public $numberOfRoom;                   // information
            public $livingSpace;                    // ++ information
            public $balconySpace;                   // ++ information
            public $floor;                          // information
            public $numberOfPerson;                 // +++ information
            public $numberOfBalcony;                // information
            public $availabilityBalcony;            // Yes or Not information
            public $typeOfHeating;                  // Electric or Water + information
            public $expenseElectic;                 // kW/h + information
            public $codeFlat;                       // Datebase of owner flat in the apartment
            public $codeApartmentFlat;              // Datebase of owner flat in the apartment
            public $nameStreet;                     // Datebase of owner flat in the apartment
            public $nameCity;                       // Datebase of owner flat in the apartment

            //функция конструктор
            function Flat() {
                $this->nameCity = filter_input (INPUT_POST, 'nameCity');
                $this->nameStreet = filter_input (INPUT_POST, 'nameStreet');
                $this->codeApartmentFlat = (int)filter_input (INPUT_POST, 'codeApartmentFlat');
                $this->codeFlat = (int)filter_input (INPUT_POST, 'codeFlat');
                $this->firstNameOwner = filter_input (INPUT_POST, 'firstName');
                $this->secondNameOwner = filter_input (INPUT_POST, 'secondName');
                $this->numberOfRoom = (int)filter_input (INPUT_POST, 'numberRoom');
                $this->livingSpace = round((float)filter_input (INPUT_POST, 'livingSpace'),3);
                $this->balconySpace = round((float)filter_input (INPUT_POST, 'balconySpace'),3);
                $this->floor = (int)filter_input (INPUT_POST, 'floor');
                $this->numberOfPerson = (int)filter_input (INPUT_POST, 'numberPerson');
                $this->numberOfBalcony = (int)filter_input (INPUT_POST, 'numberBalcony');
                $this->availabilityBalcony = (string)filter_input (INPUT_POST, 'availabilityBalcony');
                $this->typeOfHeating = (string)filter_input (INPUT_POST, 'typeOfHeating');
                $this->expenseElectic = (int)filter_input (INPUT_POST, 'expenseElectic');
            }
//------------------------------------------CREATE INFORMATION ABOUT FLAT---------------------------------------------
            //методы
            function createFlatDatabase() {
                $createFlat = array( "codeFlat" => $this->codeFlat,
                                     "firstNameOwner" => $this->firstNameOwner,
                                     "secondNameOwner" => $this->secondNameOwner,
                                     "numberOfRoom" => $this->numberOfRoom,
                                     "livingSpace" => $this->livingSpace,
                                     "floor" => $this->floor,
                                     "numberOfBalcony" => $this->numberOfBalcony,
                                     "availabilityBalcony" => $this->availabilityBalcony,
                                     "balconySpace" => $this->balconySpace,
                                     "numberOfPerson" => $this->numberOfPerson,
                                     "typeOfHeating" => $this->typeOfHeating,
                                     "expenseElectic" => $this->expenseElectic);
                if (!isset($_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartmentFlat][$this->codeFlat])){
                    $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartmentFlat][$this->codeFlat] = $createFlat;
                            }
                    else {
                        foreach ( $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartmentFlat][$this->codeFlat] as $key => $value){
                            foreach ( $createFlat as $key2 => $value2){
                                if ($key==$key2){
                                    $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartmentFlat][$this->codeFlat][$key]=$value2;
                                }
                            }
                        }
                    }
            }
//------------------------------------------PAY RENT FLAT---------------------------------------------
            function payRent() {
                $payRent = $this->PAY_RENT * ($this->livingSpace + $this->balconySpace);
                $_SESSION['payRent'] = $payRent;
                $payRent = round($payRent, 3);
                return $payRent;
            }
//------------------------------------------PAY SERVICE HEATING---------------------------------------------
            function payHeating() {
                $today = date("m.d.y");
                $todayTimeArray = explode(".", $today);
                if ($this->typeOfHeating != 'Electric') {
                    if ((($todayTimeArray[0] < 4) || (($todayTimeArray[0] == 4) && ($todayTimeArray[1] < 16)))
                       or ( (($todayTimeArray[0] == 10) && ($todayTimeArray[1] > 15)) || ($todayTimeArray[0] > 10))) {
                        $payHeating = $this->PAY_HEATING_WINTER * ($this->livingSpace + $this->balconySpace);
                        $_SESSION['payHeating'] = $payHeating;
                        return $payHeating;
                    } else {
                        $payHeating = $this->PAY_HEATING_SUMMER * ($this->livingSpace + $this->balconySpace);
                        $_SESSION['payHeating'] = $payHeating;
                        return $payHeating;
                    }
                }
            }
//------------------------------------------PAY SERVICE ELECTRIC---------------------------------------------
            function payElectric() {
                if ($this->expenseElectic <= 150) {
                    $payElectric = $this->PAY_ELECTRIC_LIGHT_1 * $this->expenseElectic;
                    $_SESSION['payElectric'] = $payElectric;
                    return $payElectric;
                } else if ($this->expenseElectic <= 800) {
                    $payElectric = $this->PAY_ELECTRIC_LIGHT_2 * $this->expenseElectic;
                    $_SESSION['payElectric'] = $payElectric;
                    return $payElectric;
                } else {
                    $payElectric = $this->PAY_ELECTRIC_LIGHT_3 * $this->expenseElectic;
                    $_SESSION['payElectric'] = $payElectric;
                    return $payElectric;
                }
            }
//------------------------------------------PAY SERVICE HOT WATER---------------------------------------------
            function payHotWater() {
                $payHotWater = $this->PAY_HOT_WATER * $this->numberOfPerson;
                $_SESSION['payHotWater'] = $payHotWater;
                return $payHotWater;
            }
//------------------------------------------PAY SERVICE COLD WATER---------------------------------------------
            function payColdWater() {
                $payColdWater = $this->PAY_COLD_WATER * $this->numberOfPerson * $this->AMOUNT_OF_WATER;
                $_SESSION['payColdWater'] = $payColdWater;
                return $payColdWater;
            }
//------------------------------------------PAY SERVICE NATURAL GAS---------------------------------------------
            function payNaturalGas() {
                $payNaturalGas = $this->PAY_NATURAL_GAS * $this->numberOfPerson;
                $_SESSION['payNaturalGas'] = $payNaturalGas;
                return $payNaturalGas;
            }
//------------------------------------------ANSWER TO USER INFORMATION ABOUT FLAT--------------------------------------
            function informationAboutFlat() {
                $informationAboutFlat = array("First name owner flat " => '<b>' . $this->firstNameOwner . '</b>',
                    "Second name owner flat " => '<b>' . $this->secondNameOwner . '</b>',
                    "Numbers of room in flat " => '<b>' . $this->numberOfRoom . '</b>',
                    "Area flat " => '<b>' . $this->livingSpace . '</b> m2',
                    "Floor in apartment house " => '<b>' . $this->floor . '</b>',
                    "Number balcony " => '<b>' . $this->numberOfBalcony . '</b>',
                    "Availability balcony " => '<b>' . $this->availabilityBalcony . '</b>',
                    "Area balcony " => '<b>' . $this->balconySpace . '</b> m2',
                    "Number person in flat " => '<b>' . $this->numberOfPerson . '</b>',
                    "Type heating in flat " => '<b>' . $this->typeOfHeating . '</b>',
                    "Expense electic for the last month " => '<b>' . $this->expenseElectic . '</b> kW/h');
                echo '<b><i><h4>INFORMATION ABOUT FLAT</h4></i></b><br>   ';
                $step = 0;
                foreach ($informationAboutFlat as $key => $value) {
                    echo '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $_SESSION['$informationAboutFlat'][$step] = '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $step++;
                }
            }
//------------------------------------------FUNCTION SUM and RENT ALL SERVICES ONE FLAT--------------------------------------
            function FlatPayment() {
                $FlatPayment = array("Owner flat must pay for rent flat" => $this->payRent(),
                    "Owner flat must pay for heating flat" => $this->payHeating(),
                    "Owner flat must pay for electric flat" => $this->payElectric(),
                    "Owner flat must pay for hot water flat" => $this->payHotWater(),
                    "Owner flat must pay for cold water flat" => $this->payColdWater(),
                    "Owner flat must pay for natural gas flat" => $this->payNaturalGas());
                echo '<hr><b><i><h4>INFORMATION ABOUT RENT FLAT</h4></i></b>';
                $sumPay = 0;
                foreach ($FlatPayment as $key => $value) {
                    echo '=>>   ' . $key . '     <b>' . $value . '</b> UAH!<hr>';
                    $sumPay += $value;
                }
                 echo '=>>   The total amount for all utilities payments = <b>' . $sumPay . '</b> UAH!<hr>';
                 echo '=>>   The current date ' . $today = date("d.m.y") . ' !<hr>';
            }
//------------------------------------------FUNCTION SUM RENT FROM ALL FLATS IN APARTMENT-----------------------------------
            function SumFlatPayment($numberApartment, $nameStreet, $nameCity) {
                $sumFlatPaymentApartment = array("All payment for rent flats in Apartment" => 0,
                                     "All payment for heating flat in Apartment" => 0,
                                     "All payment for electric flat in Apartment" => 0,
                                     "All payment for hot water flat in Apartment" => 0,
                                     "All payment for cold water flat in Apartment" => 0,
                                     "All payment for for natural gas flat in Apartment" => 0);
                foreach ($_SESSION['codeCity'][$nameCity][$nameStreet][$numberApartment] as $key => $value) {
                    if (is_numeric($key)){
                        foreach ($value as $key2 => $value2) {
                            if ( $key2 == "livingSpace" ) {
                                    $this->livingSpace = $value2;
                                }
                            elseif ( $key2 == "balconySpace" ) {
                                    $this->balconySpace = $value2;
                                }
                            elseif ( $key2 == "numberOfPerson" ) {
                                    $this->numberOfPerson = $value2;
                                }
                            elseif ( $key2 == "typeOfHeating" ) {
                                    $this->typeOfHeating = $value2;
                                }
                            elseif ( $key2 == "expenseElectic" ) {
                                    $this->expenseElectic = $value2;
                                }
                            }
                        $tempRent = $this->payRent(); $tempHeating = $this->payHeating(); $tempElectric = $this->payElectric();
                        $tempHotWater = $this->payHotWater(); $tempColdWater = $this->payColdWater(); $tempNaturalGas = $this->payNaturalGas();
                        $sumFlatPaymentApartment["All payment for rent flats in Apartment"] += $tempRent;
                        $sumFlatPaymentApartment["All payment for heating flat in Apartment"] += $tempHeating;
                        $sumFlatPaymentApartment["All payment for electric flat in Apartment"] += $tempElectric;
                        $sumFlatPaymentApartment["All payment for hot water flat in Apartment"] += $tempHotWater;
                        $sumFlatPaymentApartment["All payment for cold water flat in Apartment"] += $tempColdWater;
                        $sumFlatPaymentApartment["All payment for for natural gas flat in Apartment"] += $tempNaturalGas;
                    }
                }
                echo '<hr><b><i><h4>INFORMATION ABOUT RENT ALL FLAT APARTMENT</h4></i></b>';
                $sumPay3 = 0;
                foreach ($sumFlatPaymentApartment as $key3 => $value3) {
                    echo '=>>   ' . $key3 . '     <b>' . $value3 . '</b> UAH!<hr>';
                    $sumPay3 += $value3;
                }
                 echo '=>>   The total amount for all utilities payments = <b>' . $sumPay3 . '</b> UAH!<hr>';
                 echo '=>>   The current date ' . $today = date("d.m.y") . ' !<hr>';
            }
//------------------------------------------FUNCTION SUM RENT FROM ALL APARTMENTS-----------------------------------
            function SumApartmentPayment($sumElectic) {
                $sumApartmentPaymentStreet = 0;
                foreach ($_SESSION['codeCity'][$this->nameCity][$this->nameStreet] as $key => $value){
                    if (is_numeric($key)){
                    foreach ($value as $key2 =>$value2) {
                        if (is_numeric($key2)){
                        foreach ($value2 as $key3 => $value3) {
                            if ( $key3 == "livingSpace" ) {
                                    $this->livingSpace = $value3;
                                }
                            elseif ( $key3 == "balconySpace" ) {
                                    $this->balconySpace = $value3;
                                }
                            elseif ( $key3 == "numberOfPerson" ) {
                                    $this->numberOfPerson = $value3;
                                }
                            elseif ( $key3 == "typeOfHeating" ) {
                                    $this->typeOfHeating = $value3;
                                }
                            elseif ( $key3 == "expenseElectic" ) {
                                    $this->expenseElectic = $value3;
                                }
                            }
                            $sumApartmentPaymentStreet += $this->payRent();
                            $sumApartmentPaymentStreet += $this->payHeating();
                            $sumApartmentPaymentStreet += $this->payElectric();
                            $sumApartmentPaymentStreet += $this->payHotWater();
                            $sumApartmentPaymentStreet += $this->payColdWater();
                            $sumApartmentPaymentStreet += $this->payNaturalGas();
                                    }
                                }
                    }
                }
                 echo '=>>   The total amount for all utilities payments = <b>' . ($sumApartmentPaymentStreet+$sumElectic) . '</b> UAH!
                         <br>this sum with all payments appartment and payment for lighting street<hr>';
                 echo '=>>   The current date ' . $today = date("d.m.y") . ' !<hr>';
            }
//------------------------------------------FUNCTION SUM RENT FROM ALL APARTMENTS-----------------------------------
        function SumNumberOfPeorleCity() {
                $SumNumberOfPeorleCity = 0;
                foreach ($_SESSION['codeCity'][$this->nameCity] as $key => $value){
                    if (is_array($value)){
                    foreach ($value as $key2 =>$value2){
                            if (is_array($value2)){
                        foreach ($value2 as $key3 =>$value3) {
                            if (is_numeric($key3)){
                            foreach ($value3 as $key4 => $value4) {
                                if ( $key4 == "numberOfPerson" ) {
                                        $SumNumberOfPeorleCity += $value4;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                 echo '=>>   The live in city = <b>' . ($SumNumberOfPeorleCity) . '</b> of people<hr>';
            }
        }
//------------------------------------------FIRST STAGE CREATING WORK PROGRAM AND AJAX-----------------------------------
        // Работа с классом квартиры
        /*  $object = new FlatPayment();  // вызов конструктора
        $object->informationAboutFlat();
        $object->myFunctionFlatPayment();
        $object->createUserDatabase();  */
        //print_r($_SESSION['$informationAboutFlat']);
        //echo '{"payRent" : "'.$_SESSION['payRent'].'", "payHeating" : "'.$_SESSION['payHeating'].'",
        //       "payElectric" : "'.$_SESSION['payElectric'].'", "payHotWater" : "'.$_SESSION['payHotWater'].'",
        //       "payColdWater" : "'.$_SESSION['payColdWater'].'", "payNaturalGas" : "'.$_SESSION['payNaturalGas'].'"}';
        //$arr = array('payRent' => $_SESSION['payRent'], 'payHeating' => $_SESSION['payHeating'],
        //           'payElectric' => $_SESSION['payElectric'], 'payHotWater' => $_SESSION['payHotWater'],
        //           'payColdWater' => $_SESSION['payColdWater'], 'payNaturalGas' => $_SESSION['payNaturalGas'],
        //           'informationAboutFlat' => $_SESSION['informationAboutFlat']);
        //echo json_encode($arr);
//------------------------------------------DESTROY SESSION---------------------------------------------------------------
        /* unset($_SESSION['$stepCreateUser']);
         unset($_SESSION['codeFlat']);
         unset($_SESSION['payRent']);
         unset($_SESSION['payHeating']);
         unset($_SESSION['payElectric']);
         unset($_SESSION['payHotWater']);
         unset($_SESSION['payColdWater']);
         unset($_SESSION['payNaturalGas']);
         unset($_SESSION['$informationAboutFlat']);
         unset($_SESSION['$coseFlat']);
         unset($_SESSION['']);
         session_unset(); */
?>
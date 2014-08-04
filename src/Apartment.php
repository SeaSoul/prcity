<?php

namespace Seasoul\Phpcave\Projectcity;

        class Apartment {

            //cвойства
            protected $PAY_RENT = 5.25;
            protected $PAY_ELECTRIC_LIGHT_1 = 0.28;     // kW/UAH +
            protected $PAY_ELECTRIC_LIGHT_2 = 0.32;     // kW/UAH +
            protected $PAY_ELECTRIC_LIGHT_3 = 0.98;     // kW/UAH +
            public $expenseElectic;                     // + information kW/h*month
            public $codeApartment;                      // Datebase of Apartmentt in the street
            public $numberOfFloor;                      // information
            public $numberOfPorch;                      // information
            public $areaAroundApartment;                // + information
            public $nameStreet;                         // + information
            public $nameCity;                           // + information
            public $payElectricApartment;

            //функция конструктор
            function Apartment() {
                $this->nameCity = filter_input (INPUT_POST, 'nameCity');
                $this->nameStreet = filter_input (INPUT_POST, 'nameStreet');
                $this->expenseElectic = (int)filter_input (INPUT_POST, 'expenseElectic');                       // $_POST['expenseElectic'];
                $this->codeApartment = (int)filter_input (INPUT_POST, 'codeApartment');                         // $_POST['codeApartment'];
                $this->numberOfFloor = (int)filter_input (INPUT_POST, 'numberOfFloor');                         // $_POST['numberOfFloor'];
                $this->numberOfPorch = (int)filter_input (INPUT_POST, 'numberOfPorch');                         // $_POST['numberOfPorch'];
                $this->areaAroundApartment = round((float)filter_input (INPUT_POST, 'areaAroundApartment'), 3); // $_POST['areaAroundApartment'];
            }
//------------------------------------------CREATE INFORMATION ABOUT APARTMENT---------------------------------------------
            //методы
            function createApartmentDatabase() {
                $createApartment = array( "Number apartment" => $this->codeApartment,
                                          "Numbers of floors apartment" => $this->numberOfFloor,
                                          "Numbers of porchs apartment" => $this->numberOfPorch,
                                          "Area around apartment" => $this->areaAroundApartment,
                                          "Expense electic for the last month" => $this->expenseElectic);
                    if (!isset($_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartment])){
                        $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartment] = $createApartment;
                            }
                    else {
                        foreach ( $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartment] as $key => $value){
                            foreach ( $createApartment as $key2 => $value2){
                                if ($key==$key2){
                                    $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartment][$key]=$value2;
                                }
                                else {
                                    $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$this->codeApartment][$key2]=$value2;
                                    }
                            }
                        }
                    }
            }
//------------------------------------------RENT APARTMENT---------------------------------------------
            function payRentApartment() {
                $payRent = $this->PAY_RENT * $this->areaAroundApartment;
                $payRent = round($payRent, 3);
                return $payRent;
            }
//------------------------------------------PAY SERVICE ELECTRIC---------------------------------------------
            function payElectricApartment() {
                if ($this->expenseElectic <= 150) {
                    $payElectricApartment = $this->PAY_ELECTRIC_LIGHT_1 * $this->expenseElectic;
                    return $payElectricApartment;
                } else if ($this->expenseElectic <= 800) {
                    $payElectricApartment = $this->PAY_ELECTRIC_LIGHT_2 * $this->expenseElectic;
                    return $payElectricApartment;
                } else {
                    $payElectricApartment = $this->PAY_ELECTRIC_LIGHT_3 * $this->expenseElectic;
                    return $payElectricApartment;
                }
            }
//------------------------------------------CONSUMPTION SERVICE FLOOR---------------------------------------------
            function payElectricPorch() {
                $payElectricPorch = $this->expenseElectic / ($this->numberOfFloor * $this->numberOfPorch);
                return $payElectricPorch;
            }
//------------------------------------------ANSWER TO USER INFORMATION ABOUT APARTMENT-----------------------------
            function informationAboutApartment() {
                $informationAboutApartment = array("Number of apartment " => '<b>' . $this->codeApartment . '</b>',
                    "Number of Floors in apartment " => '<b>' . $this->numberOfFloor . '</b>',
                    "Number of Porch in apartment " => '<b>' . $this->numberOfPorch . '</b>',
                    "Area apartment " => '<b>' . $this->areaAroundApartment . '</b> m2',
                    "Electric consumption for the last month " => '<b>' . $this->expenseElectic . '</b>');
                echo '<b><i><h4>INFORMATION ABOUT APARTMENT</h4></i></b><br>   ';
                $step = 0;
                foreach ($informationAboutApartment as $key => $value) {
                    echo '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $_SESSION['$informationAboutApartment'][$step] = '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $step++;
                }
            }
//------------------------------------------FUNCTION SUM and RENT ALL SERVICES ONE APARTMENT-----------------------------
            function ApartmentPayArea() {
                $ApartmentPayArea = array("Aparment owner must pay for rent" => $this->payRentApartment(),
                    "Aparment owner must pay for all electric" => $this->payElectricApartment(),
                    "Aparment floor consumes electric in month" => $this->payElectricPorch());
                echo '<hr><b><i><h4>INFORMATION ABOUT RENT APARTMENT</h4></i></b>';
                foreach ($ApartmentPayArea as $key => $value) {
                    if ($key=="Aparment floor consumes electric in month"){
                        echo '=>>   ' . $key . '     <b>' . $value . '</b></b> kW/month!<hr>';
                    }
                    else {
                        echo '=>>   ' . $key . '     <b>' . $value . '</b></b> UAH!<hr>';
                    }
                    }
                echo '=>>   The current date ' . $today = date("d.m.y") . ' !<hr>';
            }
//------------------------------------------FUNCTION FIND ALL AREA IN STREET-----------------------------------
            function sumAreaApartment() {
                $sumAreaApartment =  0;
                foreach ($_SESSION['codeCity'][$this->nameCity][$this->nameStreet] as $key => $value) {
                    if (is_numeric($key)){
                        foreach ($value as $key2 => $value2) {
                            if ( $key2 == "Area around apartment" ) {
                                    $sumAreaApartment += $value2;
                                }
                            }
                        }
                    }
                return $sumAreaApartment;
            }
        }
?>
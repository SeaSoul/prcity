<?php

namespace Seasoul\Phpcave\Projectcity;

        class City {

            //cвойства
            protected $RENT_CITY = 20;
            public $nameCity;                                             // Datebase of Street in the City
            public $yearFoundation;                                       // information kW/h*month
            public $geoCoordinate;                                        // information kW/h*month
            public $country;
            //функция конструктор
            function City() {
                $this->nameCity = filter_input (INPUT_POST, 'nameCity');
                $this->yearFoundation = filter_input (INPUT_POST, 'yearFoundation');
                $this->geoCoordinate = filter_input (INPUT_POST, 'geoCoordinate');
                $this->country = filter_input (INPUT_POST, 'country');
            }
//------------------------------------------CREATE INFORMATION ABOUT APARTMENT---------------------------------------------
            //методы
            function createCityDatabase() {
                $createCity = array( "Name City the" => $this->nameCity,
                                     "Year of foundation" => $this->yearFoundation,
                                     "Geografic coordinate" => $this->geoCoordinate,
                                     "Country" => $this->country);
                    if (!isset($_SESSION['codeCity'][$this->nameCity])){
                        $_SESSION['codeCity'][$this->nameCity] = $createCity;
                            }
                    else {
                        foreach ($_SESSION['codeCity'][$this->nameCity] as $key=>$value){
                            foreach ($createCity as $key2=>$value2){
                                if ($key==$key2){
                                    $_SESSION['codeCity'][$this->nameCity][$key]=$value2;
                                }
                                else {
                                    $_SESSION['codeCity'][$this->nameCity][$key2]=$value2;
                                    }
                            }
                        }
                    }
            }
//------------------------------------------ANSWER TO USER INFORMATION ABOUT CITY-----------------------------
            function informationAboutCity() {
                $informationAboutStreet = array( "Name City the" => $this->nameCity,
                                                 "Year of foundation" => $this->yearFoundation,
                                                 "Geografic coordinate" => $this->geoCoordinate,
                                                 "Country" => $this->country);
                echo '<b><i><h4>INFORMATION ABOUT CITY</h4></i></b><br>   ';
                $step = 0;
                foreach ($informationAboutStreet as $key => $value) {
                    echo '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $_SESSION['$informationAboutApartment'][$step] = '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $step++;
                }
            }
//------------------------------------------ANSWER TO USER INFORMATION ABOUT CITY-----------------------------
            function budgetCity($result) {
                $budgetCity = $result * $this->RENT_CITY;
                    echo '=>>   City budget <b>' . $budgetCity . '</b>UAH!<br>';
                    echo '=>>   The current date ' . $today = date("d.m.y") . ' !<hr>';
            }
        }
?>

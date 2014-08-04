<?php

namespace Seasoul\Phpcave\Projectcity;

        class Street {

            //cвойства
            private $PAY_ELECTRIC_LIGHT_1 = 0.28;                                 // kW/UAH +
            private $PAY_ELECTRIC_LIGHT_2 = 0.32;                                 // kW/UAH +
            private $PAY_ELECTRIC_LIGHT_3 = 0.98;                                 // kW/UAH +
            public $nameCity;
            public $nameStreet;                                                   // Datebase of Street in the City
            public $coordinateStartStreet;                                        // + information kW/h*month
            public $coordinateEndStreet;                                          // information
            public $lengthStreet;
            public $lightingColumn;                                               // +
            public $expenseElecticMonth;                                          // +
            public $areaAroundStreet;                                             // +
            public $sumElectic;                                                   // +
            public $cleaner;                                                      // +

            //функция конструктор
            function Street() {
                $this->nameCity = filter_input (INPUT_POST, 'nameCity');
                $this->nameStreet = filter_input (INPUT_POST, 'nameStreet');
                $this->coordinateStartStreet = filter_input (INPUT_POST, 'coordinateStartStreet');
                $this->coordinateEndStreet = filter_input (INPUT_POST, 'coordinateEndStreet');
                $this->lengthStreet = round((float)filter_input (INPUT_POST, 'lengthStreet'),3);
            }
//------------------------------------------CREATE INFORMATION ABOUT APARTMENT---------------------------------------------
            //методы
            function createStreetDatabase() {
                $createStreet = array( "Name street" => $this->nameStreet,
                                       "Start street" => $this->coordinateStartStreet,
                                       "End street" => $this->coordinateEndStreet,
                                       "Length street" => $this->lengthStreet,
                                       "Street need lighting colums" => $this->lightingColumn,
                                       "Month expense electic" => $this->expenseElecticMonth,
                                       "Area around street" => $this->areaAroundStreet,
                                       "Street needs cleaner" => $this->cleaner);
                    if (!isset($_SESSION['codeCity'][$this->nameCity][$this->nameStreet])){
                        $_SESSION['codeCity'][$this->nameCity][$this->nameStreet] = $createStreet;
                            }
                    else {
                        foreach ($_SESSION['codeCity'][$this->nameCity][$this->nameStreet] as $key=>$value){
                            foreach ($createStreet as $key2=>$value2){
                                if ($key==$key2){
                                    $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$key]=$value2;
                                }
                                else {
                                    $_SESSION['codeCity'][$this->nameCity][$this->nameStreet][$key2]=$value2;
                                    }
                            }
                        }
                    }
            }
//------------------------------------------FIND COLUMS ON THE STREET---------------------------------------------
            function findColumsStreet() {
                $this->lightingColumn = floor(($this->lengthStreet / 30)*2);
                if ($this->lightingColumn%2!=0){
                    $this->lightingColumn += 1;
                }
            }
//------------------------------------------PAY ELECTRIC LIGHTING STREET---------------------------------------------
            function payElectricLightStreet() {
                $this->findColumsStreet();
                $this->expenseElecticMonth = $this->lightingColumn*0.25*24*30;
                if ($this->expenseElecticMonth <= 150) {
                    $this->sumElectic = $this->PAY_ELECTRIC_LIGHT_1 * $this->expenseElecticMonth;
                } else if ($this->expenseElecticMonth <= 800) {
                    $this->sumElectic = $this->PAY_ELECTRIC_LIGHT_2 * $this->expenseElecticMonth;
                } else {
                    $this->sumElectic = $this->PAY_ELECTRIC_LIGHT_3 * $this->expenseElecticMonth;
                }
            }
//------------------------------------------AREA STREET---------------------------------------------
            function findAreaStreet($result) {
                $areaAround=$result*1.4;
                $this->areaAroundStreet = $areaAround;
            }
//------------------------------------------STREET NEED CLEARER------------------------------------------------------------
            function findNumberCleaner($result) {
                $numberCleaner = floor($result/200);
                $this->cleaner = $numberCleaner;
            }
//------------------------------------------ANSWER TO USER INFORMATION ABOUT STREET-----------------------------
            function informationAboutStreet() {
                $informationAboutStreet = array( "Name street" => $this->nameStreet,
                                          "Start street" => $this->coordinateStartStreet,
                                          "End street" => $this->coordinateEndStreet,
                                          "Length street" => $this->lengthStreet,
                                          "Street need lighting colums" => $this->lightingColumn,
                                          "Month expense electic" => $this->expenseElecticMonth,
                                          "Area around street" => $this->areaAroundStreet,
                                          "Street needs cleaner" => $this->cleaner);
                echo '<b><i><h4>INFORMATION ABOUT STREET</h4></i></b><br>   ';
                $step = 0;
                foreach ($informationAboutStreet as $key => $value) {
                    echo '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $_SESSION['$informationAboutApartment'][$step] = '=>>   ' . $key . ' <b>' . $value . '</b>!<br>';
                    $step++;
                }
            }
//------------------------------------------FUNCTION FIND ALL AREA IN CITY-----------------------------------
            function sumAreaCity() {
                $sumAreaCity =  0;
                foreach ($_SESSION['codeCity'][$this->nameCity] as $key => $value) {
                    if (is_array($value)){
                    foreach ($value as $key2 => $value2)
                                if ( $key2 == "Area around street" ) {
                                        $sumAreaCity += $value2;
                                    }
                                }
                            }
                return $sumAreaCity;
            }
        }
?>
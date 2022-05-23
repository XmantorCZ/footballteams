<?php

namespace controllers;

class Loggedrights extends LoginController
{
    public function get_install(\Base $base)
    {
        $base->set('logo', 'settings');
        $base->set('content', 'install.html');
        $base->set("title", "INSTALL");
        echo \Template::instance()->render("index.html");
    }

    public function post_install(\Base $base)
    {

        if ($base->get('POST.SlaviaPraha') == true) {

            \models\SlaviaPraha::setdown();
            \models\SlaviaPraha::setup();

        }
        if ($base->get('POST.SPZapasy') == true) {

            \models\SPZapasy::setdown();
            \models\SPZapasy::setup();

        }
        if ($base->get('POST.Uzivatele') == true) {


            \models\Uzivatele::setdown();
            \models\Uzivatele::setup();

            /*
                        $slavia = new data\SlaviaPraha();

                        $players = $slavia->find(array(""));

                        foreach ($players as $player) {

                            $player->Zapasy = 0;
                            $player->Goly = 0;
                            $player->Asistence = 0;
                            $player->ZK = 0;
                            $player->CK = 0;

                            $player->save();
                        }
                    }
            */
        }
        $base->reroute('/');
    }

    public function get_upravithrace(\Base $base)
    {

        $id = $base->get('PARAMS.id');
        $slavia = new data\SlaviaPraha();
        $player = $slavia->findone(array('id=?', $id));
        $base->set('hrac', $slavia->findone(array('id=?', $id)));
        $base->set("content", "upravithrace.html");
        $base->set('title', $player->Jmeno . " " . $player->Prijmeni);
        echo \Template::instance()->render("index.html");

    }

    public function post_upravithrace(\Base $base)
    {
        $id = $base->get('POST.id');
        $slavia = new data\SlaviaPraha();
        $slavia->load(array("id=?", $id));
        $slavia->copyfrom($base->get('POST'));

        if ($base->get('POST.Zranen') == true) {
            $slavia->Zranen = 1;
        } else {
            $slavia->Zranen = 0;
        }

        $slavia->save();

        $base->reroute('/slaviapraha');

    }

    public function get_upravitzapas(\Base $base)
    {

        $id = $base->get('PARAMS.id');
        $zapasy = new data\SPZapasy();
        $match = $zapasy->findone(array('id=?', $id));
        $base->set('zapas', $zapasy->findone(array('id=?', $id)));
        $base->set("content", "upravitzapas.html");
        $base->set('title', $match->HOST . " - " . $match->AWAY);
        echo \Template::instance()->render("index.html");


    }

    public function post_upravitzapas(\Base $base)
    {
        $id = $base->get('POST.id');
        $zapasy = new data\SPZapasy();
        $zapasy->load(array("id=?", $id));
        $zapasy->copyfrom($base->get('POST'));

        $zapasy->save();

        $base->reroute('/slaviapraha');

    }


    public function post_slaviapraha(\Base $base)
    {
        if ($base->get('POST["Jmeno"]') != "") {
            $slavia = new data\SlaviaPraha();
            $slavia->copyfrom($base->get('POST'));
            $slavia->Narod = $base->get('POST["Narod"]') . ".png";
            $slavia->Zapasy = 0;
            $slavia->Goly = 0;
            $slavia->Asistence = 0;
            $slavia->ZK = 0;
            $slavia->CK = 0;
            $slavia->save();
            $base->reroute('/slaviapraha');
        }
        if ($base->get('POST["DIVACI"]') != "") {
            $zapasy = new data\SPZapasy();
            $slavia = new data\SlaviaPraha();
            $nations = new data\SlaviaPraha();
            $zapasy->copyfrom($base->get('POST'), function ($val) {
                // the 'POST' array is passed to our callback function
                return array_intersect_key($val, array_flip(array('SOUTEZ', 'OHOST', 'OAWAY', 'HOST', 'AWAY', 'DATUM', 'DIVACI', 'STADION', 'VIDEOLINK', 'PLAYERSLINK')));
            });
            $dan = "";
            $ghost = 0;
            $gaway = 0;
            $temp = "";
            $uz = "";
            $zk = "";
            $ck = "";
            $asi = "";
            $spoj = "";

            $slaviapraha = $slavia->findone(array('Jmeno=?', "Slavia"));

            if ($base->get('POST["WIN"]') == "0") {

                $temp = 0;

                $zapasy->HOST = 'Slavia';
                $zapasy->AWAY = $base->get('POST["AWAY"]');

                $zapasy->OHOST = 'Slavia' . ".png";
                $zapasy->OAWAY = $base->get('POST["AWAY"]') . ".png";

            } else if ($base->get('POST["WIN"]') == "1") {

                $temp = 1;

                $zapasy->AWAY = 'Slavia';
                $zapasy->HOST = $base->get('POST["AWAY"]');

                $zapasy->OHOST = $base->get('POST["AWAY"]') . ".png";
                $zapasy->OAWAY = 'Slavia' . ".png";

            }

            if ($base->get('POST.ODEHRANE') == false) {
                $zapasy->ODEHRANE = '0';
            }
            if ($base->get('POST.ODEHRANE') == true) {
                $zapasy->ODEHRANE = '1';
                $slaviapraha->Zapasy += 1;
            }

            $zapasy->save();
            $slaviapraha->save();


            $pole = $base->get('POST.GOLY');

            sort($pole);

            for ($i = 0; $i < count($pole) - 1; $i++) {

                $spoj .= $pole[$i] . ";";

            }
            $spoj .= $pole[count($pole) - 1];

            $gol = explode(";", $spoj);

            foreach ($gol as $roz) {
                $ro = explode(",", $roz);

                /*
                if ($ro[0][0] == '0') {
                    //01
                    $ro[0] = substr($ro[0], 1);

                }
                */

                $ro[0] = "<span class='vertical-center' style='font-size: 75%; color: gray'>" . $ro[0] . "'</span>";


                if ($ro[1] == 1) {

                    $ro[1] = '<span style="padding-left: 3px; padding-right: 3px"><img alt="Goal" width="16px" height="16px" src="/images/Goal2.png"></span>';
                    $uz = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $asi = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[3], $ro[3]));
                    $var = "";
                    if ($ro[3] == "") {
                        $var = 1;
                    } else {
                        $var = 0;
                    }

                    if ($asi == "") {

                        $ro[3] = "<span class='vertical-center' style='color: gray;font-size:85%'>(" . $ro[3] . ")</span>";
                        $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";

                        if ($var == 1) {
                            $ro[3] = "";
                        }

                    } else {

                        $ro[3] = "<span class='vertical-center' style='color: gray;font-size:85%'>(" . $ro[3] . ")</span>";
                        $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";
                        if ($var == 1) {
                            $ro[3] = "";
                        }
                        $slaviapraha->Asistence += 1;
                        $asi->Asistence += 1;
                        $asi->save();

                    }
                    if ($uz == "") {
                        if ($temp == 1) {
                            $roz = implode(" ", $ro);
                            $ghost += 1;
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $gaway += 1;
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        }

                    } else {
                        if ($temp == 1) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $gaway += 1;
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = implode(" ", $ro);
                            $ghost += 1;
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        }
                        $slaviapraha->Goly += 1;
                        $uz->Goly += 1;
                        $uz->save();

                    }

                    $dan .= $roz . '<br>';


                } else if ($ro[1] == 2) {
                    $ro[1] = '<span style="padding-left: 3px; padding-right: 3px"><img alt="YellowCard" width="16px" height="16px" src="/images/yellow-card.png"></span>';
                    $zk = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";
                    $ro[3] = "";

                    if ($zk == "") {
                        if ($temp == 1) {
                            $roz = implode(" ", $ro);
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        }


                    } else {
                        $slaviapraha->ZK += 1;
                        $zk->ZK += 1;
                        $zk->save();

                        if ($temp == 1) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = implode(" ", $ro);
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        }
                    }

                    $dan .= $roz . "<br>";

                }
                if ($ro[1] == 3) {
                    $ro[1] = '<span style="padding-left: 3px; padding-right: 3px"><img alt="RedCard" width="16px" height="16px" src="/images/red-card.png"></span>';

                    $ck = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));

                    $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";
                    $ro[3] = "";

                    if ($ck == "") {
                        if ($temp == 1) {
                            $roz = implode(" ", $ro);
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        }

                    } else {
                        $slaviapraha->CK += 1;
                        $ck->CK += 1;
                        $ck->save();

                        if ($temp == 1) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = implode(" ", $ro);
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        }
                    }
                    $dan .= $roz . "<br>";
                }
                if ($ro[1] == 4) {
                    $ro[1] = '<span style="padding-left: 3px; padding-right: 3px"><img alt="Penalty" width="16px" height="16px" src="/images/penalty.png"></span>';

                    $penalty = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";
                    $ro[3] = "";
                    if ($penalty == "") {
                        if ($temp == 1) {
                            $roz = implode(" ", $ro);
                            $ghost += 1;
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $gaway += 1;
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        }

                    } else {
                        $slaviapraha->Goly += 1;
                        $penalty->Goly += 1;
                        $penalty->save();

                        if ($temp == 1) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $gaway += 1;
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = implode(" ", $ro);
                            $ghost += 1;
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        }
                    }

                    $dan .= $roz . "<br>";
                }
                if ($ro[1] == 5) {
                    $ro[1] = '<span style="padding-left: 3px; padding-right: 3px"><img alt="Penalty" width="16px" height="16px" src="/images/penalty.png"> <img alt="Miss" width="16px" height="16px" src="/images/miss.png"></span>';

                    $penaltymiss = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";
                    $ro[3] = "";
                    if ($penaltymiss == "") {
                        if ($temp == 1) {
                            $roz = implode(" ", $ro);
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        }

                    } else {
                        if ($temp == 1) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = implode(" ", $ro);
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        }
                    }

                    $dan .= $roz . "<br>";
                }
                if ($ro[1] == 6) {

                    $ro[1] = '<span style="padding-left: 3px; padding-right: 3px"><img alt="OwnGoal" width="16px" height="16px" src="/images/OwnGoal.png"></span>';
                    $uz = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $asi = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[3], $ro[3]));

                    if ($ro[3] == "") {
                        $var = 1;
                    } else {
                        $var = 0;
                    }

                    if ($asi == "") {

                        $ro[3] = "<span class='vertical-center' style='color: gray;font-size:85%'>(" . $ro[3] . ")</span>";
                        $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";

                        if ($var == 1) {
                            $ro[3] = "";
                        }

                    } else {

                        $ro[3] = "<span class='vertical-center' style='color: gray;font-size:85%'>(" . $ro[3] . ")</span>";
                        $ro[2] = "<span class='vertical-center' style='font-size:85%'>" . $ro[2] . "</span>";

                        if ($var == 1) {
                            $ro[3] = "";
                        }

                    }
                    if ($uz == "") {
                        $slaviapraha->Goly += 1;
                        if ($temp == 0) {
                            $roz = implode(" ", $ro);
                            $ghost += 1;
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 1) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $gaway += 1;
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        }

                    } else {
                        if ($temp == 0) {
                            $revroz = array_reverse($ro);
                            $roz = implode(" ", $revroz);
                            $gaway += 1;
                            $roz = "<span class='vertical-center' style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 1) {
                            $roz = implode(" ", $ro);
                            $ghost += 1;
                            $roz = "<span class='vertical-center' style='float: left'>" . $roz . "</span>";
                        }

                    }

                    $dan .= $roz . '<br>';


                }


            }
            $slaviapraha->save();


            $zapasy->GHOST = $ghost;
            $zapasy->GAWAY = $gaway;

            $zapasy->GOLY = $dan;


            $zapasy->save();
            $base->reroute('/slaviapraha');
        }
    }


}
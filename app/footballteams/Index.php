<?php


namespace footballteams;


use DateTime;

class Index
{

    public function menu(\Base $base)
    {
        $base->set("content", "menu.html");
        $base->set("title", "MENU");
        $base->set("logo", "Menu");
        echo \Template::instance()->render("index.html");
    }

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

            \footballteams\data\SlaviaPraha::setdown();
            \footballteams\data\SlaviaPraha::setup();

        }
        if ($base->get('POST.SPZapasy') == true) {

            \footballteams\data\SPZapasy::setdown();
            \footballteams\data\SPZapasy::setup();

        }

        $base->reroute('/');

    }

    public function get_slaviapraha(\Base $base)
    {
        //['limit'=>x,'order'=>'xxx ASC'];
        $slavia = new data\SlaviaPraha();
        $base->set("logo", "Slavia");
        //$base->set("brankari", $slavia->find());
        $base->set("brankari", $slavia->find("Pozice='BR'"));
        $base->set("obranci", $slavia->find("Pozice='OB'"));
        $base->set("zaloznici", $slavia->find("Pozice='ZA'"));
        $base->set("utocnici", $slavia->find("Pozice='UT'"));
        $zapasy = new data\SPZapasy();

        $zapas = $zapasy->find("", ['limit' => 5, 'order' => 'DATUM DESC']);


        if ($zapas != "") {
            foreach ($zapas as $value) {

                $value->DATUM = date('d/m/Y', strtotime($value->DATUM));
            }

        }

        $base->set("zapasy", $zapas);

        $base->set("title", "SLAVIA PRAHA");
        $base->set("content", 'slaviapraha.html');
        echo \Template::instance()->render("index.html");
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
            $zapasy->copyfrom($base->get('POST'), function ($val) {
                // the 'POST' array is passed to our callback function
                return array_intersect_key($val, array_flip(array('SOUTEZ', 'OHOST', 'OAWAY', 'HOST', 'AWAY', 'DATUM', 'DIVACI', 'STADION')));
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

                $ro[0] = "<span style='font-size: 75%; color: gray'>" . $ro[0] . "'</span>";


                if ($ro[1] == 1) {

                    $ro[1] = '<img alt="Goal" width="16px" height="16px" src="/images/Goal.png">';
                    $uz = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $asi = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[3], $ro[3]));
                    if ($asi == "") {

                        $ro[3] = "<span style='color: gray'>(" . $ro[3] . ")</span>";

                    } else {

                        $ro[3] = "<span style='color: gray'>(" . $ro[3] . ")</span>";
                        $asi->Asistence += 1;
                        $asi->save();

                    }
                    $roz = implode(" ", $ro);
                    if ($uz == "") {

                        if ($temp == 1) {
                            $ghost += 1;
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $gaway += 1;
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        }

                    } else {

                        if ($temp == 1) {
                            $gaway += 1;
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $ghost += 1;
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        }

                        $uz->Goly += 1;
                        $uz->save();

                    }

                    $dan .= $roz . "<br>";


                } else if ($ro[1] == 2) {
                    $ro[1] = '<img alt="YellowCard" width="16px" height="16px" src="/images/yellow-card.png">';

                    $zk = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[3] = "";
                    $roz = implode(" ", $ro);
                    if ($zk == "") {


                        if ($temp == 1) {
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        }


                    } else {

                        $zk->ZK += 1;
                        $zk->save();

                        if ($temp == 1) {
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        }
                    }

                    $dan .= $roz . "<br>";

                }
                if ($ro[1] == 3) {
                    $ro[1] = '<img alt="RedCard" width="16px" height="16px" src="/images/red-card.png">';

                    $ck = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[3] = "";
                    $roz = implode(" ", $ro);
                    if ($ck == "") {

                        if ($temp == 1) {
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        }

                    } else {

                        $ck->CK += 1;
                        $ck->save();

                        if ($temp == 1) {
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        }
                    }
                    $dan .= $roz . "<br>";
                }
                if ($ro[1] == 4) {
                    $ro[1] = '<img alt="Penalty" width="16px" height="16px" src="/images/penalty.png">';

                    $penalty = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[3] = "";
                    $roz = implode(" ", $ro);
                    if ($penalty == "") {

                        if ($temp == 1) {
                            $ghost += 1;
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $gaway += 1;
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        }

                    } else {

                        $penalty->Goly += 1;
                        $penalty->save();

                        if ($temp == 1) {
                            $gaway += 1;
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $ghost += 1;
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        }
                    }

                    $dan .= $roz . "<br>";
                }
                if ($ro[1] == 5) {
                    $ro[1] = '<img alt="Penalty" width="16px" height="16px" src="/images/penalty.png"> <img alt="Miss" width="16px" height="16px" src="/images/miss.png">';

                    $penaltymiss = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $ro[3] = "";
                    $roz = implode(" ", $ro);
                    if ($penaltymiss == "") {

                        if ($temp == 1) {
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        }

                    } else {

                        if ($temp == 1) {
                            $roz = "<span style='float: right'>" . $roz . "</span>";
                        } else if ($temp == 0) {
                            $roz = "<span style='float: left'>" . $roz . "</span>";
                        }
                    }

                    $dan .= $roz . "<br>";
                }

            }


            $zapasy->GHOST = $ghost;
            $zapasy->GAWAY = $gaway;

            $zapasy->GOLY = $dan;


            $zapasy->save();
            $base->reroute('/slaviapraha');
        }
    }

    public function get_upravithrace(\Base $base)
    {

        $id = $base->get('PARAMS.id');
        $slavia = new data\SlaviaPraha();
        $player=$slavia->findone(array('id=?', $id));
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

        $slavia->save();

        $base->reroute('/slaviapraha');

    }


}
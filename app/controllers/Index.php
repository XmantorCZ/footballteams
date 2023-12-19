<?php


namespace controllers;


use DateTime;

class Index extends RolesController
{

    public function menu(\Base $base)
    {
        $teams = new \models\Teams();
        $base->set("teams",$teams->find());
        $base->set("content", "menu.html");
        $base->set("title", "MENU");
        $base->set("logo", "Menu");
        echo \Template::instance()->render("index.html");
    }

    public function get_leagues(\Base $base)
    {
        $leagues = new \models\Leagues();
        $base->set("teams",$leagues->find());
        $base->set("content", "leagues.html");
        $base->set("title", "Leagues");
        $base->set("logo", "Menu");
        echo \Template::instance()->render("index.html");
    }

    public function get_registrace(\Base $base)
    {
        $base->set("error", "");
        $uzivatele = new \models\Uzivatele();
        $jmeno = $base->get('POST["nickname"]');
        $uz = $uzivatele->findone(array("nickname=?", $jmeno));
        $base->set("content", "login.html");
        $base->set("moznost", "Registrace");
        $base->set("title", "REGISTER");
        date_default_timezone_set("UTF+2");
        $base->set("den", date("Y-m-d"));
        echo \Template::instance()->render("index.html");

    }

    public function get_match(\Base $base)
    {
        $team = $base->get("PARAMS.team");

        $teams= new \models\Teams();
        $match= new \models\Matches();
        $find_team = $teams->findone(array("teamid=?", $team));
        $base->set("teamid", $find_team->teamid);
        $base->set("teamname", $find_team->fullname );
        $matches = $match -> find("host='$find_team->name' OR away='$find_team->name'", ['order' => 'date DESC']);
        $base->set("date_of_match", date("Y-m-d"));
        $base->set("matches", $matches);
        $base->set("content", "match.html");
        $base->set("title", "MATCH");
        $base->set("logo", "Menu");


        echo \Template::instance()->render("index.html");

    }


    public function get_squad(\Base $base)
    {
        $teamid = $base->get("PARAMS.team");
        $base->set("content", "squad.html");
        $base->set("title", "SQUAD");
        $base->set("logo", "Menu");

        $slavia= new \models\Players();
        $teams= new \models\Teams();

        $team = $teams->findone(array("teamid=?", $teamid));

        $base->set("teamname", $team->fullname);

        $base->set("goals", $team->goals);
        $base->set("assists", $team->assists);
        $base->set("yellows", $team->yellows);
        $base->set("reds", $team->reds);
        $base->set("matches", $team->matches);

        $brankari = $slavia->find("position='BR' AND team='$team->name'", ['order' => 'value DESC']);
        if (!$brankari == "") {
            foreach ($brankari as $brankar) {

                if($brankar->value == "?") {

                } else {
                    if ((int)$brankar->value < 10) {
                        $brankar->value = substr($brankar->value, 1);
                    }
                    if ((float)$brankar->value >= 1) {
                        $brankar->value = substr($brankar->value, 0, -1);
                        $brankar->value = $brankar->value . 'mil';
                    }
                    if ((float)$brankar->value < 1) {
                        $brankar->value = $brankar->value * 1000;
                        $brankar->value = $brankar->value . 'tis';
                    }

                }
            }
        }
        $base->set("brankari", $brankari);

        $obranci = $slavia->find("position='OB' AND team='$team->name'", ['order' => 'value DESC']);

        if (!$obranci == "") {
            foreach ($obranci as $obrance) {

                if ($obrance->value == "?") {

                } else {
                    if ((int)$obrance->value < 10) {
                        $obrance->value = substr($obrance->value, 1);
                    }
                    if ((float)$obrance->value >= 1) {
                        $obrance->value = substr($obrance->value, 0, -1);
                        $obrance->value = $obrance->value. 'mil';
                    }
                    if ((float)$obrance->value < 1) {
                        $obrance->value = $obrance->value * 1000;
                        $obrance->value = $obrance->value . 'tis';
                    }


                }

            }
        }
        $base->set("obranci", $obranci);

        $zaloznici = $slavia->find("position='ZA' AND team='$team->name'", ['order' => 'value DESC']);
        if (!$zaloznici == "") {
            foreach ($zaloznici as $zaloznik) {

                if ($zaloznik->value == "?") {

                } else {
                    if ((int)$zaloznik->value < 10) {
                        $zaloznik->value = substr($zaloznik->value, 1);
                    }
                    if ((float)$zaloznik->value >= 1) {
                        $zaloznik->value = substr($zaloznik->value, 0, -1);
                        $zaloznik->value = $zaloznik->value . 'mil';
                    }
                    if ((float)$zaloznik->value < 1) {
                        $zaloznik->value = $zaloznik->value * 1000;
                        $zaloznik->value = $zaloznik->value . 'tis';
                    }

                }

            }
        }
        $base->set("zaloznici", $zaloznici);

        $utocnici = $slavia->find("position='UT' AND team='$team->name'", ['order' => 'value DESC']);
        if (!$utocnici == "") {
            foreach ($utocnici as $utocnik) {

                if ($utocnik->value == "?") {

                } else {

                    if ((int)$utocnik->value < 10) {
                        $utocnik->value = substr($utocnik->value, 1);
                    }
                    if ((int)$utocnik->value >= 1) {
                        $utocnik->value = substr($utocnik->value, 0, -1);
                        $utocnik->value = $utocnik->value . 'mil';
                    }
                    if ((int)$utocnik->value < 1) {
                        $utocnik->value = $utocnik->value * 1000;
                        $utocnik->value = $utocnik->value . 'tis';
                    }


                }


            }
        }
        $base->set("utocnici", $utocnici);

        echo \Template::instance()->render("index.html");
    }

    public function post_registrace(\Base $base)
    {

        $base->set("error", "");
        $uzivatele = new \models\Uzivatele();
        $uzivatele->copyfrom($base->get('POST'));
        $uz = $uzivatele->findone(array("nickname=?", $uzivatele->nickname));
        if ($uz == NULL) {
            $uzivatele->password = password_hash($base->get('POST.password'), PASSWORD_DEFAULT);
            date_default_timezone_set("UTF+2");
            $uzivatele->date = date('Y/m/d');
            $uzivatele->save();
            $base->reroute("/");
        } else {
            $base->set("error", "Toto jméno je již zabrané");
            $base->set("title", "LOGIN");
            $base->set("content", "login");
            echo \Template::instance()->render("index.html");

        }


    }

    public function get_login(\Base $base)
    {
        $base->set("error", "");
        $base->set("content", "login.html");
        $base->set("title", "LOGIN");
        $base->set("moznost", "Přihlášení");
        $base->set("logo", "login");
        echo \Template::instance()->render("index.html");
    }

    public function post_login(\Base $base)
    {

        $uzivatele = new \models\Uzivatele();
        $jmeno = $base->get('POST["nickname"]');
        $uz = $uzivatele->findone(array("nickname=?", $jmeno));
        if ($uz == NULL) {
            $base->set("title", "Login");
            $base->set("error", "Tvoje jméno nebo heslo nesouhlasí");
            $base->set("moznost", "Přihlášení");
            $base->set("content", "login.html");
            echo \Template::instance()->render("index.html");
        } else {
            if (password_verify($base->get('POST["password"]'), $uz->password)) {

                $base->set("SESSION.name", $jmeno);
                $base->set("SESSION.id", $uz->id);
                $base->set("SESSION.role", $uz->role);
                $base->set("SESSION.regdate", $uz->date);
                $base->reroute("/");

            } else {
                $base->set("title", "Login");
                $base->set("moznost", "Přihlášení");
                $base->set("error", "Tvoje jméno nebo heslo nesouhlasí");
                $base->set("content", "login.html");
                echo \Template::instance()->render("index.html");
            }
        }


    }
    public function get_slaviapraha(\Base $base)
    {
        //['limit'=>x,'order'=>'xxx ASC'];
        $teamname = $base->get('PARAMS.team');
        $players = new \models\Players();
        $teams = new \models\Teams();
        $base->set("teamid", $teamname);
        $base->set("logo", "Slavia");

        $findteam = $teams->findone(array("teamid='$teamname'"));

        $base->set("golycelkem", $findteam->goals);
        $base->set("asistcelkem", $findteam->assists);
        $base->set("zkcelkem", $findteam->yellows);
        $base->set("ckcelkem", $findteam->reds);
        $base->set("zapasycelkem", $findteam->matches);

        $nameteam = $findteam->name;
        $valueteam = $players->find("team='$nameteam'");

        $base->set("brankariselect", $players->find("team='$nameteam' AND position='BR' AND state!='departure' AND state!='hostdeparture'", ['order' => 'matches DESC']));
        $base->set("zalozniciselect", $players->find("team='$nameteam' AND position='ZA' AND state!='departure' AND state!='hostdeparture'", ['order' => 'matches DESC']));
        $base->set("obranciselect", $players->find("team='$nameteam' AND position='OB' AND state!='departure' AND state!='hostdeparture'", ['order' => 'matches DESC']));
        $base->set("utocniciselect", $players->find("team='$nameteam' AND position='UT' AND state!='departure' AND state!='hostdeparture'", ['order' => 'matches DESC']));

        $value = 0;
        if (!$valueteam == "") {
            foreach ($valueteam as $valuecelkem) {
                $value = $value + (float)$valuecelkem->value;
            }
        }
        $base->set("valuecelkem", $value);

        $base->set("strelci", $players->find("team='$nameteam' AND position!='default'", ['limit' => 5, 'order' => 'goals DESC']));
        $base->set("asistence", $players->find("team='$nameteam' AND position!='default'", ['limit' => 5, 'order' => 'assists DESC']));
        $base->set("zlutekarty", $players->find("team='$nameteam' AND position!='default'", ['limit' => 5, 'order' => 'yellows DESC']));

        $nezarazeny = $players->find("team='$nameteam' AND state='departure' OR state='hostdeparture'", ['order' => 'value DESC']);

        if (!$nezarazeny == "") {
            foreach ($nezarazeny as $brankar) {

                if ($brankar->value == "?") {

                } else {
                    if ((int)$brankar->value < 10) {
                        $brankar->value = substr($brankar->value, 1);
                    }
                    if ((float)$brankar->value >= 1) {
                        $brankar->value = substr($brankar->value, 0, -1);
                        $brankar->value = $brankar->value . 'mil';
                    }
                    if ((float)$brankar->value < 1) {
                        $brankar->value = (int)$brankar->value * 1000;
                        $brankar->value = $brankar->value . 'tis';
                    }

                }
            }
        }

        $base->set("nazarazeny", $nezarazeny);

        $brankari = $players->find("position='BR' AND team='$nameteam' AND state!='departure' AND state!='hostdeparture'", ['order' => 'value DESC']);

        if (!$brankari == "") {
            foreach ($brankari as $brankar) {

                if ($brankar->value == "?") {

                } else {
                    if ((int)$brankar->value < 10) {
                        $brankar->value = substr($brankar->value, 1);
                    }
                    if ((float)$brankar->value >= 1) {
                        $brankar->value = substr($brankar->value, 0, -1);
                        $brankar->value = $brankar->value . 'mil';
                    }
                    if ((float)$brankar->value < 1) {
                        $brankar->value = $brankar->value * 1000;
                        $brankar->value = $brankar->value . 'tis';
                    }

                }
            }
        }
        $base->set("brankari", $brankari);

        $obranci = $players->find("position='OB' AND team='$nameteam' AND state!='departure' AND state!='hostdeparture'", ['order' => 'value DESC']);

        if (!$obranci == "") {
            foreach ($obranci as $obrance) {

                if ($obrance->value == "?") {

                } else {
                    if ((int)$obrance->value < 10) {
                        $obrance->value = substr($obrance->value, 1);
                    }
                    if ((float)$obrance->value >= 1) {
                        $obrance->value = substr($obrance->value, 0, -1);
                        $obrance->value = $obrance->value . 'mil';
                    }
                    if ((float)$obrance->value < 1) {
                        $obrance->value = $obrance->value * 1000;
                        $obrance->value = $obrance->value . 'tis';
                    }


                }

            }
        }
        $base->set("obranci", $obranci);

        $zaloznici = $players->find("position='ZA' AND team='$nameteam' AND state!='departure' AND state!='hostdeparture'", ['order' => 'value DESC']);
        if (!$zaloznici == "") {
            foreach ($zaloznici as $zaloznik) {

                if ($zaloznik->value == "?") {

                } else {
                    if ((int)$zaloznik->value < 10) {
                        $zaloznik->value = substr($zaloznik->value, 1);
                    }
                    if ((float)$zaloznik->value >= 1) {
                        $zaloznik->value = substr($zaloznik->value, 0, -1);
                        $zaloznik->value = $zaloznik->value . 'mil';
                    }
                    if ((float)$zaloznik->value < 1) {
                        $zaloznik->value = $zaloznik->value * 1000;
                        $zaloznik->value = $zaloznik->value . 'tis';
                    }

                }

            }
        }
        $base->set("zaloznici", $zaloznici);

        $utocnici = $players->find("position='UT' AND team='$nameteam' AND state!='departure' AND state!='hostdeparture'", ['order' => 'value DESC']);
        if (!$utocnici == "") {
            foreach ($utocnici as $utocnik) {

                if ($utocnik->value == "?") {

                } else {

                    if ((int)$utocnik->value < 10) {
                        $utocnik->value = substr($utocnik->value, 1);
                    }
                    if ((int)$utocnik->value >= 1) {
                        $utocnik->value = substr($utocnik->value, 0, -1);
                        $utocnik->value = $utocnik->value . 'mil';
                    }
                    if ((int)$utocnik->value < 1) {
                        $utocnik->value = $utocnik->value * 1000;
                        $utocnik->value = $utocnik->value . 'tis';
                    }


                }


            }
        }
        $base->set("utocnici", $utocnici);

        //$zapasy = new \models\SPZapasy();

        //$zapas = $zapasy->find("", ['limit' => 15, 'order' => 'DATUM DESC']);


        //if ($zapas != "") {
          //  foreach ($zapas as $value) {

            //    $value->DATUM = date('d/m/Y', strtotime($value->DATUM));
           // }

        //}

        //$base->set("zapasy", $zapas);

        $base->set("title", $findteam->fullname);
        $base->set("content", 'slaviapraha.html');
        echo \Template::instance()->render("index.html");
    }
}
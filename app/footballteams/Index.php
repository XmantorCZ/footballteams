<?php


namespace footballteams;


use DateTime;

class Index extends RolesController
{

    public function menu(\Base $base)
    {
        $base->set("content", "menu.html");
        $base->set("title", "MENU");
        $base->set("logo", "Menu");
        echo \Template::instance()->render("index.html");
    }

    public function get_registrace(\Base $base)
    {
        $base->set("error", "");
        $uzivatele = new data\Uzivatele();
        $jmeno = $base->get('POST["nickname"]');
        $uz = $uzivatele->findone(array("nickname=?", $jmeno));
        $base->set("content", "login.html");
        $base->set("moznost", "Registrace");
        $base->set("title", "REGISTER");
        date_default_timezone_set("UTF+2");
        $base->set("den", date("Y-m-d"));
        echo \Template::instance()->render("index.html");

    }

    public function post_registrace(\Base $base)
    {

        $base->set("error", "");
        $uzivatele = new data\Uzivatele();
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

        $uzivatele = new data\Uzivatele();
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
        $slavia = new data\SlaviaPraha();
        $base->set("logo", "Slavia");
        //$base->set("brankari", $slavia->find());

        $goly = $slavia->findone(array("Jmeno=?", "Slavia"));


        $base->set("golycelkem", $goly->Goly);
        $base->set("asistcelkem", $goly->Asistence);
        $base->set("zkcelkem", $goly->ZK);
        $base->set("ckcelkem", $goly->CK);
        $base->set("zapasycelkem", $goly->Zapasy);
        $value = 0;
        $valueslavia = $slavia->find("Stav!='departure' AND Stav!='hostdeparture'");

        $base->set("brankariselect", $slavia->find("Pozice='BR' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Zapasy DESC']));
        $base->set("zalozniciselect", $slavia->find("Pozice='ZA' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Zapasy DESC']));
        $base->set("obranciselect", $slavia->find("Pozice='OB' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Zapasy DESC']));
        $base->set("utocniciselect", $slavia->find("Pozice='UT' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Zapasy DESC']));

        foreach ($valueslavia as $valuecelkem) {

            $value = $value + (float)$valuecelkem->Hodnota;

        }
        $base->set("valuecelkem", $value);
        $base->set("strelci", $slavia->find('Pozice!="default"', ['limit' => 5, 'order' => 'Goly DESC']));
        $base->set("asistence", $slavia->find('Pozice!="default"', ['limit' => 5, 'order' => 'Asistence DESC']));
        $base->set("zlutekarty", $slavia->find('Pozice!="default"', ['limit' => 5, 'order' => 'ZK DESC']));

        $nezarazeny = $slavia->find("Stav!='stayed' AND Stav!='arrival' AND Stav!='hostarrival'", ['order' => 'Hodnota DESC']);

        if (!$nezarazeny == "") {
            foreach ($nezarazeny as $brankar) {

                if ($brankar->Hodnota == "?") {

                } else {
                    if ((int)$brankar->Hodnota < 10) {
                        $brankar->Hodnota = substr($brankar->Hodnota, 1);
                    }
                    if ((float)$brankar->Hodnota >= 1) {
                        $brankar->Hodnota = substr($brankar->Hodnota, 0, -1);
                        $brankar->Hodnota = $brankar->Hodnota . 'mil';
                    }
                    if ((float)$brankar->Hodnota < 1) {
                        $brankar->Hodnota = $brankar->Hodnota * 1000;
                        $brankar->Hodnota = $brankar->Hodnota . 'tis';
                    }

                }
            }
        }

        $base->set("nazarazeny", $nezarazeny);

        $brankari = $slavia->find("Pozice='BR' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Hodnota DESC']);

        if (!$brankari == "") {
            foreach ($brankari as $brankar) {

                if ($brankar->Hodnota == "?") {

                } else {
                    if ((int)$brankar->Hodnota < 10) {
                        $brankar->Hodnota = substr($brankar->Hodnota, 1);
                    }
                    if ((float)$brankar->Hodnota >= 1) {
                        $brankar->Hodnota = substr($brankar->Hodnota, 0, -1);
                        $brankar->Hodnota = $brankar->Hodnota . 'mil';
                    }
                    if ((float)$brankar->Hodnota < 1) {
                        $brankar->Hodnota = $brankar->Hodnota * 1000;
                        $brankar->Hodnota = $brankar->Hodnota . 'tis';
                    }

                }
            }
        }
        $base->set("brankari", $brankari);

        $obranci = $slavia->find("Pozice='OB' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Hodnota DESC']);

        if (!$obranci == "") {
            foreach ($obranci as $obrance) {

                if ($obrance->Hodnota == "?") {

                } else {
                    if ((int)$obrance->Hodnota < 10) {
                        $obrance->Hodnota = substr($obrance->Hodnota, 1);
                    }
                    if ((float)$obrance->Hodnota >= 1) {
                        $obrance->Hodnota = substr($obrance->Hodnota, 0, -1);
                        $obrance->Hodnota = $obrance->Hodnota . 'mil';
                    }
                    if ((float)$obrance->Hodnota < 1) {
                        $obrance->Hodnota = $obrance->Hodnota * 1000;
                        $obrance->Hodnota = $obrance->Hodnota . 'tis';
                    }


                }

            }
        }
        $base->set("obranci", $obranci);

        $zaloznici = $slavia->find("Pozice='ZA' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Hodnota DESC']);
        if (!$zaloznici == "") {
            foreach ($zaloznici as $zaloznik) {

                if ($zaloznik->Hodnota == "?") {

                } else {
                    if ((int)$zaloznik->Hodnota < 10) {
                        $zaloznik->Hodnota = substr($zaloznik->Hodnota, 1);
                    }
                    if ((float)$zaloznik->Hodnota >= 1) {
                        $zaloznik->Hodnota = substr($zaloznik->Hodnota, 0, -1);
                        $zaloznik->Hodnota = $zaloznik->Hodnota . 'mil';
                    }
                    if ((float)$zaloznik->Hodnota < 1) {
                        $zaloznik->Hodnota = $zaloznik->Hodnota * 1000;
                        $zaloznik->Hodnota = $zaloznik->Hodnota . 'tis';
                    }

                }

            }
        }
        $base->set("zaloznici", $zaloznici);

        $utocnici = $slavia->find("Pozice='UT' AND Stav!='departure' AND Stav!='hostdeparture'", ['order' => 'Hodnota DESC']);
        if (!$utocnici == "") {
            foreach ($utocnici as $utocnik) {

                if ($utocnik->Hodnota == "?") {

                } else {

                    if ((int)$utocnik->Hodnota < 10) {
                        $utocnik->Hodnota = substr($utocnik->Hodnota, 1);
                    }
                    if ((int)$utocnik->Hodnota >= 1) {
                        $utocnik->Hodnota = substr($utocnik->Hodnota, 0, -1);
                        $utocnik->Hodnota = $utocnik->Hodnota . 'mil';
                    }
                    if ((int)$utocnik->Hodnota < 1) {
                        $utocnik->Hodnota = $utocnik->Hodnota * 1000;
                        $utocnik->Hodnota = $utocnik->Hodnota . 'tis';
                    }


                }


            }
        }
        $base->set("utocnici", $utocnici);

        $zapasy = new data\SPZapasy();

        $zapas = $zapasy->find("", ['limit' => 15, 'order' => 'DATUM DESC']);


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


}
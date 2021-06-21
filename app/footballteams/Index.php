<?php


namespace footballteams;


use DateTime;

class Index
{

    public function menu(\Base $base)
    {
        $base->set("content", "menu.html");
        $base->set("title", "MENU");
        echo \Template::instance()->render("index.html");
    }

    public function install(\Base $base)
    {
        // \footballteams\data\SlaviaPraha::setdown();
        // \footballteams\data\SlaviaPraha::setup();

        \footballteams\data\SPZapasy::setdown();
        \footballteams\data\SPZapasy::setup();

        $base->reroute("/");
    }

    public function get_slaviapraha(\Base $base)
    {
        //['limit'=>x,'order'=>'xxx ASC'];
        $slavia = new data\SlaviaPraha();
        //$base->set("brankari", $slavia->find());
        $base->set("brankari", $slavia->find("Pozice='BR'"));
        $base->set("obranci", $slavia->find("Pozice='OB'"));
        $base->set("zaloznici", $slavia->find("Pozice='ZA'"));
        $base->set("utocnici", $slavia->find("Pozice='UT'"));
        $zapasy = new data\SPZapasy();

        $zapas = $zapasy->find("", ['limit' => 5, 'order' => 'DATUM DESC']);

        /* foreach ($zapas as $goly) {
            $dan = "";
            $gol = explode(";", $goly->GOLY);
            foreach ($gol as $roz) {
                $ro = explode(",", $roz);
                if ($ro[0] == 1) {
                    $ro[0] = "";
                    $ro[1] .= "'";
                    $ro[3] = "(" . $ro[3] . ")";

                    $roz = implode(" ", $ro);
                    $dan .= $roz . "<br>";
                }
            }
            $goly->GOLY = $dan;
        }
        */
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
        if ($base->get('POST["GHOST"]') != "") {
            $zapasy = new data\SPZapasy();
            $slavia = new data\SlaviaPraha();
            $zapasy->copyfrom($base->get('POST'));

            //superkrutopřísný nešahat!!!
            $dan = "";
            $gol = explode(";", $zapasy->GOLY);
            foreach ($gol as $roz) {
                $ro = explode(",", $roz);
                if ($ro[1] == 1) {
                    $ro[1] = '<img alt="Goal" width="16px" height="16px" src="/images/Goal.png">';
                    $ro[0] = "<span style='font-size: 75%; color: gray'>" . $ro[0] . "'</span>";

                    $uz = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[2], $ro[2]));
                    $uz->Goly += 1;

                    $asi = $slavia->findone(array("Prijmeni=? or Jmeno=?", $ro[3], $ro[3]));
                    $asi->Asistence += 1;

                    $ro[3] = "<span style='color: gray'>(" . $ro[3] . ")</span>";

                    $uz->save();
                    $asi->save();
                }
                $roz = implode(" ", $ro);
                $dan .= $roz . "<br>";

            }
            $zapasy->GOLY = $dan;


            if ($base->get('POST["WIN"]') == "0") {

                $zapasy->HOST = 'Slavia';
                $zapasy->AWAY = $base->get('POST["AWAY"]');

                $zapasy->OHOST = 'Slavia' . ".png";
                $zapasy->OAWAY = $base->get('POST["AWAY"]') . ".png";

            }
            if ($base->get('POST["WIN"]') == "1") {

                $zapasy->AWAY = 'Slavia';
                $zapasy->HOST = $base->get('POST["AWAY"]');

                $zapasy->OHOST = $base->get('POST["AWAY"]') . ".png";
                $zapasy->OAWAY = 'Slavia' . ".png";

            }

            $zapasy->save();
            $base->reroute('/slaviapraha');
        }

    }

}
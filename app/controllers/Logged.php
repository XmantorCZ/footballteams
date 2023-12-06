<?php

namespace controllers;

class Logged extends LoginController
{

    public function get_odhlaseni(\Base $base)
    {
        $base->clear("SESSION.name");
        $base->clear("SESSION.id");
        $base->set("SESSION.role", 0);
        $base->clear("SESSION.regdate");
        $base->reroute("/");
    }

    public function get_profil(\Base $base)
    {

        $base->set('content', 'profile.html');
        $base->set("title", "PROFIL");
        echo \Template::instance()->render("index.html");
    }
}
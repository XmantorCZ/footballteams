<?php

namespace controllers;

class Logged extends LoginController
{
    public function get_odhlaseni(\Base $base)
    {
        $base->clear("SESSION.name");
        $base->set("SESSION.name", "");
        $base->clear("SESSION.id");
        $base->set("SESSION.role", 0);
        $base->clear("SESSION.regdate");
        $base->reroute("/");
    }


}
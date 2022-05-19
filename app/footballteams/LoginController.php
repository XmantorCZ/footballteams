<?php

namespace footballteams;

class LoginController
{
    public function beforeRoute(\Base $base)
    {
        $username = $base->get("SESSION.name");
        if ($username != "") {


        } else {
            $base->reroute("/");

        }

    }

}
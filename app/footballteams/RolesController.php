<?php

namespace footballteams;

class RolesController
{
    public function beforeLogin(\Base $base)
    {
        $role = $base->get("SESSION.role");
        if ($role != "") {


        } else {
            $base->set("SESSION.role", "0");

        }

    }
}
<?php

namespace controllers;

class Rightsprotect
{
    public function beforeReroute(\Base $base)
    {
        $role = $base->get("SESSION.role");
        if ($role == "2") {


        } else {
            $base->reroute("/");

        }

    }

}
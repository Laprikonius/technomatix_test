<?php

namespace block_mvcuserlist\controllers;

use block_mvcuserlist\AbstractController;
use block_mvcuserlist\actions\UserGradeAction;

class UserGradeController extends AbstractController
{
    public static function actions()
    {
        return [
            'gradeUser' => UserGradeAction::class,
        ];
    }
}
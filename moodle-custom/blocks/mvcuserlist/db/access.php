<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    // Позволяет добавлять блок на личные страницы пользователей
    'block/mvcuserlist:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    // Позволяет добавлять блок на страницы курса
    'block/mvcuserlist:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);

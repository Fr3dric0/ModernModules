<?php
namespace Plugin\ModernModules;

class Event
{
    public static function ipBeforeController($data) {
        ipAddCss('assets/modernModules.css');
    }
}
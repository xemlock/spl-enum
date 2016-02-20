<?php

if (!class_exists('SplEnum')) {
    if (!class_exists('Polyfill_SplEnum')) {
        require_once dirname(__FILE__) . '/src/Polyfill/SplEnum.php';
    }
    class SplEnum extends Polyfill_SplEnum
    {
    }
}

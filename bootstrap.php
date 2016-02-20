<?php

if (!class_exists('SplEnum')) {
    if (!class_exists('Polyfill_SplEnum')) {
        require_once dirname(__FILE__) . '/src/Polyfill/SplEnum.php';
    }
    if (function_exists('class_alias')) {
        class_alias('Polyfill_SplEnum', 'SplEnum');
    } else {
        class SplEnum extends Polyfill_SplEnum
        {
        }
    }
}

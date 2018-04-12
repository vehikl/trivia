<?php

require_once "vendor/autoload.php";

foreach(range(1, 1000) as $seed) {
    playGame($seed, './tests/fixtures/output-seeded-with-'.$seed.'.txt');
}





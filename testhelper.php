<?php

echo 'Loaded php.ini: ' . php_ini_loaded_file() . '<br>';
echo 'intl loaded: ' . (extension_loaded('intl') ? 'yes' : 'no') . '<br>';
echo 'class_exists NumberFormatter: ' . (class_exists('NumberFormatter') ? 'yes' : 'no');

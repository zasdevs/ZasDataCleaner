<?php

require_once 'swap/ZasDataCleaner.php';

$cleaner = new ZasDataCleaner( 'mp4', '40%', '120_GB' );

echo $cleaner->delete_files();

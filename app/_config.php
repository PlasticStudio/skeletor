<?php

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;

require_once('conf/ConfigureFromEnv.php');

TinyMCEConfig::get('cms')
    ->addButtonsToLine(1, 'styleselect')
    ->setOption('importcss_append', true);

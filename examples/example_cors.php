<?php

require __DIR__ . '/../vendor/autoload.php';
require_once 'CorsFlowProcessor.php';

$processor = new CorsFlowProcessor(new Request());

echo <<<SMPL

=================================================
Rude.php - CorsFlowProcessor example
=================================================

Generating random results...

SMPL;
for ($i = 1; $i < 6; $i++) {
    echo "\n--- $i ------------------------\n";
    $processor->run();
}
echo <<<SMPL

=================================================
Finished.

SMPL;


<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/SimpleClass.php';

use Kaiser\Rude\Rude;
use Kaiser\Rude\Rule;

// --------------------

$simpleObj = new SimpleClass();
$rude = new Rude();

$rude->addRule(new Rule('func1', 'func2', 'func3'));
$rude->addRule(new Rule('func2', 'SimpleClass::simpleClassStaticFunc', [$simpleObj, 'simpleClassFunc']));
$rude->addRule(new Rule('func3', 'done', 'done'));
$rude->addRule(new Rule('done', 'done', 'done'));
$rude->addRule(new Rule('SimpleClass::simpleClassStaticFunc', 'func2', 'func3'));
$rude->addRule(new Rule([$simpleObj, 'simpleClassFunc'], 'done', 'done'));

echo <<<SMPL

=================================================
Rude.php - simple example
=================================================

Generating random results...

SMPL;
for ($i = 1; $i < 6; $i++) {
    $rude->check('func1');
    echo "\n--- $i ------------------------\n";
    echo "The result is: " . SimpleClass::$result . "\n";
    echo "And the path was: " . $rude->getPath() . "\n";
}
echo <<<SMPL

=================================================
Finished.

SMPL;

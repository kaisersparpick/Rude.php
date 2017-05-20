<?php

class SimpleClass {
    public static $result;

    public function simpleClassFunc() {
        SimpleClass::$result = 'simpleClassFunc instance method';
        return null;
    }
    public static function simpleClassStaticFunc() {
        SimpleClass::$result = 'simpleClassStaticFunc static method';
        return null;
    }
}

function func1() { SimpleClass::$result = 'func1'; return (rand(0, 13) % 2 === 0); }
function func2() { SimpleClass::$result = 'func2'; return (rand(0, 13) % 2 === 0); }
function func3() { SimpleClass::$result = 'func3'; return true; }
function done() { return null; }

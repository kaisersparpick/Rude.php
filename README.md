# Rude.php
Rude.php is a **PHP** implementation of the *rule-based control-flow pattern* [Rude](https://github.com/kaisersparpick/Rude).

## Usage

#### Creating an instance
```php
use Kaiser\Rude\{Rude, Rule};

$rude = new Rude();
```

#### Adding a rule

```php
$rude->addRule(new Rule('func1', 'SimpleClass::simpleClassStaticFunc', [$simpleObj, 'simpleClassFunc']));

// Rule constructor
public function __construct(callable $condition, ?callable $yes, ?callable $no)
```
`addRule` accepts a `Rule` object. The `Rule` constructor takes three arguments: the condition to check, the function to call when the result is true, and the function to call when it is false. Each argument must be a `callable`; the yes and the no callbacks are  `nullable`.

The return value of conditions must be `true` for proceeding with the yes callback and `false` with the no branch. When a condition returns `null`, Rude exits the condition chain. In this case, the yes and no callbacks can be `null`. These conditions are usually exit points.

#### Checking conditions

Checking conditions based on the applied rules is triggered by calling `$rude->check()`.

```php
$rude->check('func1');
```

This specifies the entry point in the condition chain and can be set to any valid rule condition.

See the **examples** folder for more details.

<?php

namespace App\Models;

/**
 * Section is kept as a class alias for SchoolClass.
 *
 * The database table is still named "sections", and Laravel's autoloader
 * previously cached this file. Rather than requiring `composer dump-autoload`
 * on every developer machine, this alias makes both class names work
 * transparently — so any code that still references App\Models\Section
 * will resolve to SchoolClass without errors.
 *
 * DO NOT add logic here — put everything in SchoolClass.php.
 */
class Section extends SchoolClass
{
    // Intentionally empty — this is a pure alias.
}

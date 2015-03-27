<?php

require('../src/Toml.php');

$toml = 'thevoid = [[[[[]]]]]';

$result = Toml::parse($toml);

print_r($result);
echo json_encode($result);
echo "\n";

// Format response values into test suite object
function walk(&$a) {
    foreach($a as $i => $v)
    {
        if(is_array($v) && array_values($v) !== $v)
        {
            walk($a[$i]);
        }
        else
        {
            // Get type
            $t = gettype($v);

            // Parse type name
            $t = str_replace(array('boolean', 'double'), array('bool', 'float'), $t);

            // Check date type
            if(Toml::isISODate($v))
            {
                $t = 'datetime';
            }

            // Fix double vs integer type
            if($t == 'float' && $v == intval($v))
            {
                $t = 'integer';
            }

            // Parse array type
            if($t == 'array')
            {
                walk($v);
            }
            else
            {
                $v = "$v";
            }

            $a[$i] = array(
                'type' => $t,
                'value' => $v,
            );
        }
    }
}

walk($result);

print_r($result);
echo json_encode($result);

echo "\n";

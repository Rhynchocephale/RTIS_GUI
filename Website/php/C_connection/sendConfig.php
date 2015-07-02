<?php
exec('cd ../../C && ./test "'. str_replace(","," ",$_POST["array"]) .'"',$output,$result);
echo "output: ".implode(",",$output).", result: $result\n\n";
?>

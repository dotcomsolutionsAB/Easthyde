<?php

// include the Diff class
require_once './class.Diff.php';

// output the result of comparing two files as HTML
echo Diff::toHTML(Diff::compareFiles('279-updated.xml', '279.xml'));

?>

<style>
	.diff td{
  vertical-align : top;
  white-space    : pre;
  white-space    : pre-wrap;
  font-family    : monospace;
}
</style>
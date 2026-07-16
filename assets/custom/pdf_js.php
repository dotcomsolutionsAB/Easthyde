<?php
require("../plugins/global/fpdf/fpdf.php");

// PHP 8+: bare FPDF align/border tokens become undefined constants
$__fpdf_consts = ['L', 'C', 'R', 'J', 'T', 'B'];
// All non-empty border combinations of L/T/R/B (order variants used in this codebase)
foreach (['L','T','R','B'] as $a) {
	$__fpdf_consts[] = $a;
	foreach (['L','T','R','B'] as $b) {
		if ($a === $b) continue;
		$__fpdf_consts[] = $a.$b;
		foreach (['L','T','R','B'] as $c) {
			if ($c === $a || $c === $b) continue;
			$__fpdf_consts[] = $a.$b.$c;
			foreach (['L','T','R','B'] as $d) {
				if ($d === $a || $d === $b || $d === $c) continue;
				$__fpdf_consts[] = $a.$b.$c.$d;
			}
		}
	}
}
$__fpdf_consts = array_values(array_unique($__fpdf_consts));
foreach ($__fpdf_consts as $__fpdf_const) {
	if (!defined($__fpdf_const)) {
		define($__fpdf_const, $__fpdf_const);
	}
}
unset($__fpdf_consts, $__fpdf_const, $a, $b, $c, $d);

function dotcom_wordwrap($text, $limit){
    $text = (string)($text ?? '');
    $items=explode(" ",$text);
    $length=sizeof($items);
    $chara=0; $j=0; $new=""; $line=array();

    for($i=0;$i<$length;$i++){
        $chara=$chara+strlen((string)$items[$i])+1;
        if($chara>$limit){
            $line[$j]=$new;
            $j++;
            $chara=0;
            $new="";
            $i--;
        }else{
            $new=$new.$items[$i]." ";
        }
    }

    $line[$j]=$new;
    return $line;
}

function convertToIndianCurrency($number) {
    $number = (float)($number ?? 0);
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen((string)$no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
    return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
}

class PDF_JavaScript extends FPDF {

    protected $javascript;
    protected $n_js;

    function IncludeJS($script, $isUTF8=false) {
        $script = (string)($script ?? '');
        if(!$isUTF8) {
            // utf8_encode() removed in PHP 8.4
            if (function_exists('mb_convert_encoding')) {
                $script = mb_convert_encoding($script, 'UTF-8', 'ISO-8859-1');
            }
        }
        $this->javascript=$script;
    }

    function _putjavascript() {
        $this->_newobj();
        $this->n_js=$this->n;
        $this->_put('<<');
        $this->_put('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
        $this->_put('>>');
        $this->_put('endobj');
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/S /JavaScript');
        $this->_put('/JS '.$this->_textstring($this->javascript));
        $this->_put('>>');
        $this->_put('endobj');
    }

    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog() {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_put('/Names <</JavaScript '.($this->n_js).' 0 R>>');
        }
    }
}
?>
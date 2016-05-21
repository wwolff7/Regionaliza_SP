<?php
	function simpsonf($y, $sigma, $mi, $gama, $area){
		inverse_ncdf(1 - $y,$x);
		return (($gama + exp($mi + ($sigma * ($x)))) * $area);

	}
	function simpsonsrule($a, $b, $n, $sigma, $mi, $gama, $area,&$simp){
	// approximates integral_a_b f(x) dx with composite Simpson's rule with $n intervals
	// $n has to be an even number
	// f(x) is defined in "function simpsonf($x)"
	   if($n%2==0){
		  $h=($b-$a)/$n;
		  $S=simpsonf($a, $sigma, $mi, $gama, $area)+simpsonf($b, $sigma, $mi, $gama, $area);
		  $i=1;
		  while($i <= ($n-1)){
			 $xi=$a+$h*$i;
			 if($i%2==0){
				$S=$S+2*simpsonf($xi, $sigma, $mi, $gama, $area);
			 }
			 else{
				$S=$S+4*simpsonf($xi, $sigma, $mi, $gama, $area);
			 }
			 $i++;
		  }
		  return $simp = ($h/3*$S);
		  }
	   else{
		  return $simp = ('$n has to be an even number');
	   }
	}

?>
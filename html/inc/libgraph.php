<?php
#============================================================================
#
# Program: libgraph.pl
# Programmer: Remo Rickli
#
# Functions for RRDs and plotting (only functions for now, but intended for
# statistical graphing). The RRD related functions are directly used in the Map!
#
#============================================================================

#===================================================================
# Replace characters, which could break the (PNG) image
# Parameters:	label
# Global:	-
# Return:	sanitized label
#===================================================================
function Safelabel($s){
	return preg_replace('/[$&]/','.', $s);
}

#===================================================================
# Stack traffic and errors
# Parameters:	rrd, type
# Global:	-
# Return:	in/out graphs, title
#===================================================================
function GraphTraffic($rrd,$t){

	global $errlbl,$trflbl,$debug;

	$c = 0;
	$drawin = '';
	$drawout= '';
	$idef   = 'inoct';
	$odef   = 'outoct';
	#$agrtyp = 'MAX'; #TODO check whether this makes sense with aggregation or remove from RRDs to preserve space!
	$agrtyp = 'AVERAGE';
	$inmod  = 'AREA';
	$outmod = 'LINE2';
	$n	= count($rrd);

	if($t == 'trf'){
		if ($_SESSION['gbit']){
			$tit = "$trflbl [Bit/s]";
		}else{
			$tit = "$trflbl [Byte/s]";
		}
	}elseif($t == 'err'){
		$idef = 'inerr';
		$odef = 'outerr';
		$tit = "$errlbl/s";
	}elseif($t == 'dsc'){
		$idef = 'indisc';
		$odef = 'outdisc';
		$tit = "Discards/s";
	}else{
		$idef = 'inbcast';
		$tit = "Broadcasts/s";
	}

	foreach (array_keys($rrd) as $i){
		$c++;
		$il = str_replace(":","\:",$i);
		if ($_SESSION['gbit'] and $t == 'trf'){
			$drawin .= "DEF:$idef$c=$rrd[$i]:$idef:$agrtyp ";
			$drawin .= "CDEF:b$idef$c=$idef$c,8,* $inmod:b$idef$c#". StackCol($t,$c,3) .":\"$il in ";
			$drawout .= "DEF:$odef$c=$rrd[$i]:$odef:$agrtyp ";
			$drawout .= "CDEF:b$odef$c=$odef$c,8,* $outmod:b$odef$c#". StackCol($t,$c,0) .":\"$il out";
		}else{
			$drawin .= "DEF:$idef$c=$rrd[$i]:$idef:$agrtyp $inmod:$idef$c#". StackCol($t,$c,3) .":\"$il in ";
			$drawout .= "DEF:$odef$c=$rrd[$i]:$odef:$agrtyp $outmod:$odef$c#". StackCol($t,$c,0) .":\"$il out";
		}
		if($c == $n){
			$drawout .= "\\l";
			$drawin .= "\\l";
		}
		$drawin .= "\" ";
		$drawout .= "\" ";

		if($debug){
			$drawout .= "\n\t";
			$drawin .= "\n\t";
		}
		$inmod = 'STACK';
		$outmod = 'STACK';
	}
	if ($t == 'brc'){
		return array($drawin,$tit);
	}else{
		return array($drawin.$drawout,$tit);
	}
}

#===================================================================
# Returns color of stacked graphs based on type, order and direction
# Parameters:	type, count, direction
# Global:	-
# Return:	color
#===================================================================
function StackCol($type,$ct,$dir){


	#if($ct > 8){$ct = 8;}
	if($type == 'trf'){
		if($dir){
			return sprintf("%x%x%x",$ct%3*5+3,$ct%4*2+9,$ct%5*3+3);
		}else{
			return sprintf("%x%x%x",$ct%3*5,$ct%4*2+6,$ct%5*3);
		}
	}elseif($type == 'err'){
		if($dir){
			return sprintf("%x%x%x",$ct%4*2+9,$ct%5*3+3,$ct%3*5+3);
		}else{
			return sprintf("%x%x%x",$ct%4*2+6,$ct%5*3,$ct%3*5);
		}
	}elseif($type == 'dsc'){
		if($dir){
			return sprintf("%x%x%x",$ct%4*2+9,$ct%3*5+3,$ct%5*3+3);
		}else{
			return sprintf("%x%x%x",$ct%4*2+6,$ct%3*5,$ct%5*3);
		}
	}else{
		return sprintf("%x%x%x",$ct%5*3+1,$ct%9,16-$ct%16);
	}
}

#===================================================================
# Defines graphs according to parameters
# Parameters:	size (1=tiny,2=small,3=med,4=large,5=x-large), start, end, tile, option (bw for tiny else canvas)
# Global:	-
# Return:	in/out graphs, title
#===================================================================
function GraphOpts($siz,$sta,$end,$tit,$opt){

	global $datfmt;

	if($siz == '1'){
		if($opt == 1){					# error graph
			return "-w50 -h30 -j -c CANVAS#eeccbb";
		}elseif($opt){					# >1 traffic graph
			return "-w50 -h30 -u$opt -j -c CANVAS#ccddee";
		}else{						# discardsm broadcast
			return "-w50 -h30 -j -c CANVAS#eeeeee";
		}
	}elseif($siz == '2'){
		$dur = (($sta)?"-s${sta}":"-s-1d").(($end)?" -e${end} ":"");
		return "-w80 -h52 -g $dur -L5";
	}elseif($siz == '3'){
		$dur = (($sta)?"-s${sta}":"-s-3d").(($end)?" -e${end} ":"");
		return "--title=\"$tit\" -g -w150 -h90 $dur -L6";
	}elseif($siz == '4'){
		$dur = (($sta)?"-s${sta}":"-s-5d").(($end)?" -e${end} ":"");
		return "--title=\"$tit\" -w250 -h100 $dur -L6";
	}else{
		$sta = ($sta)?$sta:('date' - 7 * 86400);
		$dur = "-s${sta}".(($end)?" -e${end} ":"");
		return "--title=\"$tit ". date($datfmt,$sta)."\" -w800 -h200 $dur -L6";
	}
}

#===================================================================
# Old test functions for the plotter
# Parameters:	-
# Global:	-
# Return:	-
#===================================================================
class Graph {

	function Graph($res){
		if   ($res ==  "svga"){$wd = "800"; $ht = "600";}
		elseif($res == "xga" ){$wd = "1024";$ht = "768";}
		elseif($res == "sxga"){$wd = "1280";$ht = "1024";}
		elseif($res == "uxga"){$wd = "1600";$ht = "1200";}
		else{$wd = "640";$ht = "480";}

		$this->img = imageCreate($wd, $ht);
		$this->wte = imageColorAllocate($this->img, 255, 255, 255);
		$this->blk = imageColorAllocate($this->img, 0, 0, 0);
		$this->gry = imageColorAllocate($this->img, 100, 100, 100);
		$this->red = imageColorAllocate($this->img, 150, 0, 0);
		$this->grn = imageColorAllocate($this->img, 0, 150, 0);
		$this->blu = imageColorAllocate($this->img, 0, 0, 150);

		imagestring($this->img, 2,5,5, $res, $this->blu);
	}

	function drawGrid() {
		$this->x0 = -$x1;
		$this->y0 = -$y1;
		$this->x1 = $x1;
		$this->y1 = $y1;
		$this->posX0 = $width/2;
		$this->posY0 = $height/2;
		$this->scale = (double)($width-20)/($this->x1-$this->x0);
		imageLine($this->img, $this->posX0 + $this->x0*$this->scale-2,
		$this->posY0,
		$this->posX0 + $this->x1*$this->scale+2,
		$this->posY0, $this->blk);
		imageLine($this->img, $this->posX0,
		$this->posY0 - $this->y0*$this->scale+2,
		$this->posX0,
		$this->posY0 - $this->y1*$this->scale-2, $this->blk);
		imagesetstyle($this->img, array($this->gry, $this->wte, $this->wte, $this->wte, $this->wte) );
		for ($x = 1; $x <= $this->x1; $x += 1) {
			imageline($this->img, $this->posX0+$x*$this->scale,0,$this->posX0+$x*$this->scale,$this->posY0 * 2, IMG_COLOR_STYLED);
			imageline($this->img, $this->posX0-$x*$this->scale,0,$this->posX0-$x*$this->scale,$this->posY0 * 2, IMG_COLOR_STYLED);

			imageLine($this->img, $this->posX0+$x*$this->scale,
			$this->posY0-3,
			$this->posX0+$x*$this->scale,
			$this->posY0+3, $this->blk);
			imageLine($this->img, $this->posX0-$x*$this->scale,
			$this->posY0-3,
			$this->posX0-$x*$this->scale,
			$this->posY0+3, $this->blk);
			imagestring($this->img, 2, $this->posX0+$x*$this->scale, $this->posY0+4, $x, $this->blu);
			imagestring($this->img, 2, $this->posX0-$x*$this->scale, $this->posY0+4, "-$x", $this->blu);
		}
		for ($y = 1; $y <= $this->y1; $y += 1) {
			imageline($this->img, 0, $this->posY0+$y*$this->scale,$this->posX0 * 2,$this->posY0+$y*$this->scale, IMG_COLOR_STYLED);
			imageline($this->img, 0, $this->posY0-$y*$this->scale,$this->posX0 * 2,$this->posY0-$y*$this->scale, IMG_COLOR_STYLED);

			imageLine($this->img, $this->posX0-3,
			$this->posY0-$y*$this->scale,
			$this->posX0+3,
			$this->posY0-$y*$this->scale, $this->blk);
			imageLine($this->img, $this->posX0-3,
			$this->posY0+$y*$this->scale,
			$this->posX0+3,
			$this->posY0+$y*$this->scale, $this->blk);
			imagestring($this->img, 2, $this->posX0+4, $this->posY0-$y*$this->scale, $y, $this->blu);
			imagestring($this->img, 2, $this->posX0+4, $this->posY0+$y*$this->scale, "-$y", $this->blu);
		}
	}

	function drawFunction($function, $dx = 0.1) {
		$xold = $x = $this->x0;
		eval("\$yold=".$function.";");
		for ($x += $dx; $x <= $this->x1; $x += $dx) {
			eval("\$y = ".$function.";");
			imageLine($this->img, $this->posX0+$xold*$this->scale,
			$this->posY0-$yold*$this->scale,
			$this->posX0+$x*$this->scale,
			$this->posY0-$y*$this->scale, $this->grn);
			$xold = $x;
			$yold = $y;
		}
	}

	function writePng() {
		imagePNG($this->img);
	}

	function destroyGraph() {
		imageDestroy($this->img);
	}

}
?>

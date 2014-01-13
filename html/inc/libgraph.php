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

	global $errlbl,$trflbl,$debug,$stalbl;

	$c	= 0;
	$drawin = '';
	$drawout= '';
	$outdir = '';
	$odef   = '';
	$idef   = '';
	$inmod  = 'AREA';
	$n	= count($rrd);

	if($t == 'trf'){
		$idef = 'inoct';
		$odef = 'outoct';
		$tit = ($_SESSION['gbit'])?"$trflbl [Bit/s]":"$trflbl [Byte/s]";
	}elseif($t == 'err'){
		$idef = 'inerr';
		$odef = 'outerr';
		$tit = "$errlbl/s";
	}elseif($t == 'dsc'){
		$idef = 'indisc';
		$odef = 'outdisc';
		$tit = 'Discards/s';
	}elseif($t == 'sta'){
		$tit = "IF $stalbl";
	}else{
		$idef = 'inbcast';
		$tit = 'Broadcasts/s';
	}
	if($_SESSION['gneg']){
		$outdir = '-';
		$outmod = 'AREA';
	}else{
		$outmod = 'LINE2';
	}

	foreach (array_keys($rrd) as $i){
		$c++;
		$eol = ($c == $n)?"\\l":"";
		$il = str_replace(":","\:",$i);
		if($_SESSION['gbit'] and $t == 'trf'){
			$drawin .= "DEF:inbyte$c=$rrd[$i]:$idef:AVERAGE ";
			$drawin .= "CDEF:$idef$c=inbyte$c,8,* $inmod:$idef$c#". StackCol($t,$c,3) .":\"$il in ";
			$drawout .= "DEF:outbyte$c=$rrd[$i]:$odef:AVERAGE ";
			$drawout .= "CDEF:$odef$c=outbyte$c,${outdir}8,* $outmod:$odef$c#". StackCol($t,$c,0) .":\"$il out";
		}elseif($t == 'sta'){
			$drawin .= "DEF:sta$c=$rrd[$i]:status:AVERAGE ";
			$drawin .= "CDEF:sh$c=sta$c,0,EQ $inmod:sh$c#cc8844: ";
			$drawin .= "CDEF:dn$c=sta$c,1,2,LIMIT,1,0,IF $inmod:dn$c#cccc44: ";#TODO remove $inmod and just multiply by $c to avoid stacking problem?
			$drawin .= "CDEF:up$c=sta$c,2,GT $inmod:up$c#44cc44: ";
			$drawin .= "CDEF:un$c=sta$c,UN $inmod:un$c#cccccc:\"$il ";
		}else{
			$drawin  .= "DEF:$idef$c=$rrd[$i]:$idef:AVERAGE $inmod:$idef$c#". StackCol($t,$c,3) .":\"$il in ";
			$drawout .= "DEF:outgr$c=$rrd[$i]:$odef:AVERAGE ";
			$drawout .= "CDEF:$odef$c=outgr$c,${outdir}1,* $outmod:$odef$c#". StackCol($t,$c,0) .":\"$il out";
		}

		if ($t == 'trf' and $n == 1 and !$_SESSION['gneg']){# Couldn't figure out yet, why 95% is incorrect on negative traffic??!?
			$drawin  .= "\" VDEF:tio95=$idef$c,95,PERCENT LINE1:tio95#ffcc44:\"95%\" GPRINT:tio95:\"%4.2lf%s\" ";
			$drawout .= "\" VDEF:too95=$odef$c,95,PERCENT LINE1:too95#ff4444:\"95%\" GPRINT:too95:\"%4.2lf%s\" ";
			$drawin  .= "GPRINT:$idef$c:MIN:\"Min\:%3.2lf%s\" GPRINT:$idef$c:AVERAGE:\"Avg\:%3.2lf%s\" GPRINT:$idef$c:MAX:\"Max\:%3.2lf%s ";
			$drawout .= "GPRINT:$odef$c:MIN:\"Min\:%3.2lf%s\" GPRINT:$odef$c:AVERAGE:\"Avg\:%3.2lf%s\" GPRINT:$odef$c:MAX:\"Max\:%3.2lf%s ";
		}

		$drawin  .= "$eol\" ";
		$drawout .= "$eol\" ";

		if($debug){
			$drawin  .= "\n\t";
			$drawout .= "\n\t";
		}
		$inmod  = 'STACK';
		$outmod = 'STACK';
	}

	if ($t == 'brc' or $t == 'sta'){
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
	}elseif($type == 'sta'){
		return sprintf("%x%x%x",$ct%9,$ct%4*2+9,16-$ct%16);
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
		}elseif($opt == 100){				# broadcast graph
			return "-w50 -h30 -u$opt -j -c CANVAS#dddddd";
		}elseif($opt){					# traffic graph
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

<?php

/*error_reporting(E_ALL);
ini_set("display_errors" , 1);*/

require('fpdf.php');
//require('htmlparser.inc');

function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['G']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter in 72 dpi
function px2mm($px){
	return $px*25.4/72;
}
 
function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
} 

class PDF extends FPDF{
	var $angle=0;
	var $extgstates;

	var $tmpFiles = array(); 

	/*******************************************************************************
	*                                                                              *
	*                               Public methods                                 *
	*                                                                              *
	*******************************************************************************/
	function Image($file,$x,$y,$w=0,$h=0,$type='',$link='', $isMask=false, $maskImg=0){
		//echo "images";exit;
		//Put an image on the page
		if(!isset($this->images[$file])){
			//First use of image, get info
			if($type == ''){
				$pos = strrpos($file,'.');
				if(!$pos)
					$this->Error('Image file has no extension and no type was specified: '.$file);
					
				$type	= substr($file,$pos+1);
			}

			$type		= strtolower($type);
			$mqr		= get_magic_quotes_runtime();
			@set_magic_quotes_runtime(0);
			if($type == 'jpg' || $type == 'jpeg')
				$info	= $this->_parsejpg($file);
			elseif($type == 'png'){
				$info	= $this->_parsepng($file);
				if ($info == 'alpha') 
					return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
			}else{
				//Allow for additional formats
				$mtd	= '_parse'.$type;
				if(!method_exists($this, $mtd))
					$this->Error('Unsupported image type: '.$type);
				$info	= $this->$mtd($file);
			}
			@set_magic_quotes_runtime($mqr);
			
			if ($isMask){
				$info['cs']	= "DeviceGray"; // try to force grayscale (instead of indexed)
			}
			$info['i']		= count($this->images)+1;
			if ($maskImg>0) 
				$info['masked']		= $maskImg;###
			$this->images[$file]	= $info;
		}else{
			$info=$this->images[$file];
		}
		//Automatic width and height calculation if needed
		if($w==0 && $h==0){
			//Put image at 72 dpi
			$w	= $info['w']/$this->k;
			$h	= $info['h']/$this->k;
		}
		if($w==0)
			$w	= $h*$info['w']/$info['h'];
		if($h==0)
			$h	= $w*$info['h']/$info['w'];
			
		if ($isMask) $x = $this->fwPt;// + 10; // embed hidden, ouside the canvas  
		$this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
		/*if($link)
			$this->Link($x,$y,$w,$h,$link);*/
		
		return $info['i'];
	}
	
function _parsejpg($file)
{
	//Extract info from a JPEG file
	$a=GetImageSize($file);
	if(!$a)
		$this->Error('Missing or incorrect image file: '.$file);
	if($a[2]!=2)
		$this->Error('Not a JPEG file: '.$file);
	if(!isset($a['channels']) || $a['channels']==3)
		$colspace='DeviceRGB';
	elseif($a['channels']==4)
		$colspace='DeviceCMYK';
	else
		$colspace='DeviceGray';
	$bpc=isset($a['bits']) ? $a['bits'] : 8;
	//Read whole file
	$f=fopen($file,'rb');
	$data='';
	while(!feof($f))
		$data.=fread($f,8192);
	fclose($f);
	return array('w'=>$a[0], 'h'=>$a[1], 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'DCTDecode', 'data'=>$data);
}	
	
function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
{
	$tmp_alpha = tempnam('.', 'mska');
	$this->tmpFiles[] = $tmp_alpha;
	$tmp_plain = tempnam('.', 'mskp');
	$this->tmpFiles[] = $tmp_plain;
	
	list($wpx, $hpx) = getimagesize($file);
	$img = @imagecreatefrompng($file);
	$alpha_img = imagecreate( $wpx, $hpx );
	
	// generate gray scale pallete
	for($c=0;$c<256;$c++) ImageColorAllocate($alpha_img, $c, $c, $c);
	
	// extract alpha channel
	$xpx=0;
	while ($xpx<$wpx){
		$ypx = 0;
		while ($ypx<$hpx){
			$color_index = @imagecolorat($img, $xpx, $ypx);
			$alpha = 255-($color_index>>24)*255/127; // GD alpha component: 7 bit only, 0..127!
			imagesetpixel($alpha_img, $xpx, $ypx, $alpha);
	    ++$ypx;
		}
		++$xpx;
	}

	imagepng($alpha_img, $tmp_alpha);
	imagedestroy($alpha_img);
	
	// extract image without alpha channel
	$plain_img = imagecreatetruecolor ( $wpx, $hpx );
	@imagecopy ($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
	imagepng($plain_img, $tmp_plain);
	imagedestroy($plain_img);
	
	//first embed mask image (w, h, x, will be ignored)
	$maskImg = $this->Image($tmp_alpha, -120,-120,1,1, 'PNG', '', true); 
	
	//embed image, masked with previously embedded mask
	$this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
}

	

	function Close()
	{
		parent::Close();
		// clean up tmp files
		foreach($this->tmpFiles as $tmp) @unlink($tmp);
	}

	/*******************************************************************************
	*                                                                              *
	*                               Private methods                                *
	*                                                                              *
	*******************************************************************************/
	function _putimages()
	{
		$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
		reset($this->images);
		while(list($file,$info)=each($this->images))
		{
			$this->_newobj();
			$this->images[$file]['n']=$this->n;
			$this->_out('<</Type /XObject');
			$this->_out('/Subtype /Image');
			$this->_out('/Width '.$info['w']);
			$this->_out('/Height '.$info['h']);
			
			if (isset($info["masked"])) $this->_out('/SMask '.($this->n-1).' 0 R'); ###
			
			if($info['cs']=='Indexed')
				$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
			else
			{
				$this->_out('/ColorSpace /'.$info['cs']);
				if($info['cs']=='DeviceCMYK')
					$this->_out('/Decode [1 0 1 0 1 0 1 0]');
			}
			$this->_out('/BitsPerComponent '.$info['bpc']);
			if(isset($info['f']))
				$this->_out('/Filter /'.$info['f']);
			if(isset($info['parms']))
				$this->_out($info['parms']);
			if(isset($info['trns']) && is_array($info['trns']))
			{
				$trns='';
				for($i=0;$i<count($info['trns']);$i++)
					$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
				$this->_out('/Mask ['.$trns.']');
			}
			$this->_out('/Length '.strlen($info['data']).'>>');
			$this->_putstream($info['data']);
			unset($this->images[$file]['data']);
			$this->_out('endobj');
			//Palette
			if($info['cs']=='Indexed')
			{
				$this->_newobj();
				$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
				$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
				$this->_putstream($pal);
				$this->_out('endobj');
			}
		}
	}

	// GD seems to use a different gamma, this method is used to correct it again
	function _gamma($v){
		return pow ($v/255, 2.2) * 255;
	}

	// this method overwriing the original version is only needed to make the Image method support PNGs with alpha channels.
	// if you only use the ImagePngWithAlpha method for such PNGs, you can remove it from this script.
	function _parsepng($file)
	{
		//Extract info from a PNG file
		$f=fopen($file,'rb');
		if(!$f)
			$this->Error('Can\'t open image file: '.$file);
		//Check signature
		if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
			$this->Error('Not a PNG file: '.$file);
		//Read header chunk
		fread($f,4);
		if(fread($f,4)!='IHDR')
			$this->Error('Incorrect PNG file: '.$file);
		$w=$this->_freadint($f);
		$h=$this->_freadint($f);
		$bpc=ord(fread($f,1));
		
		if($bpc>16)
			$this->Error('16-bit depth not supported: '.$file);
		$ct=ord(fread($f,1));
		if($ct==0)
			$colspace='DeviceGray';
		elseif($ct==2)
			$colspace='DeviceRGB';
		elseif($ct==3)
			$colspace='Indexed';
		else {
			fclose($f);      // the only changes are 
			return 'alpha';  // made in those 2 lines
		}
		if(ord(fread($f,1))!=0)
			$this->Error('Unknown compression method: '.$file);
		if(ord(fread($f,1))!=0)
			$this->Error('Unknown filter method: '.$file);
		if(ord(fread($f,1))!=0)
			$this->Error('Interlacing not supported: '.$file);
		fread($f,4);
		$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
		//Scan chunks looking for palette, transparency and image data
		$pal='';
		$trns='';
		$data='';
		do
		{
			$n=$this->_freadint($f);
			$type=fread($f,4);
			if($type=='PLTE')
			{
				//Read palette
				$pal=fread($f,$n);
				fread($f,4);
			}
			elseif($type=='tRNS')
			{
				//Read transparency info
				$t=fread($f,$n);
				if($ct==0)
					$trns=array(ord(substr($t,1,1)));
				elseif($ct==2)
					$trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
				else
				{
					$pos=strpos($t,chr(0));
					if($pos!==false)
						$trns=array($pos);
				}
				fread($f,4);
			}
			elseif($type=='IDAT')
			{
				//Read image data block
				$data.=fread($f,$n);
				fread($f,4);
			}
			elseif($type=='IEND')
				break;
			else
				fread($f,$n+4);
		}
		while($n);
		if($colspace=='Indexed' && empty($pal))
			$this->Error('Missing palette in '.$file);
		fclose($f);
		return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
	}



	function Rotate($angle,$x=-1,$y=-1){
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0){
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
			$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}

	function _endpage(){
		if($this->angle!=0)	{
			$this->angle=0;
			$this->_out('Q');
		}
		parent::_endpage();
	}

	function RotatedText($txt,$x,$y,$angle){
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}

function RotatedHTMLText($txt,$x,$y,$angle)
	{
	$y	= $y;//($y-4);
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->SetLeftMargin($x); // -5
	$this->SetXY($x,$y);
	$this->WriteHTML($txt);
	$this->Rotate(0);
	}

function RotatedImage($file,$x,$y,$w,$h,$angle)	{
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->Image($file,$x,$y,$w,$h);
		//$this->ImagePngWithAlpha($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}
function RotatedPNG($file,$x,$y,$w,$h,$angle)	{
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		//$this->Image($file,$x,$y,$w,$h);
		$this->ImagePngWithAlpha($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}

function RotatedEps($file,$x,$y,$w,$h,$angle){
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->ImageEps($file, $x,$y,$w,$h);
		$this->Rotate(0);
	}

function RotatedEpsColorable($file , $x , $y , $w , $h , $angle , $imageColor)
	{
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->ImageEpsColorable($file, $x, $y, $w, $h, $link='', true , $imageColor);
		$this->Rotate(0);
	}

function RotatedShape($x,$y,$w,$h,$angle,$style='DF'){	
		$this->Rotate($angle,$x,$y);
		$this->Ellipse($x,$y,$w,$h,$style);
		$this->Rotate(0);
	}

function RotateRoundRect($x, $y, $w, $h, $corRadius , $angle, $style='DF'){
		$this->Rotate($angle,$x,$y);
		$this->RoundedRect($x , $y , $w , $h , $corRadius , $style);
		$this->Rotate(0);
	}

function RotateRect($x, $y, $w, $h, $angle , $style='DF'){
		$this->Rotate($angle,$x,$y);
		$this->Rect($x , $y , $w , $h , $style);
		$this->Rotate(0);
	}



	function imagecopymergealpha(&$dst_im, &$src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct = 0) {
        $dst_x = (int) $dst_x;
        $dst_y = (int) $dst_y;
        $src_x = (int) $src_x;
        $src_y = (int) $src_y;
        $src_w = (int) $src_w;
        $src_h = (int) $src_h;
        $pct   = (int) $pct;
        $dst_w = imagesx($dst_im);
        $dst_h = imagesy($dst_im);

        for ($y = $src_y; $y < $src_h; $y++) {
            for ($x = $src_x; $x < $src_w; $x++) {

                if ($x >= 0 && $x <= $dst_w && $y >= 0 && $y <= $dst_h) {
                    $dst_pixel = imagecolorsforindex($dst_im, imagecolorat($dst_im, $x + $dst_x, $y + $dst_y));
                    $src_pixel = imagecolorsforindex($src_im, imagecolorat($src_im, $x + $src_x, $y + $src_y));

                    $src_alpha = 1 - ($src_pixel['alpha'] / 127);
                    $dst_alpha = 1 - ($dst_pixel['alpha'] / 127);
                    $opacity = $src_alpha * $pct / 100;
                    if ($dst_alpha >= $opacity) $alpha = $dst_alpha;
                    if ($dst_alpha < $opacity)  $alpha = $opacity;              
                    if ($alpha > 1) $alpha = 1;                   

                    if ($opacity > 0) {
                        $dst_red   = round(( ($dst_pixel['red']   * $dst_alpha * (1 - $opacity)) ) );
                        $dst_green = round(( ($dst_pixel['green'] * $dst_alpha * (1 - $opacity)) ) );
                        $dst_blue  = round(( ($dst_pixel['blue']  * $dst_alpha * (1 - $opacity)) ) );
                        $src_red   = round((($src_pixel['red']   * $opacity)) );
                        $src_green = round((($src_pixel['green'] * $opacity)) );
                        $src_blue  = round((($src_pixel['blue']  * $opacity)) );
                        $red   = round(($dst_red   + $src_red  ) / ($dst_alpha * (1 - $opacity) + $opacity));
                        $green = round(($dst_green + $src_green) / ($dst_alpha * (1 - $opacity) + $opacity));
                        $blue  = round(($dst_blue  + $src_blue ) / ($dst_alpha * (1 - $opacity) + $opacity));
                        if ($red   > 255) $red   = 255;  
                        if ($green > 255) $green = 255;  
                        if ($blue  > 255) $blue  = 255;  
                        $alpha =  round((1 - $alpha) * 127);
                        $color = imagecolorallocatealpha($dst_im, $red, $green, $blue, $alpha);
                        imagesetpixel($dst_im, $x + $dst_x, $y + $dst_y, $color);
                    }
                }
            }
        }
        return true;
    }




//////////////////////////////////////
//html parser

function WriteHTML($html)
{
	$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
	$html=str_replace("\n",'',$html); //replace carriage returns by spaces
	$html=str_replace("\t",'',$html); //replace carriage returns by spaces
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //explodes the string
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			elseif($this->tdbegin) {
				if(trim($e)!='' and $e!="&nbsp;") {
					$this->Cell($this->tdwidth,$this->tdheight,$e,$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
				}
				elseif($e=="&nbsp;") {
					$this->Cell($this->tdwidth,$this->tdheight,'',$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
				}
			}
			else
				$this->Write(5,stripslashes(txtentities($e)));
		}
		else
		{
			//Tag
			if($e{0}=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
					if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag,$attr)
{
	//Opening tag
	switch($tag){

		case 'SUP':
			if($attr['SUP'] != '') {	
				//Set current font to: Bold, 6pt 	
				$this->SetFont('','',6);
				//Start 125cm plus width of cell to the right of left margin 		
				//Superscript "1" 
				$this->Cell(2,2,$attr['SUP'],0,0,'L');
			}
			break;

		case 'TABLE': // TABLE-BEGIN
			if( $attr['BORDER'] != '' ) $this->tableborder=$attr['BORDER'];
			else $this->tableborder=0;
			break;
		case 'TR': //TR-BEGIN
			break;
		case 'TD': // TD-BEGIN
			if( $attr['WIDTH'] != '' ) $this->tdwidth=($attr['WIDTH']/4);
			else $this->tdwidth=30; // SET to your own width if you need bigger fixed cells
			if( $attr['HEIGHT'] != '') $this->tdheight=($attr['HEIGHT']/6);
			else $this->tdheight=6; // SET to your own height if you need bigger fixed cells
			if( $attr['ALIGN'] != '' ) {
				$align=$attr['ALIGN'];		
				if($align=="LEFT") $this->tdalign="L";
				if($align=="CENTER") $this->tdalign="C";
				if($align=="RIGHT") $this->tdalign="R";
			}
			else $this->tdalign="L"; // SET to your own
			if( $attr['BGCOLOR'] != '' ) {
				$coul=hex2dec($attr['BGCOLOR']);
					$this->SetFillColor($coul['R'],$coul['G'],$coul['B']);
					$this->tdbgcolor=true;
				}
			$this->tdbegin=true;
			break;

		case 'HR':
			if( $attr['WIDTH'] != '' )
				$Width = $attr['WIDTH'];
			else
				$Width = $this->w - $this->lMargin-$this->rMargin;
			$x = $this->GetX();
			$y = $this->GetY();
			$this->SetLineWidth(0.2);
			$this->Line($x,$y,$x+$Width,$y);
			$this->SetLineWidth(0.2);
			$this->Ln(1);
			break;
		case 'STRONG':
			$this->SetStyle('B',true);
			break;
		case 'EM':
			$this->SetStyle('I',true);
			break;
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) and (isset($attr['WIDTH']) or isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
			}
			break;
		//case 'TR':
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(5);
			break;
		case 'P':
			$this->Ln(10);
			break;
		case 'FONT':
			if (isset($attr['COLOR']) and $attr['COLOR']!='') {
				$coul=hex2dec($attr['COLOR']);
				$this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
				$this->issetcolor=true;
			}
			if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist)) {
				$this->SetFont(strtolower($attr['FACE']));
				$this->issetfont=true;
			}
			if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist) and isset($attr['SIZE']) and $attr['SIZE']!='') {
				$this->SetFont(strtolower($attr['FACE']),'',$attr['SIZE']);
				$this->issetfont=true;
			}
			break;
	}
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='SUP') {
	}

	if($tag=='TD') { // TD-END
		$this->tdbegin=false;
		$this->tdwidth=0;
		$this->tdheight=0;
		$this->tdalign="C";
		$this->tdbgcolor=false;
	}
	if($tag=='TR') { // TR-END
		$this->Ln();
	}
	if($tag=='TABLE') { // TABLE-END
		//$this->Ln();
		$this->tableborder=0;
	}

	if($tag=='STRONG')
		$tag='B';
	if($tag=='EM')
		$tag='I';
	if($tag=='B' or $tag=='I' or $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='FONT'){
		if ($this->issetcolor==true) {
			$this->SetTextColor(0);
		}
		if ($this->issetfont) {
			$this->SetFont('arial');
			$this->issetfont=false;
		}
	}
}

function SetStyle($tag,$enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
		if($this->$s>0)
			$style.=$s;
	$this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}


/* Modify style and select corresponding font*/

  function mySetTextColor($r,$g=0,$b=0){
        static $_r=0, $_g=0, $_b=0;

        if ($r==-1)
            $this->SetTextColor($_r,$_g,$_b);
        else {
            $this->SetTextColor($r,$g,$b);
            $_r=$r;
            $_g=$g;
            $_b=$b;
        }
    }
 
 function CellFit($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='',$scale=0,$force=1)
	{
		//Get string width
		$str_width=$this->GetStringWidth($txt);

		//Calculate ratio to fit cell
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$ratio=($w-$this->cMargin*2)/$str_width;

		$fit=($ratio < 1 || ($ratio > 1 && $force == 1));
		if ($fit)
		{
			switch ($scale)
			{

				//Character spacing
				case 0:
					//Calculate character spacing in points
					$char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
					//Set character spacing
					$this->_out(sprintf('BT %.2f Tc ET',$char_space));
					break;

				//Horizontal scaling
				case 1:
					//Calculate horizontal scaling
					$horiz_scale=$ratio*100.0;
					//Set horizontal scaling
					$this->_out(sprintf('BT %.2f Tz ET',$horiz_scale));
					break;

			}
			//Override user alignment (since text will fill up cell)
			$align='';
		}

		//Pass on to Cell method
		$this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

		//Reset character spacing/horizontal scaling
		if ($fit)
			$this->_out('BT '.($scale==0 ? '0 Tc' : '100 Tz').' ET');
	}

	//Cell with horizontal scaling only if necessary
	function CellFitScale($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,1,0);
	}

	//Cell with horizontal scaling always
	function CellFitScaleForce($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,1,1);
	}

	//Cell with character spacing only if necessary
	function CellFitSpace($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,0,0);
	}

	//Cell with character spacing always
	function CellFitSpaceForce($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
	{
		//Same as calling CellFit directly
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,0,1);
	}

	//Patch to also work with CJK double-byte text
	function MBGetStringLength($s)
	{
		if($this->CurrentFont['type']=='Type0')
		{
			$len = 0;
			$nbbytes = strlen($s);
			for ($i = 0; $i < $nbbytes; $i++)
			{
				if (ord($s[$i])<128)
					$len++;
				else
				{
					$len++;
					$i++;
				}
			}
			return $len;
		}
		else
			return strlen($s);
	}

	function Ellipse($x,$y,$rx,$ry,$style='D')
		{
			if($style=='F')
				$op='f';
			elseif($style=='FD' or $style=='DF')
				$op='B';
			else
				$op='S';
			$lx=4/3*(M_SQRT2-1)*$rx;
			$ly=4/3*(M_SQRT2-1)*$ry;
			$k=$this->k;
			$h=$this->h;
			$this->_out(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
				($x+$rx)*$k,($h-$y)*$k,
				($x+$rx)*$k,($h-($y-$ly))*$k,
				($x+$lx)*$k,($h-($y-$ry))*$k,
				$x*$k,($h-($y-$ry))*$k));
			$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
				($x-$lx)*$k,($h-($y-$ry))*$k,
				($x-$rx)*$k,($h-($y-$ly))*$k,
				($x-$rx)*$k,($h-$y)*$k));
			$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
				($x-$rx)*$k,($h-($y+$ly))*$k,
				($x-$lx)*$k,($h-($y+$ry))*$k,
				$x*$k,($h-($y+$ry))*$k));
			$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s',
				($x+$lx)*$k,($h-($y+$ry))*$k,
				($x+$rx)*$k,($h-($y+$ly))*$k,
				($x+$rx)*$k,($h-$y)*$k,
				$op));
		}

	function RoundedRect($x, $y, $w, $h, $r, $style = 'DF')
		{
			$k = $this->k;
			$hp = $this->h;
			if($style=='F')
				$op='f';
			elseif($style=='FD' or $style=='DF')
				$op='B';
			else
				$op='S';
				
			$MyArc = 4/3 * (sqrt(2) - 1);
			
			$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
			$xc = $x+$w-$r;
			$yc = $y+$r;
			$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
	
			$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
			$xc = $x+$w-$r ;
			$yc = $y+$h-$r;
			$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
			$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
			$xc = $x+$r ;
			$yc = $y+$h-$r;
			$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
			$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
			$xc = $x+$r ;
			$yc = $y+$r;
			$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
			$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
			$this->_out($op);
		}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
		{
			$h = $this->h;
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
				$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
		}
	
	
	function ClippingText($x,$y,$txt,$outline=false)
	{
		$op=$outline ? 5 : 7;
		$this->_out(sprintf('q BT %.2f %.2f Td %d Tr (%s) Tj ET',
			$x*$this->k,
			($this->h-$y)*$this->k,
			$op,
			$this->_escape($txt)));
	}

	function ClippingRect($x,$y,$w,$h,$outline=false)
	{
		$op=$outline ? 'S' : 'n';
		$this->_out(sprintf('q %.2f %.2f %.2f %.2f re W %s',
			$x*$this->k,
			($this->h-$y)*$this->k,
			$w*$this->k,-$h*$this->k,
			$op));
	}

	function ClippingRoundedRect($x, $y, $w, $h, $r, $outline=false)
	{
		$k = $this->k;
		$hp = $this->h;
		$op=$outline ? 'S' : 'n';
		$MyArc = 4/3 * (sqrt(2) - 1);

		$this->_out(sprintf('q %.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
		$xc = $x+$w-$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

		$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
		$xc = $x+$w-$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
		$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
		$xc = $x+$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
		$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
		$xc = $x+$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
		$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out(' W '.$op);
	}

	function ClippingEllipse($x,$y,$rx,$ry,$outline=false)
	{
		$op=$outline ? 'S' : 'n';
		$lx=4/3*(M_SQRT2-1)*$rx;
		$ly=4/3*(M_SQRT2-1)*$ry;
		$k=$this->k;
		$h=$this->h;
		$this->_out(sprintf('q %.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
			($x+$rx)*$k,($h-$y)*$k,
			($x+$rx)*$k,($h-($y-$ly))*$k,
			($x+$lx)*$k,($h-($y-$ry))*$k,
			$x*$k,($h-($y-$ry))*$k));
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			($x-$lx)*$k,($h-($y-$ry))*$k,
			($x-$rx)*$k,($h-($y-$ly))*$k,
			($x-$rx)*$k,($h-$y)*$k));
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			($x-$rx)*$k,($h-($y+$ly))*$k,
			($x-$lx)*$k,($h-($y+$ry))*$k,
			$x*$k,($h-($y+$ry))*$k));
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c W %s',
			($x+$lx)*$k,($h-($y+$ry))*$k,
			($x+$rx)*$k,($h-($y+$ly))*$k,
			($x+$rx)*$k,($h-$y)*$k,
			$op));
	}

	function ClippingCircle($x,$y,$r,$outline=false)
	{
		$this->ClippingEllipse($x,$y,$r,$r,$outline);
	}

	function ClippingPolygon($points,$outline=false)
	{
		$op=$outline ? 'S' : 'n';
		$h = $this->h;
		$k = $this->k;
		$points_string = '';
		for($i=0; $i<count($points); $i+=2){
			$points_string .= sprintf('%.2f %.2f', $points[$i]*$k, ($h-$points[$i+1])*$k);
			if($i==0)
				$points_string .= ' m ';
			else
				$points_string .= ' l ';
		}
		$this->_out('q '.$points_string . 'h W '.$op);
	}

	function UnsetClipping()
	{
		$this->_out('Q');
	}

	function ClippedCell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
	{
		if($border || $fill || $this->y+$h>$this->PageBreakTrigger)
		{
			$this->Cell($w,$h,'',$border,0,'',$fill);
			$this->x-=$w;
		}
		$this->ClippingRect($this->x,$this->y,$w,$h);
		$this->Cell($w,$h,$txt,'',$ln,$align,0,$link);
		$this->UnsetClipping();
	}
	function StartTransform(){
        //save the current graphic state
        $this->_out('q');
    }

    function ScaleX($s_x, $x='', $y=''){
        $this->Scale($s_x, 100, $x, $y);
    }
    function ScaleY($s_y, $x='', $y=''){
        $this->Scale(100, $s_y, $x, $y);
    }
    function ScaleXY($s, $x='', $y=''){
        $this->Scale($s, $s, $x, $y);
    }
    function Scale($s_x, $s_y, $x='', $y=''){
        if($x === '')
            $x=$this->x;
        if($y === '')
            $y=$this->y;
        if($s_x == 0 || $s_y == 0)
            $this->Error('Please use values unequal to zero for Scaling');
        $y=($this->h-$y)*$this->k;
        $x*=$this->k;
        //calculate elements of transformation matrix
        $s_x/=100;
        $s_y/=100;
        $tm[0]=$s_x;
        $tm[1]=0;
        $tm[2]=0;
        $tm[3]=$s_y;
        $tm[4]=$x*(1-$s_x);
        $tm[5]=$y*(1-$s_y);
        //scale the coordinate system
        $this->Transform($tm);
    }

    function MirrorH($x=''){
        $this->Scale(-100, 100, $x);
    }
    function MirrorV($y=''){
        $this->Scale(100, -100, '', $y);
    }
    function MirrorP($x='',$y=''){
        $this->Scale(-100, -100, $x, $y);
    }
    function MirrorL($angle=0, $x='',$y=''){
        $this->Scale(-100, 100, $x, $y);
        $this->Rotate(-2*($angle-90),$x,$y);
    }

    function TranslateX($t_x){
        $this->Translate($t_x, 0, $x, $y);
    }
    function TranslateY($t_y){
        $this->Translate(0, $t_y, $x, $y);
    }
    function Translate($t_x, $t_y){
        //calculate elements of transformation matrix
        $tm[0]=1;
        $tm[1]=0;
        $tm[2]=0;
        $tm[3]=1;
        $tm[4]=$t_x*$this->k;
        $tm[5]=-$t_y*$this->k;
        //translate the coordinate system
        $this->Transform($tm);
    }

    function RotateTransform($angle, $x='', $y=''){
        if($x === '')
            $x=$this->x;
        if($y === '')
            $y=$this->y;
        $y=($this->h-$y)*$this->k;
        $x*=$this->k;
        //calculate elements of transformation matrix
        $tm[0]=cos(deg2rad($angle));
        $tm[1]=sin(deg2rad($angle));
        $tm[2]=-$tm[1];
        $tm[3]=$tm[0];
        $tm[4]=$x+$tm[1]*$y-$tm[0]*$x;
        $tm[5]=$y-$tm[0]*$y-$tm[1]*$x;
        //rotate the coordinate system around ($x,$y)
        $this->Transform($tm);
    }

    function SkewX($angle_x, $x='', $y=''){
        $this->Skew($angle_x, 0, $x, $y);
    }
    function SkewY($angle_y, $x='', $y=''){
        $this->Skew(0, $angle_y, $x, $y);
    }
    function Skew($angle_x, $angle_y, $x='', $y=''){
        if($x === '')
            $x=$this->x;
        if($y === '')
            $y=$this->y;
        if($angle_x <= -90 || $angle_x >= 90 || $angle_y <= -90 || $angle_y >= 90)
            $this->Error('Please use values between -90� and 90� for skewing');
        $x*=$this->k;
        $y=($this->h-$y)*$this->k;
        //calculate elements of transformation matrix
        $tm[0]=1;
        $tm[1]=tan(deg2rad($angle_y));
        $tm[2]=tan(deg2rad($angle_x));
        $tm[3]=1;
        $tm[4]=-$tm[2]*$y;
        $tm[5]=-$tm[1]*$x;
        //skew the coordinate system
        $this->Transform($tm);
    }

    function Transform($tm){
        $this->_out(sprintf('%.3f %.3f %.3f %.3f %.3f %.3f cm', $tm[0],$tm[1],$tm[2],$tm[3],$tm[4],$tm[5]));
    }

    function StopTransform(){
        //restore previous graphic state
        $this->_out('Q');
    }

	/********END TRANSFORM***************/
	
	
	 function AlphaPDF($orientation='P',$unit='mm',$format='A4')
    {
        parent::FPDF($orientation, $unit, $format);
        $this->extgstates = array();
    }

    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            foreach ($this->extgstates[$i]['parms'] as $k=>$v)
                $this->_out('/'.$k.' '.$v);
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        //foreach($this->extgstates as $k=>$extgstate)
          //  $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_out('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }

	function ImageEps ($file, $x, $y, $w=0, $h=0, $link='', $useBoundingBox=true){
	
	$data = file_get_contents($file);
	if ($data===false) $this->Error('EPS file not found: '.$file);
	
	$regs = array();
	
	# EPS/AI compatibility check (only checks files created by Adobe Illustrator!)
	
	preg_match ('/%%Creator:([^\r\n]+)/', $data, $regs); # find Creator
	
	if (count($regs)>1){
		$version_str = trim($regs[1]); # e.g. "Adobe Illustrator(R) 8.0"
		$version_str = "3.2";
		if (strpos($version_str, 'Adobe Illustrator')!==false) {
			$version = (float) array_pop( explode(' ', $version_str) );
	
			if ($version>=9)
				$this->Error('File was saved with wrong Illustrator version: '.$file);
				#return false; # wrong version, only 1.x, 3.x or 8.x are supported
		}#else
		#$this->Error('EPS wasn\'t created with Illustrator: '.$file);
	}
	
	# strip binary bytes in front of PS-header
	$start = strpos($data, '%!PS-Adobe');
	if ($start>0) $data = substr($data, $start);
	
	# find BoundingBox params
	preg_match ("/%%BoundingBox:([^\r\n]+)/", $data, $regs);
	if (count($regs)>1){
		list($x1,$y1,$x2,$y2) = explode(' ', trim($regs[1]));
	}
	//else $this->Error('No BoundingBox found in EPS file: '.$file);
	
	$start = strpos($data, '%%EndSetup');
	if ($start===false) $start = strpos($data, '%%EndProlog');
	if ($start===false) $start = strpos($data, '%%BoundingBox');
	
	$data = substr($data, $start);
	
	$end = strpos($data, '%%PageTrailer');
	if ($end===false) $end = strpos($data, 'showpage');
	if ($end) $data = substr($data, 0, $end);
	
	# save the current graphic state
	$this->_out('q');
	
	$k = $this->k;
	
	if ($useBoundingBox){
		$dx = $x*$k-$x1;
		$dy = $y*$k-$y1;
	}else{
		$dx = $x*$k;
		$dy = $y*$k;
	}
	
	# translate
	$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', 1,0,0,1,$dx,$dy+($this->hPt - 2*$y*$k - ($y2-$y1))));
	
	if ($w>0){
		$scale_x = $w/(($x2-$x1)/$k);
		if ($h>0){
			$scale_y = $h/(($y2-$y1)/$k);
		}else{
			$scale_y = $scale_x;
			$h = ($y2-$y1)/$k * $scale_y;
		}
	}else{
		if ($h>0){
			$scale_y = $h/(($y2-$y1)/$k);
			$scale_x = $scale_y;
			$w = ($x2-$x1)/$k * $scale_x;
		}else{
			$w = ($x2-$x1)/$k;
			$h = ($y2-$y1)/$k;
		}
	}
	
	# scale
	if (isset($scale_x))
		$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $scale_x,0,0,$scale_y, $x1*(1-$scale_x), $y2*(1-$scale_y)));
	
	# handle pc/unix/mac line endings
	$lines =  preg_split("\r\n[\r\n]", $data);
	
	$u=0;
	$cnt = count($lines);
	for ($i=0;$i<$cnt;$i++){
		$line = $lines[$i];
		if ($line=='' || $line{0}=='%') continue;
	
		$len = strlen($line);
		$chunks = explode(' ', $line);
		$cmd = array_pop($chunks);
		
		# RGB
		if ($cmd=='Xa'||$cmd=='XA'){
			$r = array_pop($chunks);
			$g = array_pop($chunks);
			$b = array_pop($chunks);
			
			$this->_out("$r $g $b ". ($cmd=='Xa'?'rg':'RG') ); #substr($line, 0, -2).'rg' -> in EPS (AI8): c m y k r g b rg!
			continue;
		}
		
		switch ($cmd){
			case 'm':
			case 'l':
			case 'v':
			case 'y':
			case 'c':
	
			case 'k':
			case 'K':
			case 'g':
			case 'G':
	
			case 's':
			case 'S':
	
			case 'J':
			case 'j':
			case 'w':
			case 'M':
			case 'd' :
	
			case 'n' :
			case 'v' :
				$this->_out($line);
				break;
	
			case 'x': # custom fill color
				list($c,$m,$y,$k) = $chunks;
				$this->_out("$c $m $y $k k");
				break;
	
			case 'X': # custom stroke color
				list($c,$m,$y,$k) = $chunks;
				$this->_out("$c $m $y $k K");
				break;
	
			case 'Y':
			case 'N':
			case 'V':
			case 'L':
			case 'C':
				$line{$len-1}=strtolower($cmd);
				$this->_out($line);
				//$this->_out(12 , 12 , 12);
				break;
	
			case 'b':
			case 'B':
				$this->_out($cmd . '*');
				break;
	
			case 'f':
			case 'F':
				if ($u>0){
					$isU = false;
					$max = min($i+5,$cnt);
					for ($j=$i+1;$j<$max;$j++)
						$isU = ($isU || ($lines[$j]=='U' || $lines[$j]=='*U'));
					if ($isU) $this->_out("f*");
				}else
					$this->_out("f*");
				break;
	
			case '*u':
				$u++;
				break;
	
			case '*U':
				$u--;
				break;
	
			#default: echo "$cmd<br>"; #just for debugging
		}
		
	}
	
	# restore previous graphic state
	
	$this->_out('Q');
	if ($link)
		$this->Link($x,$y,$w,$h,$link);
	
	return true;
	}
	
	function ImageEpsColorable ($file, $x, $y, $w=0, $h=0, $link='', $useBoundingBox=true , $imageColor){	
		$data = file_get_contents($file);
		if ($data===false) $this->Error('EPS file not found: '.$file);
		
		$regs = array();
		
		# EPS/AI compatibility check (only checks files created by Adobe Illustrator!)
		
		preg_match ('/%%Creator:([^\r\n]+)/', $data, $regs); # find Creator
		
		if (count($regs)>1){
			$version_str = trim($regs[1]); # e.g. "Adobe Illustrator(R) 8.0"
			$version_str = "3.2";
			if (strpos($version_str, 'Adobe Illustrator')!==false) {
				$version = (float) array_pop( explode(' ', $version_str) );
		
				if ($version>=9)
					$this->Error('File was saved with wrong Illustrator version: '.$file);
					#return false; # wrong version, only 1.x, 3.x or 8.x are supported
			}#else
			#$this->Error('EPS wasn\'t created with Illustrator: '.$file);
		}
		
		# strip binary bytes in front of PS-header
		$start = strpos($data, '%!PS-Adobe');
		if ($start>0) $data = substr($data, $start);
		
		# find BoundingBox params
		preg_match ("/%%BoundingBox:([^\r\n]+)/", $data, $regs);
		if (count($regs)>1){
			list($x1,$y1,$x2,$y2) = explode(' ', trim($regs[1]));
		}
		//else $this->Error('No BoundingBox found in EPS file: '.$file);
		
		$start = strpos($data, '%%EndSetup');
		if ($start===false) $start = strpos($data, '%%EndProlog');
		if ($start===false) $start = strpos($data, '%%BoundingBox');
		
		$data = substr($data, $start);
		
		$end = strpos($data, '%%PageTrailer');
		if ($end===false) $end = strpos($data, 'showpage');
		if ($end) $data = substr($data, 0, $end);
		
		# save the current graphic state
		$this->_out('q');
		
		$k = $this->k;
		
		if ($useBoundingBox){
			$dx = $x*$k-$x1;
			$dy = $y*$k-$y1;
		}else{
			$dx = $x*$k;
			$dy = $y*$k;
		}
		
		# translate
		$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', 1,0,0,1,$dx,$dy+($this->hPt - 2*$y*$k - ($y2-$y1))));
		
		if ($w>0){
			$scale_x = $w/(($x2-$x1)/$k);
			if ($h>0){
				$scale_y = $h/(($y2-$y1)/$k);
			}else{
				$scale_y = $scale_x;
				$h = ($y2-$y1)/$k * $scale_y;
			}
		}else{
			if ($h>0){
				$scale_y = $h/(($y2-$y1)/$k);
				$scale_x = $scale_y;
				$w = ($x2-$x1)/$k * $scale_x;
			}else{
				$w = ($x2-$x1)/$k;
				$h = ($y2-$y1)/$k;
			}
		}
		
		# scale
		if (isset($scale_x))
			$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $scale_x,0,0,$scale_y, $x1*(1-$scale_x), $y2*(1-$scale_y)));
		
		# handle pc/unix/mac line endings
		$lines =  preg_split("\r\n[\r\n]", $data);
		
		$u=0;
		$cnt = count($lines);
		for ($i=0;$i<$cnt;$i++){
			$line = $lines[$i];
			if ($line=='' || $line{0}=='%') continue;
		
			$len = strlen($line);
			$chunks = explode(' ', $line);
			$cmd = array_pop($chunks);
			# RGB
			if ($cmd=='Xa'||$cmd=='XA' ||$cmd=='XR'){
				$r = array_pop($chunks);
				$g = array_pop($chunks);
				$b = array_pop($chunks);
				
				$txtColor	= $imageColor;//$value->color;
				$red		= hexdec(substr($txtColor, 1, 2));
				$green		= hexdec(substr($txtColor, 3, 2));
				$blue		= hexdec(substr($txtColor, 5, 2));
				
				$r			= ($red/255);
				$g			= ($green/255);
				$b			= ($blue/255);
				
				$this->_out("$r $g $b ". (($cmd=='Xa'||$cmd=='Xr') ?'rg':'RG') ); #substr($line, 0, -2).'rg' -> in EPS (AI8): c m y k r g b rg!
				continue;
			}
			
			switch ($cmd){
				case 'm':
				case 'l':
				case 'v':
				case 'y':
				case 'c':
		
				case 'k':
				case 'K':
				case 'g':
				case 'G':
		
				case 's':
				case 'S':
		
				case 'J':
				case 'j':
				case 'w':
				case 'M':
				case 'd' :
		
				case 'n' :
				case 'v' :
					$this->_out($line);
					break;
		
				case 'x': # custom fill color
					//list($c,$m,$y,$k) = $chunks;
					//$this->_out("$c $m $y $k k");
					break;
		
				case 'X': # custom stroke color
					//list($c,$m,$y,$k) = $chunks;
					//$this->_out("$c $m $y $k K");
					break;
		
				case 'Y':
				case 'N':
				case 'V':
				case 'L':
				case 'C':
					$line{$len-1}=strtolower($cmd);
					$this->_out($line);
					//$this->_out(12 , 12 , 12);
					break;
		
				case 'b':
				case 'B':
					$this->_out($cmd . '*');
					break;
		
				case 'f':
				case 'F':
					if ($u>0){
						$isU = false;
						$max = min($i+5,$cnt);
						for ($j=$i+1;$j<$max;$j++)
							$isU = ($isU || ($lines[$j]=='U' || $lines[$j]=='*U'));
						if ($isU) $this->_out("f*");
					}else
						$this->_out("f*");
					break;
		
				case '*u':
					$u++;
					break;
		
				case '*U':
					$u--;
					break;
		
				#default: echo "$cmd<br>"; #just for debugging
			}
			
		}
		
		# restore previous graphic state
		
		$this->_out('Q');
		if ($link)
			$this->Link($x,$y,$w,$h,$link);
		
		return true;
		}
		
		
		/*---------------- -------------------*/
		
		function LoadData($file)
{
	//Read file lines
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
}

//Simple table
function BasicTable($header,$data)
{
	//Header
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	//Data
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

//Better table
function ImprovedTable($header,$data)
{
	//Column widths
	$w=array(40,35,40,45);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR');
		$this->Cell($w[1],6,$row[1],'LR');
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		$this->Ln();
	}
	//Closure line
	$this->Cell(array_sum($w),0,'','T');
}

//Colored table
function FancyTable($header,$data)
{
	//Colors, line width and bold font
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.5);
	$this->SetFont('Times','B',20);
	//Header
		$this->SetLeftMargin(10); // -5
		$this->SetXY(120,50);
    	$w=array(100,100,100);
	    for($i=0;$i<count($header);$i++)
	  	$this->Cell($w[$i],40,$header[$i],1,0,'C',true);
	    $this->Ln();
	//Color and font restoration
	
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('Times','B',20);
	//Data
	$fill=false;
	$dis=0;
	$addpage=1;
	
	while($row=mysql_fetch_array($data))
	{
	
	   $jsonClass = new JSON;
       $jsonEncodedData = $jsonClass->decode($row['designArray']);
	   
	     //$jsonEncodedData[0]->imagePath;
	 
	   
	    //echo $jsonEncodedData[0][0];
		$Eps=explode(".",$jsonEncodedData[0]->imagePath);
		$Eps= "siteimages/design/originaldesign/".$Eps[0].".eps";
		$image= "siteimages/design/originaldesign/".$jsonEncodedData[0]->imagePath;		
		$this->SetX(120);
		$this->Cell($w[0],70,$row[designId],'LR',0,'C',$fill);
		$this->Cell($w[1],70,'','LR',0,'C',$fill);
		$this->RotatedImage($image, 240,95+$dis, 50, 50, 0);		
		$this->Cell($w[2],70,'','LR',0,'C',$fill);
		$this->RotatedEps($Eps, 340,95+$dis, 50, 50, 0);
		//$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
		$this->Ln();
		$fill=!$fill;
		$dis=$dis+70;
		
		
		$addpage++;
	}
		//$this->SetXY(120,50);
		$this->SetX(120);
	  $this->Cell(array_sum($w),0,'','T');
}

//////////////////////////////////////////*******************  FOR FLIP_FLOP EPS IMAGES ***********************************///////////////////////////////////

function flipEps($designImgSource,$posW,$posH,$posX,$posY,$rotation,$txtColor,$colorable)
{
$w = $posW;
$h = $posH;
$x = $posX;
$posY = $posY;
$y = $h;
$angle = $rotation;
$imageColor = $txtColor;
$file= $designImgSource;
$this->StartTransform();
$this->Rotate($angle,$x,$posY);
$this->MirrorV($h);
if($colorable == 'true')
{
$this->ImageEpsColorable($file, $x, $y-$posY, $w, $h, $link='', true , $imageColor);
}
else
{
$this->ImageEps($file, $x,$y-$posY,$w,$h);
}

$this->StopTransform();
//$this->Output();
}

function flopEps($designImgSource,$posW,$posH,$posX,$posY,$rotation,$txtColor,$colorable)
{
$w = $posW;
$h = $posH;
$y = $posY;
$x=$w;
$angle = $rotation;
$imageColor = $txtColor;
$file = $designImgSource;
$this->StartTransform();
$this->Rotate($angle,$posX,$y);
$this->MirrorH($w);

if($colorable == 'true')
{
$this->ImageEpsColorable($file, $x-$posX, $y, $w, $h, $link='', true , $imageColor);
}
else
{
$this->ImageEps($file, $x-$posX,$y,$w,$h);
}
$this->StopTransform();
//$this->Output();
}
function flipFlopEps($designImgSource,$posW,$posH,$posX,$posY,$rotation,$txtColor,$colorable)
 {
 
$w=$posW;
$h=$posH;
$y=$h;
$x=$w;
$angle = $rotation;
$imageColor = $txtColor;
$file = $designImgSource;
$this->StartTransform();
$this->Rotate($angle,$posX,$posY);
$this->MirrorV($h);
$this->MirrorH($w);

if($colorable == 'true')
{
$this->ImageEpsColorable($file, $x-$posX, $y-$posY, $w, $h, $link='', true , $imageColor);
}
else
{
$this->ImageEps($file, $x-$posX,$y-$posY,$w,$h);
}

$this->StopTransform();
//$this->Output();
}	
}
?>
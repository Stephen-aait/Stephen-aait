<?php

class Inky_Designersoftware_Helper_Font_Ttf extends Mage_Core_Helper_Abstract
{
	public $debug = true;
    private $error_message_tpl = "[ycTIN_TTF][ERROR] {message} <br />n";
    private $filename;
    private $file;
    private $position;
    private $offset;
    private $tables;

    public function __construct($filename = false) {
        if (false !== $filename) {
            $this->open($filename);
        }
    }

    public function __destruct() {
        $this->close();
    }

    private function open($filename) {
        $this->close();

        if (empty($filename)) {
            $this->printError("The filename cannot be empty");
            return false;
        }
        if (!file_exists($filename)) {
            $this->printError("The file $filename does not exist");
            return false;
        }

        $this->filename = $filename;
        $this->file = file_get_contents($filename);
        $this->tables = array();

        if (empty($this->file)) {
            $this->printError("The file $filename is empty");
            return false;
        }
        return true;
    }

    public function close() {
        $this->position = $this->offset = 0;
        unset($this->filename, $this->file, $this->tables);
    }

    public function getNameTable() {
        if (!isset($this->file) || empty($this->file)) {
            $this->printError("Please open the file before getNameTable()");
            return false;
        }

        $num_of_tables = $this->getUint16(4);
        for ($i = 0; $i < $num_of_tables; $i++) {
            if ("name" == $this->getTag(12 + $i * 16)) {
                $this->offset = $this->getUint32(12 + $i * 16 + 8);
                $this->position = $this->offset + 2;
                $num_of_name_tables = $this->getUint16();
                $name_tables_offset = $this->getUint16() + $this->offset;
            }
        }

        $name_tables = array();
        for ($i = 0; $i < $num_of_name_tables; $i++) {
            $this->position = $this->offset + 6 + $i * 12;
            $platform_id = $this->getUint16();
            $specific_id = $this->getUint16();
            $lang_id = $this->getUint16();
            $name_id = $this->getUint16();
            $string_length = $this->getUint16();
            $string_offset = $this->getUint16() + $name_tables_offset;

            $key = "$platform_id::$specific_id::$lang_id";

            if (isset($name_id) && empty($name_tables[$key][$name_id])) {
                $text = substr($this->file, $string_offset, $string_length);
                $name_tables[$key][$name_id] = str_replace(chr(0), "", $text);
            }
        }

        return $this->tables['name'] = $name_tables;
    }

    private function getTag($pt = false) {
        if (false === $pt) {
            $pt = $this->position;
            $this->position += 4;
        }
        return substr($this->file, $pt, 4);
    }

    private function getUint32($pt = false) {
        if (false === $pt) {
            $pt = $this->position;
            $this->position += 4;
        }
        $r = unpack("N", substr($this->file, $pt, 4));
        return $r[1];
    }

    private function getUint16($pt = false) {
        if (false === $pt) {
            $pt = $this->position;
            $this->position += 2;
        }
        $r = unpack("n", substr($this->file, $pt, 2));
        return $r[1];
    }

    private function printError($message) {
        if (true === $this->debug) {
            echo str_replace("{message}", $message, $this->error_message_tpl);
        }
    }

    public function getFontStyle($fontPath = '') {
        $objFont = (object) $objFont;
        $imageName = $this->getImageName('nn.png');
        $fontImage = $this->pathFontImage . '/' . $imageName;
        $this->open($fontPath); //Open Font File
        $fontDetail = $this->getNameTable();  //Get Font Detail

        $fontText = $fontDetail['1::0::0'][1];    //Get Font Style

        $objFont->fontStyle = $fontText;

        return $objFont;
    }

    public function generateFontImage($fontPath = '', $imageName, $fontImage) {
		
		
		/* Create new imagick object */
		$image 		= new Imagick();
		$draw 		= new ImagickDraw();
		$color 		= new ImagickPixel('#000000');
		$background = new ImagickPixel('none'); // Transparent
		
		$image->setFont($fontPath);		
		
        $objFont = array();
        $imageName = str_replace('TTF', 'png', strtoupper($imageName));

        $this->open($fontPath); //Open Font File
        $fontDetail = $this->getNameTable();  //Get Font Detail
        $fontText = $fontDetail['1::0::0'][1]; //Get Font Style
        $text = $fontText;
        

		/* Font properties */
		$draw->setFont($fontPath);
		$draw->setFontSize(50);
		$draw->setFillColor($color);
		$draw->setStrokeAntialias(true);
		$draw->setTextAntialias(true);

		/* Get font metrics */
		$metrics = $image->queryFontMetrics($draw, $text);

		/* Create text */
		$draw->annotation(0, $metrics['ascender'], $text);

		/* Create image */
		$image->newImage($metrics['textWidth'], $metrics['textHeight'], $background);
		$image->setImageFormat('png');
		$image->drawImage($draw);
		
		$image->writeImage($fontImage);       
		
        $objFont['fontImage'] = $imageName;
        $objFont['fontStyle'] = $fontText;

        return $objFont;
    }

    private function makeImageTransparent($oldfile, $newfile) {
        list($imgW, $imgH) = getimagesize($oldfile);
        $extName = $this->getImgExtension($oldfile);

        $im = (strtoupper($extName) == 'PNG') ? imagecreatefrompng($oldfile) : imagecreatefromjpeg($oldfile);

        $img = imagecreatetruecolor($imgW, $imgH);
        $trans = imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
        imagecolortransparent($img, $trans);
        imagecopy($img, $im, 0, 0, 0, 0, $imgW, $imgH);
        imagetruecolortopalette($img, true, 256);
        imageinterlace($img);
        imagepng($img, $newfile);
        imagedestroy($img);
        exec($imageMagickPath . ' -trim "' . $newfile . '" "' . $newfile . '"', $result);
    }

    private function getImgExtension($imageName) {
        $extExp = explode(".", $imageName);
        $countExp = count($extExp);
        $extName = $extExp[$countExp - 1];
        return $extName;
    }

    private function getImageName($imageName) {
        if ($imageName != '') {
            $randNo = date("mdis");
            $randVal = rand(1, $randNo);
            $extExp = explode(".", $imageName);
            $countExp = count($extExp);
            $extName = $extExp[$countExp - 1];
            $imageName = $randVal . "." . $extName;
        }
        return $imageName;
    }

    private function getImageRatio($imgRatio, $imgPath) {
        $imageRatio = '';
        $ratioW = $imgRatio;
        $ratioH = $imgRatio;

        list($width, $height) = getimagesize($imgPath);

        if ($width > $ratioW && $height > $ratioH) {
            $imageRatio = $ratioW . 'x' . $ratioH;
        } else if ($width < $ratioW && $height > $ratioH) {
            $imageRatio = 'x' . $ratioH;
        } else if ($width > $ratioW && $height < $ratioH) {
            $imageRatio = $ratioW . 'x';
        } else {
            $imageRatio = $width . 'x' . $height;
        }

        return $imageRatio;
    }
}

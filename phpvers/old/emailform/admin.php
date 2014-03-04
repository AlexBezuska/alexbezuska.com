<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "admin" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "Terrajane0413" );

?>
<?php
/**
 * Copyright (C) : http://www.formmail-maker.com
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>


</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    $_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'2B31' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANEQxhDGVqRxUSmiLSyNjpMRRYLaBVpdGgICEXR3SrSytDoANMLcdO0qWGrpq5aiuK+ABR1YMjoADYPRYy1AVNMpAHsFhSx0FCwm0MDBkH4URFicR8A+PjM5QIWLCAAAAAASUVORK5CYII=',
			'8FCB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WANEQx1CHUMdkMREpog0MDoEOgQgiQW0ijSwNgg6iKCpYwWqDEBy39KoqWFLV60MzUJyH5o6JPMYUczDZQe6W1gDgCrQ3DxQ4UdFiMV9AErBy3PPlwDhAAAAAElFTkSuQmCC',
			'2CA8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMEx1QBITmcLa6BDKEBCAJBbQKtLg6OjoIIKsGyjG2hAAUwdx07Rpq5auipqahey+ABR1YMgINIk1NBDFPNYGkQbXBlQxoKpGVzS9oaGMoUDzUNw8UOFHRYjFfQAbB80hflmXTgAAAABJRU5ErkJggg==',
			'7B24' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGRoCkEVbRVoZHR0a0cQaXRsCWlHEpoi0AnVOCUB2X9TUsFUrs6KikNzH6ABU18rogKyXtUGk0WEKY2gIkpgISCwA1S0BDUC3OKCLiYawhgagunmAwo+KEIv7ALEAzXe5hAoiAAAAAElFTkSuQmCC',
			'CF3F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WENEQx1DGUNDkMREWkUaWBsdHZDVBTSKAMlAVLEGoBhCHdhJUaumhq2aujI0C8l9aOoQYujmYbEDm1tYQ0QaGEMZUcQGKvyoCLG4DwDdNcr924LscgAAAABJRU5ErkJggg==',
			'09A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YQximMLQii7EGsLYyhDJMRRYTmSLS6OjoEIosFtAq0ugKJJHdF7V06dJUIInsvoBWxkAkdVAxhkbXUFQxkSksjejqQG5hRRMDuRkoFhowCMKPihCL+wB5fsyyusT3FAAAAABJRU5ErkJggg==',
			'4197' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37pjAEMIQyhoYgi4UwBjA6OjSIIIkxhrAGsDYEoIixAvWCxAKQ3Ddt2qqolZlRK7OQ3BcAsiMkoBXZ3tBQoBhIBs0tjA0BARhijo4OqGKsoUA3o4oNVPhRD2JxHwCII8kkahgjhQAAAABJRU5ErkJggg==',
			'0260' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGVqRxVgDWFsZHR2mOiCJiUwRaXRtcAgIQBILaGUAijE6iCC5L2rpqqVLp67MmobkPqC6KayOjjB1MLEA1oZAFDGRKYwOrA0BKHYA3dKA7hZGB9FQBzQ3D1T4URFicR8AJF/LW4KrMQQAAAAASUVORK5CYII=',
			'082F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUNDkMRYA1hbGR0dHZDViUwRaXRtCEQRC2hlbWVAiIGdFLV0ZdiqlZmhWUjuA6trZUTTK9LoMIURww6HAFQxsFscUMVAbmYNRXXLQIUfFSEW9wEA3QbIkrr4dwMAAAAASUVORK5CYII=',
			'9DCA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANEQxhCHVqRxUSmiLQyOgRMdUASC2gVaXRtEAgIwBBjdBBBct+0qdNWpq5amTUNyX2srijqIBCiNzQESUwALCaIog7ilkAUMYibHVHNG6DwoyLE4j4AN7bL9LvYYWgAAAAASUVORK5CYII=',
			'828A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGVqRxUSmsLYyOjpMdUASC2gVaXRtCAgIQFHH0Ojo6OggguS+pVGrlq4KXZk1Dcl9QHVTGBHqoOYxBLA2BIaGoIgxOgDFUNQB3dKArpc1QDTUIZQRRWygwo+KEIv7AFJOy1Fjt850AAAAAElFTkSuQmCC',
			'F21F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMZQximMIaGIIkFNLC2MoQwOjCgiIk0OmKIMTQ6TIGLgZ0UGrVq6appK0OzkNwHVDeFYQqG3gBMMSAfQ4y1AVNMNNQx1BFFbKDCj4oQi/sA5Q3KTdJaz7AAAAAASUVORK5CYII=',
			'8DD3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGUIdkMREpoi0sjY6OgQgiQW0ijS6NgQ0iKCqA4sFILlvadS0lamropZmIbkPTR1O83DYgeEWbG4eqPCjIsTiPgCwRs8IyfsDWwAAAABJRU5ErkJggg==',
			'A6DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGUNDkMRYA1hbWRsdHZDViUwRaWRtCEQRC2gVaUASAzspaum0sKWrIkOzkNwX0Craiq43NFSk0RXTPCximG4JaAW7GUVsoMKPihCL+wBZYsr07T+WzgAAAABJRU5ErkJggg==',
			'DD5D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHUMdkMQCpoi0sjYwOgQgi7WKNLoCxUTQxabCxcBOilo6bWVqZmbWNCT3gdQ5NARi6MUm5oouBnQLo6MjiltAbmYIZURx80CFHxUhFvcBAAhGzaldLz1/AAAAAElFTkSuQmCC',
			'859C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaYGIImJTBFpYHR0CBBBEgtoFWlgbQh0YEFVFwISQ3bf0qipS1dmRmYhu09kCkOjQwhcHdQ8oFgDuphIoyOGHayt6G5hDWAMQXfzQIUfFSEW9wEAOBbLeFwDnIsAAAAASUVORK5CYII=',
			'BDEC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHaYGIIkFTBFpZW1gCBBBFmsVaXRtYHRgQVUHFkN2X2jUtJWpoSuzkN2Hpg7FPGxiaHZguAWbmwcq/KgIsbgPAB5wzOz7ycJzAAAAAElFTkSuQmCC',
			'3ACF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RAMYAhhCHUNDkMQCpjCGMDoEOqCobGVtZW0QRBWbItLo2sAIEwM7aWXUtJWpq1aGZiG7D1Ud1DzRUEwxkDpUOwKAeh3R3CIaINLoEOqIqneAwo+KEIv7AEsHygahuprEAAAAAElFTkSuQmCC',
			'A498' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGaY6IImxBjBMZXR0CAhAEhOZwhDK2hDoIIIkFtDK6MraEABTB3ZS1NKlS1dmRk3NQnJfQKtIK0NIAIp5oaGioQ4Y5jG0MmITQ3MLSAzdzQMVflSEWNwHAP5BzF2dzbiAAAAAAElFTkSuQmCC',
			'E273' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDA0IdkMQCGlhbGRoCHQJQxEQaHUAkihhDowNYFOG+0KhVS0EwC8l9QPkpQNiAah5DABCimcfowOiALsYKhIwobgkNEQ11bWBAcfNAhR8VIRb3AQDMBM3+4Hu4VAAAAABJRU5ErkJggg==',
			'349C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7RAMYWhlCGaYGIIkFTGGYyujoECCCrBKoirUh0IEFWWwKoytIDNl9K6OWLl2ZGZmF4r4pIq0MIXB1UPNEQx0a0MUYWhnR7AC6pRXdLdjcPFDhR0WIxX0A4fDKY4e17usAAAAASUVORK5CYII=',
			'3CB4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7RAMYQ1lDGRoCkMQCprA2ujY6NCKLMbSKNLg2BLSiiE0RaWBtdJgSgOS+lVHTVi0NXRUVhew+sDpHB3TzWBsCQ0Mw7cDmFhQxbG4eqPCjIsTiPgDNP88bL1bYTAAAAABJRU5ErkJggg==',
			'5007' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIaGIIkFNDCGMIQyNIigiLG2Mjo6oIgFBog0ugJlApDcFzZt2srUVVErs5Dd1wpW14piM0RsCrJYQCvYjgBkMZEpILcwOiCLsQaA3YwiNlDhR0WIxX0AvBTLd3UcdYYAAAAASUVORK5CYII=',
			'2A85' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2Quw2AMAwFnSIbmH3sIr2RcJMRmMIpsgGwQ5iST2UEJUj4dSc/+WRYb2Pwp3ziFwUENKg4hlMYAjP5PamxRusvDCoWZk7k/Zaljdpy9n5y7JGh6wbqNJlcWDQsab/hGdrZFe+nioUUZvrB/17Mg98GlWbLZAeXcVQAAAAASUVORK5CYII=',
			'451C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37poiGMkxhmBqALBYiAsQMASJIYoxAMcYQRgcWJDHWKSIhDFMYHZDdN23a1KWrpq3MQnZfwBSGRgeEOjAMDcUUY5giAhZjQRFjbQW6D8UtQDtDGEMdUN08UOFHPYjFfQDlRMqU8b0oWQAAAABJRU5ErkJggg==',
			'3761' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7RANEQx1CGVqRxQKmMDQ6OjpMRVHZytDo2gBUiSw2haGVtQGuF+yklVGrpi2dumopivumMASwOjq0oprH6MDaEIAmxtqALhYwRaSBEU2vaIBIA0MoQ2jAIAg/KkIs7gMAx6XLzvNXp8UAAAAASUVORK5CYII=',
			'98DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUNDkMREprC2sjY6OiCrC2gVaXRtCEQTA6pDiIGdNG3qyrClqyJDs5Dcx+qKog4CsZgngEUMm1ugbkY1b4DCj4oQi/sADVzKUOjOyUsAAAAASUVORK5CYII=',
			'ED83' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGUIdkMQCGkRaGR0dHQJQxRpdQSSaGFBZQwCS+0Kjpq3MCl21NAvJfWjq8JqHRQzDLdjcPFDhR0WIxX0ATI7OuZmqlIMAAAAASUVORK5CYII=',
			'FFA0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMLQiiwU0iDQwhDJMdUATY3R0CAhAE2NtCHQQQXJfaNTUsKWrIrOmIbkPTR1CLBSLWEMAFjsCMNwCFENx80CFHxUhFvcBAGCUzjJ0ybSnAAAAAElFTkSuQmCC',
			'E272' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nM2QMQ6AIAxF28Eb4H26uNeELpymDNxAj+DCKf0jjY6awEsYXn7CC9Qfx2kmfumzzHkxPWVw6kvDrRpcquK7pOCoCmwa+qz0C/Qy9GF3gBrfIAWNgmNhwTK2ACxD82qbs+UJ/u9DXvpuQJnNfR1Pt3AAAAAASUVORK5CYII=',
			'BDB1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDGVqRxQKmiLSyNjpMRRFrFWl0bQgIRVPX6NroANMLdlJo1LSVqaGrliK7D00dsnmExSBuQRGDujk0YBCEHxUhFvcBACG7z2qkBFpWAAAAAElFTkSuQmCC',
			'547E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYWllDA0MDkMSA7KkMDYEODKhioehigQGMrgyNjjAxsJPCpi1dumrpytAsZPe1irQyTGFE0cvQKhrqEIAqFtDK0MrogComMgXovgZUMdYAsBiKmwcq/KgIsbgPAKeLydKJMkSAAAAAAElFTkSuQmCC',
			'531A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNYQximMLQiiwU0iLQyhDBMdUARY2h0DGEICEASCwwA6pvC6CCC5L6waavCVk1bmTUN2X2tKOpgYo0OUxhDQ5DtgIihqBOZIoKhlzWANYQx1BHVvAEKPypCLO4DABcgyxsg0bcuAAAAAElFTkSuQmCC',
			'1DED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHUMdkMRYHURaWYEyAUhiog4ija5AMREUvShiYCetzJq2MjUUSCK5j5GwXnximG4JwXTzQIUfFSEW9wEAib3IhOeh0/MAAAAASUVORK5CYII=',
			'0162' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGaY6IImxBjAGMDo6BAQgiYlMYQ1gbXB0EEESC2hlAIoB5ZDcF7UUiKYCaST3gdU5OjQ6YOgFkih2gMWmMKC4hQHsFlQ3s4YyhDKGhgyC8KMixOI+ALxUyWsdfx58AAAAAElFTkSuQmCC',
			'9FB2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQ11DGaY6IImJTBFpYG10CAhAEgtoBYo1BDqIoIs1OjSIILlv2tSpYUtDV62KQnIfqytYXSOyHQxg8wJakd0iABGbwoDFLahuBoqFMoaGDILwoyLE4j4AMDDM2NGN/3wAAAAASUVORK5CYII=',
			'3D91' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RANEQxhCGVqRxQKmiLQyOjpMRVHZKtLo2hAQiiI2BSwG0wt20sqoaSszM6OWorgPqM4hJKAV3TyHBkwxRzQxqFtQxKBuDg0YBOFHRYjFfQDZtczIaJ41VQAAAABJRU5ErkJggg==',
			'146B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGUMdkMRYHRimMjo6OgQgiYk6MISyNjg6iKDoZXRlBZIBSO5bmbV06dKpK0OzkNzH6CDSyopmHqODaKhrQyCaeQytrFjEMNwSgunmgQo/KkIs7gMAuszH8VzyJc4AAAAASUVORK5CYII=',
			'317C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA6YGIIkFTGEMAJIBIsgqW1mBYoEOLMhiUxgCGBodHZDdtzJqVdSqpSuzUNwHUjeF0QHF5lagWACmGKMDI4odAUC9rA0MKG4RBboYKIbi5oEKPypCLO4DAArKyKA4LFp5AAAAAElFTkSuQmCC',
			'34A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYWhmmADGSWMAUhqkMoQxTHZBVtjKEMjo6BAQgi01hdGVtCHQQQXLfyqilS5euisyahuy+KSKtSOqg5omGuoaiizEA1QWg2AF0C0gMxS0gNwPFUNw8UOFHRYjFfQDruMxB8o2+sQAAAABJRU5ErkJggg==',
			'543C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYWhlDGaYGIIkB2VNZGx0CRFDFQhkaAh1YkMQCAxhdGRodHZDdFzZt6dJVU1dmobivVaQVSR1UTDTUAWgeslhAK0Mruh0iUxha0d3CGoDp5oEKPypCLO4DANfsy9PHpOR8AAAAAElFTkSuQmCC',
			'9566' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaY6IImJTBFpYHR0CAhAEgtoFWlgbXB0EEAVC2FtYHRAdt+0qVOXLp26MjULyX2srgyNro6OKOYxtALFGgIdRJDEBFpFMMREprC2oruFNYAxBN3NAxV+VIRY3AcAV8HLie/B8mUAAAAASUVORK5CYII=',
			'DDCD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QgNEQxhCHUMdkMQCpoi0MjoEOgQgi7WKNLo2CDqIYIgxwsTATopaOm1l6qqVWdOQ3IemjoAYmh1Y3ILNzQMVflSEWNwHAAgTzaUhdPjFAAAAAElFTkSuQmCC',
			'2A58' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHaY6IImJTGEMYW1gCAhAEgtoZW1lbWB0EEHW3SrS6DoVrg7ipmnTVqZmZk3NQnZfgEijQ0MAinmMDqKhDg2BKOaxNgDNQxMTAYo5Ojqg6A0NBZoXyoDi5oEKPypCLO4DABJozGHGzvW8AAAAAElFTkSuQmCC',
			'F7B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNFQ11DGaY6IIkFNDA0ujY6BASgizUEOoigirWyNjrCxMBOCo1aNW1p6KqoMCT3AdUFsDY6TEXVy+jACiRRxViBMADNDpEGVgy3AMXQ3DxQ4UdFiMV9AJpYzhdBC89YAAAAAElFTkSuQmCC',
			'1C6B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YQxlCGUMdkMRYHVgbHR0dHQKQxEQdRBpcGxyBJLJekQZWIBmA5L6VWdNWLZ26MjQLyX1gdWjmQfQGYpjniiGGxS0hmG4eqPCjIsTiPgAZssj8QOGlcQAAAABJRU5ErkJggg==',
			'C61A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYQximMLQii4m0srYyhDBMdUASC2gUaQSqDAhAFmsQaWCYwuggguS+qFXTwlZNW5k1Dcl9AQ2irUjqYHobHaYwhoag2eGApg7sFjQxkJsZQx1RxAYq/KgIsbgPABZsy0n26lFCAAAAAElFTkSuQmCC',
			'34C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7RAMYWhlCHaY6IIkFTGGYyugQEBCArBKoirVB0EEEWWwKoytrAwNMHdhJK6OWLl26atXULGT3TRFpRVIHNU801LWBEdW8VoZWdDuAbmlFdws2Nw9U+FERYnEfAF16y47sUbLwAAAAAElFTkSuQmCC',
			'6AFE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA0MDkMREpjCGsDYwOiCrC2hhbcUQaxBpdEWIgZ0UGTVtZWroytAsJPeFTEFRB9HbKhqKKYapTgSLXtYAsBiKmwcq/KgIsbgPALVnynaOGA6vAAAAAElFTkSuQmCC',
			'6933' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGUIdkMREprC2sjY6OgQgiQW0iDQ6NAQ0iCCLNQDFwKII90VGLV2aNXXV0iwk94VMYQxEUgfR28qAaV4rC4YYNrdgc/NAhR8VIRb3AQAQ+s6C7UKXhgAAAABJRU5ErkJggg==',
			'1F2B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUMdkMRYHUQaGB0dHQKQxESBYqwNgUASWS+IFwhTB3bSyqypYatWZoZmIbkPrK6VEcU8sNgURkzzAjDFGB1Q9YqGAN0SGoji5oEKPypCLO4DAM/qx9bjFO45AAAAAElFTkSuQmCC',
			'EBD5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDGUMDkMQCGkRaWRsdHRhQxRpdGwLRxVpZGwJdHZDcFxo1NWzpqsioKCT3QdQBSQzzsIkFOohguMUhANl9EDczTHUYBOFHRYjFfQAyzc4BDmRhXAAAAABJRU5ErkJggg==',
			'B43F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYWhlDGUNDkMQCpjBMZW10dEBWF9DKEMrQEIgqNoXRlQGhDuyk0KilS1dNXRmaheS+gCkirQwY5omGOqCb18rQimkHQyu6W6BuRhEbqPCjIsTiPgCd2sunbCUA+QAAAABJRU5ErkJggg==',
			'7728' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGaY6IIu2MjQ6OjoEBKCJuTYEOoggi00BijYEwNRB3BS1atqqlVlTs5Dcx+jAEABUiWIeK0h0CiOKeSJAUYYAVLEAoChIfwCaGGtoAKqbByj8qAixuA8AaxPLdf/FnYcAAAAASUVORK5CYII=',
			'0D80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGVqRxVgDRFoZHR2mOiCJiUwRaXRtCAgIQBILaBVpdHR0dBBBcl/U0mkrs0JXZk1Dch+aOriYa0Mgihg2O7C5BZubByr8qAixuA8ALWLMPYzjukcAAAAASUVORK5CYII=',
			'D4CC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYWhlCHaYGIIkFTGGYyugQECCCLAZUxdog6MCCIsboytrA6IDsvqilQLBqZRay+wJaRVqR1EHFRENdMcQYWjHsmMLQiu4WbG4eqPCjIsTiPgBG48xG6B3kYwAAAABJRU5ErkJggg==',
			'F8FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDA0MdkMQCGlhbWRsYHQJQxEQaXYFiIljUiSC5LzRqZdjS0JVZ05Dch6YOj3m47UB1C9DNDYwobh6o8KMixOI+AMSgy+DZZ4/iAAAAAElFTkSuQmCC',
			'3676' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDA6Y6IIkFTGFtBZIBAcgqW0UaGRoCHQSQxaaINDA0Ojogu29l1LSwVUtXpmYhu2+KaCvDFEYM8xwCGB1E0MQcHVDFQG5hbWBA0Qt2cwMDipsHKvyoCLG4DwB2u8t26p33EAAAAABJRU5ErkJggg==',
			'E404' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMDQEIIkB2VMZQhka0cRCGR0dWlHFGF1ZGwKmBCC5LzRq6dKlq6KiopDcF9Ag0sraEOiAqlc01LUhMDQE1Y5WoB3obmkFugVFDJubByr8qAixuA8AAQPOhK1/n5QAAAAASUVORK5CYII=',
			'15B1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDGVqRxVgdRBpYGx2mIouJgsQaAkJR9YqEANXB9IKdtDJr6tKloauWIruP0YGh0RWhDiHWEIAmJoJFjLWVFU2vaAhjCNDNoQGDIPyoCLG4DwCM98o8wevfpgAAAABJRU5ErkJggg==',
			'1762' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGaY6IImxOjA0Ojo6BAQgiYkCxVwbHB1EUPQytLICaREk963MWjVt6dRVq6KQ3AdUF8Dq6NDogKKX0YG1IaAV1S2sDUCxKahiIg2MQLcgi4mGAG0MZQwNGQThR0WIxX0AyHPJWOnqEsgAAAAASUVORK5CYII=',
			'4433' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjC0MoYyhDogi4UwTGVtdHQIQBJjDGEIZWgIaBBBEmOdwujK0OjQEIDkvmnTli5dNXXV0iwk9wVMEWlFUgeGoaGioQ5o5oHcgm4HSAzdLVjdPFDhRz2IxX0APBnNMqMvTBAAAAAASUVORK5CYII=',
			'C064' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WEMYAhhCGRoCkMREWhlDGB0dGpHFAhpZW1kbHFpRxBpEGl0bGKYEILkvatW0lalTV0VFIbkPrM7R0QFTb2BoCIYdAdjcgiKGzc0DFX5UhFjcBwA/Os3mjbmxewAAAABJRU5ErkJggg==',
			'6DC6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCHaY6IImJTBFpZXQICAhAEgtoEWl0bRB0EEAWawCJMToguy8yatrK1FUrU7OQ3BcyBawO1bxWiF4RDDFBFDFsbsHm5oEKPypCLO4DAA6RzO0gT7YyAAAAAElFTkSuQmCC',
			'87C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WANEQx1CHaY6IImJTGFodHQICAhAEgtoZWh0bRB0EEBV18rawOiA7L6lUaumLV21MjULyX1AdQFAdWjmMTqA9IqgiLE2sALtEEGxQwSoCtUtrAFAFWhuHqjwoyLE4j4A30bL1duKa2UAAAAASUVORK5CYII=',
			'0032' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhDGaY6IImxBjCGsDY6BAQgiYlMYW1laAh0EEESC2gVaXRodGgQQXJf1NJpK7OmAmkk90HVNTqg6wWSDBh2BExhwOIWTDczhoYMgvCjIsTiPgCBEsxqFvYGLQAAAABJRU5ErkJggg==',
			'DBCC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNEQxhCHaYGIIkFTBFpZXQICBBBFmsVaXRtEHRgQRVrZW1gdEB2X9TSqWFLV63MQnYfmjok87CJodmBxS3Y3DxQ4UdFiMV9AFB3zRUZWTELAAAAAElFTkSuQmCC',
			'FE6B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGUMdkMQCGkQaGB0dHQLQxFgbHB1EMMQYYerATgqNmhq2dOrK0Cwk94HVYTUvEIt5mGKYbsF080CFHxUhFvcBAA8JzCiM1zc/AAAAAElFTkSuQmCC',
			'5C48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYQxkaHaY6IIkFNLA2OrQ6BASgiIk0OEx1dBBBEgsMAPIC4erATgqbNm3VysysqVnI7msVAZmIYh5YLDQQxbwAoJhDI6odIlOAOtH0sgZgunmgwo+KEIv7AMNTzhz/LsR4AAAAAElFTkSuQmCC',
			'1D7C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA6YGIImxOoi0MjQEBIggiYk6iDQ6NAQ6sKDoBYo1Ojogu29l1rSVWUtXZiG7D6xuCqMDA7reAEwxRwdGdDtaWRsYUN0SAnRzAwOKmwcq/KgIsbgPALjwyTgYlDORAAAAAElFTkSuQmCC',
			'36AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RAMYQximMLQiiwVMYW1lCGWY6oCsslWkkdHRISAAWWyKSANrQ6CDCJL7VkZNC1u6KjJrGrL7poi2IqmDm+caGhgagi6Gpg7kFnS9IDdjmDdA4UdFiMV9AKdzy+Gcqt7QAAAAAElFTkSuQmCC',
			'96FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0MdkMREprC2sjYwOgQgiQW0ijSCxERQxRqQxMBOmjZ1WtjS0JVZ05Dcx+oq2oqulwFoniuamAAWMWxuAbu5gRHFzQMVflSEWNwHAIfUygHwdmH1AAAAAElFTkSuQmCC',
			'54D4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMYWllDGRoCkMSA7KmsjQ6NaGKhrA0BrchigQGMrkCxKQFI7gubtnTp0lVRUVHI7msVaWVtCHRA1svQKhrq2hAYGoJsRyvQLUCbkNWJTAGKNTqgiLEGYLp5oMKPihCL+wBFms6ZQH1GaQAAAABJRU5ErkJggg==',
			'9B13' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQximMIQ6IImJTBFpZQhhdAhAEgtoFWl0DAHKoYq1AvU2BCC5b9rUqWGrpq1amoXkPlZXFHUQCDTPYQqqeQJYxMBumYLqFpCbGUMdUNw8UOFHRYjFfQB7isx2lJXEOgAAAABJRU5ErkJggg==',
			'7D31' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNFQxhDGVpRRFtFWlkbHaaiiTU6NASEoohNAYo1OsD0QtwUNW1l1tRVS5Hdx+iAog4MWRvA5qGIiWARC2gAuwVNDOzm0IBBEH5UhFjcBwD82s3MzUELqgAAAABJRU5ErkJggg==',
			'F457' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZWllDHUNDkMQCGhimsgJpEVSxUEwxRlfWqWA5uPtCo5YuXZqZtTILyX0BDSKtQLKVAUWvaKhDQ8AUVDGgWxoCAtDFGB0dHdDFGEIZUcQGKvyoCLG4DwDhXMyXacjaFAAAAABJRU5ErkJggg==',
			'4F9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37poiGOoQytKKIhYg0MDo6THVAEmMEirE2BAQEIImxTgGJBTqIILlv2rSpYSszI7OmIbkvAKiOIQSuDgxDQ0G8wNAQFLcA7W1AVQcWc3TEEGMIZUQVG6jwox7E4j4A95fLIZPPAxsAAAAASUVORK5CYII=',
			'F1FE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAT0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA0MDkMQCGhgDWBsYHRhQxFixiDEgi4GdFBq1Kmpp6MrQLCT3oamjghhrKFAMxc0DFX5UhFjcBwBY68hPWjwMywAAAABJRU5ErkJggg==',
			'B871' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA1qRxQKmsAL5AVNRxFpFGh0aAkIx1DU6wPSCnRQatTJs1VIgRHIfWN0UhlYM8wIwxRwdGDDcwtqAKgZ2cwNDaMAgCD8qQizuAwBv8M3SAa2FfAAAAABJRU5ErkJggg==',
			'E78F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkNEQx1CGUNDkMQCGhgaHR0dHRjQxFwbAtHFWhkR6sBOCo1aNW1V6MrQLCT3AdUFMGKYx+jAimEeawOmmEgDut7QEJEGhlBGFLGBCj8qQizuAwBRbsp1yLXl6gAAAABJRU5ErkJggg==',
			'F907' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkMZQximMIaGIIkFNLC2MoQyNIigiIk0Ojo6YIi5AskAJPeFRi1dmroqamUWkvsCGhgDgepaGVD0MoD0TkEVYwHZEcCA4RZGB1QxsJtRxAYq/KgIsbgPANZMzUyBQdbxAAAAAElFTkSuQmCC',
			'F701' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMLQiiwU0MDQ6hDJMRRdzdASKooq1sjYEwPSCnRQatWra0lVRS5HdB1QXgKQOKsbogCnG2sDo6IAmJtLAEIruPqDYFIbQgEEQflSEWNwHAJ/DzU35T7xnAAAAAElFTkSuQmCC',
			'BB37' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQxhDGUNDkMQCpoi0sjY6NIggi7WKAEUCUMWA6hjAogj3hUZNDVs1ddXKLCT3QdW1MmCaNwWLWAADhlscHbC4GUVsoMKPihCL+wCIOs6mzlCspwAAAABJRU5ErkJggg==',
			'1E70' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDA1qRxVgdRIBkwFQHJDFRiFhAAIpeoFijI1gG5r6VWVPDVi1dmTUNyX1gdVMYYeoQYgGYYowODBh2sDYwoLolBOjmBgYUNw9U+FERYnEfADwGyNdF7VwnAAAAAElFTkSuQmCC',
			'BFEB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUElEQVR4nGNYhQEaGAYTpIn7QgNEQ11DHUMdkMQCpog0sDYwOgQgi7VCxERwqwM7KTRqatjS0JWhWUjuI9o8wnZA3QwUQ3PzQIUfFSEW9wEAO6PMMFzIJGIAAAAASUVORK5CYII=',
			'42D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37pjCGsIYyhDogi4WwtrI2OjoEIIkxhog0ujYENIggibFOYQCLBSC5b9q0VUuXropamoXkvoApDFNYEerAMDSUIYAVzTygWxwwxVgb0N3CMEU01BXdzQMVftSDWNwHALD1zXiLCmM/AAAAAElFTkSuQmCC',
			'E923' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUIdkMQCGlhbGR0dHQJQxEQaXUEkmpgDkAxAcl9o1NKlWSuzlmYhuS+ggTHQoZWhAdU8hkaHKQxo5rE0OgSgiwHd4sCI4haQm1lDA1DcPFDhR0WIxX0AScPNymWqIzIAAAAASUVORK5CYII=',
			'DCE8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDHaY6IIkFTGFtdG1gCAhAFmsVaXBtYHQQQRNjRagDOylq6bRVS0NXTc1Cch+aOiQxTPMw7MDiFmxuHqjwoyLE4j4Ag0nOBNdCZbsAAAAASUVORK5CYII=',
			'3F4D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7RANEQx0aHUMdkMQCpog0MLQ6OgQgq2wFik11dBBBFgOpC4SLgZ20Mmpq2MrMzKxpyO4DqmNtRNMLNI81NBBDjAFNHdgtjahuEQ0Ai6G4eaDCj4oQi/sAzMDL3U327C4AAAAASUVORK5CYII=',
			'C4B4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WEMYWllDGRoCkMREWhmmsjY6NCKLBTQyhLI2BLSiiDUwugLVTQlAcl/UqqVLl4auiopCcl8A0ETWRkcHVL2ioa4NgaEhqHa0Au1AdwtQrwOKGDY3D1T4URFicR8ARprOvs02vlsAAAAASUVORK5CYII=',
			'7991' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGVpRRFtZWxkdHaaiiok0ujYEhKKITQGLwfRC3BS1dGlmZtRSZPcxOjAGOoQEoNjB2sDQ6NCAKibSwNLoiCYW0AB2C5oY2M2hAYMg/KgIsbgPAO3KzCDV9TkOAAAAAElFTkSuQmCC',
			'E550' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDHVqRxQIaRBpYGximOmCKBQSgioWwTmV0EEFyX2jU1KVLMzOzpiG5D6in0aEhEKYOj5hIo2tDAJodrK2Mjg4obgkNYQxhCGVAcfNAhR8VIRb3AQCTXc1aVIVpswAAAABJRU5ErkJggg==',
			'975E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQ11DHUMDkMREpjA0ujYwOiCrC2jFKtbKOhUuBnbStKmrpi3NzAzNQnIfqytDAENDIIpehlaQPlQxgVbWBlY0MZEpIg2Mjo4oYqwBIg0MoYwobh6o8KMixOI+ABsMyYAElCwPAAAAAElFTkSuQmCC',
			'F501' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkNFQxmmMLQiiwU0iDQwhDJMRRdjdHQIRRMLYW0IgOkFOyk0aurSpauiliK7L6CBodEVoQ6PmEijo6MDmhhrK9AtaGKMIUA3hwYMgvCjIsTiPgCwb82IBSsGTwAAAABJRU5ErkJggg==',
			'937E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANYQ1hDA0MDkMREpoi0MjQEOiCrC2hlaHTAFAOKOsLEwE6aNnVV2KqlK0OzkNzH6gpUN4URRS9Ip0MAqpgA2DRUMZBbWBtQxcBubmBEcfNAhR8VIRb3AQB2JcmzBTU1/QAAAABJRU5ErkJggg==',
			'EC54' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMYQ1lDHRoCkMQCGlgbXRsYGlHFRBqAYq3oYqxTGaYEILkvNGraqqWZWVFRSO4DqWNoCHRA1wsUCw3BsCMAwy2OjqjuA7mZIZQBRWygwo+KEIv7AGY3z5Pm68OyAAAAAElFTkSuQmCC',
			'1935' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YQxhDGUMDkMRYHVhbWRsdHZDViTqINDo0BDqg6gWKNTq6OiC5b2XW0qVZU1dGRSG5D2hHoANQtwiKXgagSACaGAvYDlQxkFscApDdJxoCcjPDVIdBEH5UhFjcBwD+Q8nXjOKd3QAAAABJRU5ErkJggg==',
			'B8A7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYQximMIaGIIkFTGFtZQhlaBBBFmsVaXR0dEAVA6pjbQgAQoT7QqNWhi1dFbUyC8l9UHWtDGjmuYYGTMEQawgIYMCwI9AB3c3oYgMVflSEWNwHAKX1ziqdWyRMAAAAAElFTkSuQmCC',
			'9333' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7WANYQxhDGUIdkMREpoi0sjY6OgQgiQW0MjQ6NAQ0iKCKQUUR7ps2dVXYqqmrlmYhuY/VFUUdBGIxTwCLGDa3YHPzQIUfFSEW9wEA1/HNeY5OYhUAAAAASUVORK5CYII=',
			'A879' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxBrC2MjQEBAQgiYlMEWl0aAh0EEESC2gFqmt0hImBnRS1dGXYqqWrosKQ3AdWN4VhKrLe0FCgeQFAc1HMEwGaxoBhB2sDA4pbAlqBbm5gQHHzQIUfFSEW9wEArYnMs7FERkkAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>
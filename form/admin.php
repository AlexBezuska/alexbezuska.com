<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "abezuska@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "bef08f" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
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
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

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
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
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
			'F0A2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZAhimMEx1QBILaGAMYQCKB6CIsbYyOjo6iKCIiTS6gkgk94VGTVuZuioKCBHug6prdEDXGxrQyoBmB2tDwBQGNLcAxQJQxRgCWBsCQ0MGQfhREWJxHwBRvc4d16rxxwAAAABJRU5ErkJggg==',
			'4114' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpI37pjAEAHFDALJYCGMAQwhDI7IYYwhrAGMIQyuyGCtE75QAJPdNm7YqahUQRyG5LwCsjtEBWW9oKFgsNISQW7CKsYYyhjqgig1U+FEPYnEfADi5ytXvM0sVAAAAAElFTkSuQmCC',
			'C3F6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WEOAMDRgqgOSmEirSCtrA0NAAJJYQCNDo2sDo4MAslgDA1AdowOy+6JWrQpbGroyNQvJfVB1qOY1QMwTwWKHCAG3gN3cwIDi5oEKPypCLO4DAFLIy4GqEY+PAAAAAElFTkSuQmCC',
			'3E57' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RANEQ1lDHUNDkMQCpog0sAJpEWSVrVjEQOqmAtUjuW9l1NSwpZlZK7OQ3TcFpCuglQHNPJBN6GKsDQEByGIgtzA6Ojqgu5khlBFFbKDCj4oQi/sABdfLBKYaA04AAAAASUVORK5CYII=',
			'DCFE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDA0MDkMQCprA2ujYwOiCrC2gVacAmxooQAzspaum0VUtDV4ZmIbkPTR1eMQw7sLgF7OYGRhQ3D1T4URFicR8AnhvLqHRagPkAAAAASUVORK5CYII=',
			'1EF5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDA0MDkMRYHUQaWIEyyOpEsYgxQsRcHZDctzJratjS0JVRUUjug6hjaBDB0ItNDERiqAtAdp9oCNDNDQxTHQZB+FERYnEfABbUx3scgYl3AAAAAElFTkSuQmCC',
			'3071' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA1qRxQKmMIYAyakoKltZgWoCQlHEpog0OjQ6wPSCnbQyatrKrKWrlqK4D6RuCkMrqnlAsQB0MdZWRgcGDLewNqCKgd3cwBAaMAjCj4oQi/sAuMbLoj18GY4AAAAASUVORK5CYII=',
			'0AA5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIYGIImxBjCGMIQCZZDERKawtjI6OqKIBbSKNLo2BLo6ILkvaum0lamrIqOikNwHURfQIIKiVzTUNRRVTGQK2DwHERS3gPUGILuP0QEsNtVhEIQfFSEW9wEAskTMgItW8D4AAAAASUVORK5CYII=',
			'C9BE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDGUMDkMREWllbWRsdHZDVBTSKNLo2BKKKNQDFEOrATopatXRpaujK0Cwk9wU0MAa6opvXwIBpXiMLhhg2t2Bz80CFHxUhFvcBAGj2y4+7IXKcAAAAAElFTkSuQmCC',
			'9218' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYQximMEx1QBITmcLayhDCEBCAJBbQKtLoGMLoIIIixtDoMAWuDuykaVNXLV01bdXULCT3sboCbZiCah5DK0MAwxRU8wRaGR3QxYBuaUDXyxogGuoY6oDi5oEKPypCLO4DADs7y1+RHnBEAAAAAElFTkSuQmCC',
			'5C0B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMYQxmmMIY6IIkFNLA2OoQyOgSgiIk0ODo6OoggiQUGiDSwNgTC1IGdFDZt2qqlqyJDs5Dd14qiDkUM2byAVkw7RKZguoU1ANPNAxV+VIRY3AcAJZ/MBBla+78AAAAASUVORK5CYII=',
			'2042' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QsQ2AMAwETcEGHugp6I1EGjYIU8SFNwjsQKYkdEZQghR/d9LrT6byuEQt5Re/XkhIscExzt1MBhHHxHqjbQD7trFiQmLvt+9HjGtZvJ+wjgr1Gx0qC2I3l1Q3FNkzTtVFIZ6FcDkPYW7gfx/mxe8EfkjMY7KwSKwAAAAASUVORK5CYII=',
			'7636' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMZQxhDGaY6IIu2srayNjoEBKCIiTQyNAQ6CCCLTRFpYGh0dEBxX9S0sFVTV6ZmIbmP0UG0FagOxTzWBpFGB6B5IkhiIljEAhow3RLQgMXNAxR+VIRY3AcAFYLMUE/TJ5wAAAAASUVORK5CYII=',
			'5BC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNEQxhCHRoCkMQCGkRaGR0CGtHEGl0bBFqRxQIDRFpZGximBCC5L2za1LClq1ZFRSG7rxWkDmgiss2tIPMYQ0OQ7QCLCaC4RWQK2C0oYqwBmG4eqPCjIsTiPgD90s5UIu0E3gAAAABJRU5ErkJggg==',
			'4A48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjAEMDQ6THVAFgthDGFodQgIQBJjDGFtZZjq6CCCJMY6RaTRIRCuDuykadOmrczMzJqaheS+AKA610ZU80JDRUNdQwNRzGMAmdfoiEUMVS9UDNXNAxV+1INY3AcAw8jN267CP2UAAAAASUVORK5CYII=',
			'CAE1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WEMYAlhDHVqRxURaGUNYGximIosFNLK2AsVCUcQaRBpdGxhgesFOilo1bWVq6KqlyO5DUwcVEw3FEGvEVCfSiinGGgIUC3UIDRgE4UdFiMV9AHKKzL0rMVhrAAAAAElFTkSuQmCC',
			'3A7C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA6YGIIkFTGEMAZIBIsgqW1lbGRoCHViQxaaINDo0Ojogu29l1LSVWUtXZqG4D6RuCqMDis2toqEOAehiIkDTGFHsCADqdW1gQHGLaABYDMXNAxV+VIRY3AcAkvbLvVmBFg4AAAAASUVORK5CYII=',
			'84F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYWllDA6Y6IImJTGGYytrAEBCAJBbQyhDK2sDoIIKijtEVSR3YSUujli5dGrpqahaS+0SmiLRimica6opmHtCOVkw7GDD0gt3cwIDi5oEKPypCLO4DAFOsy4KMSE+gAAAAAElFTkSuQmCC',
			'C17F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WEMYAlhDA0NDkMREWhkDGBoCHZDVBTSyYoo1MAQwNDrCxMBOigKhpStDs5DcB1Y3hRFTbwCaWCNIBFVMpBXovgZUMdYQ1lB0sYEKPypCLO4DACc8x8xiu2ObAAAAAElFTkSuQmCC',
			'C817' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WEMYQximMIaGIImJtLK2MoQAaSSxgEaRRkd0sQaguikgGuG+qFUrw1ZNW7UyC8l9UHWtDCh6RRodpoB0o9oBFAtgQHfLFEYHdDczhjqiiA1U+FERYnEfAOk5y9aOYun5AAAAAElFTkSuQmCC',
			'8289' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EEFRx9Do6OgIEwM7aWnUqqWrQldFhSG5D6huCtC8qSIo5jEEsDYENKCKMToAxdDsYG1AdwtrgGioA5qbByr8qAixuA8AtdbL2r1Nh2kAAAAASUVORK5CYII=',
			'EED3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAATklEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDGUIdkMQCGkQaWBsdHQLQxcAkplgAkvtCo6aGLV0VtTQLyX1o6giahyGG5hZsbh6o8KMixOI+ACA3zpD2QYq3AAAAAElFTkSuQmCC',
			'B782' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QMQ6AMAhF6dAb1PvQwR0TWXoaOvQG6iE4pXQr0VGT8refD/8F0McIzKRf+JgWRoYTB48OqDkj0eg1qKtsmHyuhYySBj4ueimrloHPcmS56jpawNivOi9K7GnXkcR2yTNbIwfeJ/jfh3rhuwEh7c2SrhoHmQAAAABJRU5ErkJggg==',
			'43D6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpI37prCGsIYyTHVAFgsRaWVtdAgIQBJjDGFodG0IdBBAEmOdwtDKChRDdt+0aavClq6KTM1Ccl8ARB2KeaGhEPNEUNyCTQzTLVjdPFDhRz2IxX0AR3LMb9j6+VQAAAAASUVORK5CYII=',
			'5E77' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDA0NDkMQCGkTgJD6xwAAgr9EBKIpwX9i0qWGrlq5amYXsvlaguikMrSg2g8QCgKLIdgDFGB2AokhiIlNEGlhBokhirAFAN6OJDVT4URFicR8AO9rLmxiiEXwAAAAASUVORK5CYII=',
			'5ECB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNEQxlCHUMdkMQCGkQaGB0CHQLQxFgbBB1EkMQCA0BijDB1YCeFTZsatnTVytAsZPe1oqhDEUM2L6AV0w6RKZhuYQ3AdPNAhR8VIRb3AQDTTcrzZuJP6gAAAABJRU5ErkJggg==',
			'E737' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNEQx1DGUNDkMQCGhgaXRsdGkTQxByAJJpYK0QU4b7QqFXTVk1dtTILyX1A+QCgulYGFL2MDkByCqoYK4gMQBUTaWBtdHRAdbNIA2MoI4rYQIUfFSEW9wEAwEvN3lm055EAAAAASUVORK5CYII=',
			'1C2D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YQxlCGUMdkMRYHVgbHR0dHQKQxEQdRBpcGwKBJLJeEA8uBnbSyqxpQCITRMLdB1bXyoipdwqmmEMAuhjQLQ6MqG4JYQxlDQ1EcfNAhR8VIRb3AQBV/chGK7cVigAAAABJRU5ErkJggg==',
			'FBD0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkNFQ1hDGVqRxQIaRFpZGx2mOqCKNbo2BAQEoKtrCHQQQXJfaNTUsKWrIrOmIbkPTR2SedjEsNiB4RZMNw9U+FERYnEfAFISzsh4UlmiAAAAAElFTkSuQmCC',
			'A09F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUNDkMRYAxhDGB0dHZDViUxhbWVtCEQRC2gVaXRFiIGdFLV02srMzMjQLCT3gdQ5hKDqDQ0FimGYx9rKiCGG6ZaAVrCbUcQGKvyoCLG4DwDYQcmweKRtrQAAAABJRU5ErkJggg==',
			'0DAD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB1EQximMIY6IImxBoi0MoQyOgQgiYlMEWl0dHR0EEESC2gVaXRtCISJgZ0UtXTaytRVkVnTkNyHpg4hFooqBrIDXR3ILaxAMWS3gNwMFENx80CFHxUhFvcBAAYEzE4xvWInAAAAAElFTkSuQmCC',
			'138B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGUMdkMRYHURaGR0dHQKQxEQdGBpdGwIdRFD0MiCrAztpZdaqsFWhK0OzkNyHpg4mhs08LGJY3BKC6eaBCj8qQizuAwD8TMgfFTekDQAAAABJRU5ErkJggg==',
			'612B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjAGMDo6OgQgiQW0sAawNgQ6iCCLNQD1AsUCkNwXGbUqatXKzNAsJPeFTAGqa2VENa8VKDaFEdU8kFgAqpjIFJAIql6gS0JZQwNR3DxQ4UdFiMV9AAOcyMzZnbkGAAAAAElFTkSuQmCC',
			'4CB8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpI37pjCGsoYyTHVAFgthbXRtdAgIQBJjDBFpcG0IdBBBEmOdItLAilAHdtK0adNWLQ1dNTULyX0BqOrAMDQUKIZmHsMUTDsYpmC6BaubByr8qAexuA8AvS3NirDKfmsAAAAASUVORK5CYII=',
			'5FED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7QkNEQ11DHUMdkMQCGkQaWBsYHQKwiIkgiQUGoIiBnRQ2bWrY0tCVWdOQ3deKqRebWAAWMZEpmG5hBdmL5uaBCj8qQizuAwB0AMqjlEgp8wAAAABJRU5ErkJggg==',
			'EA6F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGUNDkMQCGhhDGB0dHRhQxFhbWRvQxUQaXRsYYWJgJ4VGTVuZOnVlaBaS+8DqMMwTDXVtCMRiHqaYI5re0BCRRodQRhSxgQo/KkIs7gMAC6TLeZFG60MAAAAASUVORK5CYII=',
			'0A24' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGRoCkMRYAxhDGB0dGpHFRKawtrI2BLQiiwW0ijQ6NARMCUByX9TSaSuzVmZFRSG5D6yuldEBVa9oqMMUxtAQFDuA6gLQ3SLS6OiAKsboINLoGhqAIjZQ4UdFiMV9AJD2zYxT1n0AAAAAAElFTkSuQmCC',
			'456C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37poiGMoQyTA1AFgsRaWB0dAgQQRJjBIqxNjg6sCCJsU4RCWFtYHRAdt+0aVOXLp26MgvZfQFTGBpdHR0dkO0NDQWKNQQ6oLpFBCzGgiLG2oruFoYpjCEYbh6o8KMexOI+AHuuyv/tvhp8AAAAAElFTkSuQmCC',
			'7088' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGaY6IIu2MoYwOjoEBKCIsbayNgQ6iCCLTRFpdESog7gpatrKrNBVU7OQ3MfogKIODFkbRBpd0cwTacC0I6AB0y1ANqabByj8qAixuA8AE2vLbzW4gkkAAAAASUVORK5CYII=',
			'74F6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZWllDA6Y6IIu2MkxlbWAICEAVC2VtYHQQQBabwugKEkNxX9TSpUtDV6ZmIbmP0UGkFagOxTzWBtFQV5AMkhiQDVKHIhYAFkN1C1QM1c0DFH5UhFjcBwDSNsptJbUYYQAAAABJRU5ErkJggg==',
			'E263' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUIdkMQCGlhbGR0dHQJQxEQaXRscGkRQxBiAYmAa7r7QqFVLl05dtTQLyX1A+Smsjg4NqOYxBLCCTEURY3TAFGNtQHdLaIhoqAOamwcq/KgIsbgPAEOLzdfEHyJvAAAAAElFTkSuQmCC',
			'AB62' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGaY6IImxBoi0Mjo6BAQgiYlMEWl0bXB0EEESC2gVaWUFySG5L2rp1LClU4E0kvvA6hwdGpHtCA0FmRfQyoBqHkhsCpoY2C2oYiA3M4aGDILwoyLE4j4AJm7NPesxrDcAAAAASUVORK5CYII=',
			'EAB5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMYAlhDGUMDkMQCGhhDWBsdHRhQxFhbWRsC0cREGl0bHV0dkNwXGjVtZWroyqgoJPdB1Dk0iKDoFQ11Bcmgmwe0A0Os0SEA2X2hIUCxUIapDoMg/KgIsbgPACinzj8qcxZZAAAAAElFTkSuQmCC',
			'4161' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjAEMIQytKKIhTAGMDo6TEUWYwxhDWBtcAhFFmMF6mVtgOsFO2natFVRS6euWorsvgCQOkcHFDtCQ0F6A1rR3YJNjBFNL8MU1lCgm0MDBkP4UQ9icR8AmFnJg8X5mToAAAAASUVORK5CYII=',
			'79E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDHUMDkEVbWVtZGxgdUFS2ijS6ootNAYu5OiC7L2rp0tTQlVFRSO5jdGAMdAXSIkh6WRsYGtHFRBpYwHYgiwU0gNzCEBCAIgZys8NUh0EQflSEWNwHAEe2yumpkZjjAAAAAElFTkSuQmCC',
			'920A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeUlEQVR4nM2QIQ6AMAxF/0Q9YtynCHxJmOAKnGKI3mBwA8xOCQmCLiAh0OdemvSlyJeJ+BOv9JG4HglqnU+kCJjZOFE/NQ2LFA5TGzv2pm+Z87rmYVxMH7VIdO4dKGR3oTeuUsduP+LLlojgCkdSB06l++p/D3LTtwHqecrgvA6LVgAAAABJRU5ErkJggg==',
			'1FF9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA6Y6IImxOog0sDYwBAQgiYmCxRiBJLJeFDGwk1ZmTQ1bGroqKgzJfRB1DFMx9TI0YBHDYgeaW0Ig5iG7eaDCj4oQi/sAbAjIZ/JHiuUAAAAASUVORK5CYII=',
			'8B79' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA6Y6IImJTBFpZWgICAhAEgtoFWl0aAh0EEFX1+gIEwM7aWnU1LBVS1dFhSG5D6xuCsNUEXTzAoByaGKODgwYdrA2MKC4BezmBgYUNw9U+FERYnEfAA6ezLsasDfsAAAAAElFTkSuQmCC',
			'3616' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RAMYQximMEx1QBILmMLayhDCEBCArLJVpJExhNFBAFlsiggQMzogu29l1LSwVdNWpmYhu2+KaCtQHYZ5DkC9IgTEwG6ZguoWkJsZQx1Q3DxQ4UdFiMV9AFfYyuS7zv0SAAAAAElFTkSuQmCC',
			'5F14' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQx2mMDQEIIkFNIg0MIQwNKKLMYYwtCKLBQYA1U1hmBKA5L6waVPDVk1bFRWF7L5WkDpGB2S9ULHQEGQ7wGKobhGZginGCrSXMdQBRWygwo+KEIv7APRozYzZIseLAAAAAElFTkSuQmCC',
			'DA8A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGVqRxQKmMIYwOjpMdUAWa2VtZW0ICAhAERNpdHR0dBBBcl/U0mkrs0JXZk1Dch+aOqiYaKhrQ2BoCJp5QDFUdVMw9YYGiDQ6hDKiiA1U+FERYnEfAN9AzaxV7VesAAAAAElFTkSuQmCC',
			'CC13' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WEMYQxmmMIQ6IImJtLI2OoQwOgQgiQU0ijQ4hgDlkMVAvCkgGuG+qFXTVgHR0iwk96GpQxETQbPDAU0M7JYpqG4BuZkx1AHFzQMVflSEWNwHAK/uzXk9fXiDAAAAAElFTkSuQmCC',
			'1F33' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1DGUIdkMRYHUQaWBsdHQKQxESBYgwNAQ0iKHqBvEaHhgAk963Mmhq2auqqpVlI7kNThxDDZh4WMQy3hIg0MKK5eaDCj4oQi/sAhmjK5u8bwTAAAAAASUVORK5CYII=',
			'FF36' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkNFQx1DGaY6IIkFNIg0sDY6BASgiTE0BDoIoIs1Ojoguy80amrYqqkrU7OQ3AdVh9U8ESLEsLmFEc3NAxV+VIRY3AcAYn3OAYacgxAAAAAASUVORK5CYII=',
			'6A44' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHRoCkMREpjCGMLQ6NCKLBbSwtjJMdWhFEWsQaXQIdJgSgOS+yKhpKzMzs6KikNwXMkWk0bXR0QFFb6toqGtoYGgIihjQPAy3YIqxBmCKDVT4URFicR8AiijQAyiBK0EAAAAASUVORK5CYII=',
			'FF7C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QkNFQ11DA6YGIIkFNIiAyAARDLFABxZ0sUZHB2T3hUZNDVu1dGUWsvvA6qYwOjCg6w3AFGN0YMSwgxWoEt0tQDEUNw9U+FERYnEfANsqzH7MWKyOAAAAAElFTkSuQmCC',
			'3D65' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RANEQxhCGUMDkMQCpoi0Mjo6OqCobBVpdG1AE5sCEmN0dUBy38qoaStTp66MikJ2H0ido0ODCIZ5AVjEAh1EMNziEIDsPoibGaY6DILwoyLE4j4A3RTMIrtpjp8AAAAASUVORK5CYII=',
			'96B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoFWlkbQh0EEAVa2BtdHRAdt+0qdPCloauTM1Cch+rqyjQPEcU8xiA5rkCzRNBEhPAIobNLdjcPFDhR0WIxX0AB7/MGUcEdK0AAAAASUVORK5CYII=',
			'0707' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMIaGIImxBjA0OoQyNIggiYlMYWh0dHRAEQtoZWhlbQgAQoT7opaumrZ0VdTKLCT3AdUFsIJIFL2MDkCxKQwodrA2MDo6BDCguAVoYyijA6qbgWJTUMUGKvyoCLG4DwA9lcsSdRDO+QAAAABJRU5ErkJggg==',
			'8E85' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGUMDkMREpog0MDo6OiCrC2gVaWBtCEQRg6pzdUBy39KoqWGrQldGRSG5D6LOoUEEw7wALGKBDiIYdjgEILsP4maGqQ6DIPyoCLG4DwB8HMsL/w1ktQAAAABJRU5ErkJggg==',
			'C25C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QMQ6AIAxF28TeAO9TB3ZMYPEGeooycAM5AgunlLEER432by+/6UuhDiPwp7ziRx49Bc5OMZMokYAzirloohXkSTOBaDOy9ttqLWXfD+3XeifIytDvuoFFZGpM32guggt3LuTnwAE656/+92Bu/C4p6MtRvBuy2QAAAABJRU5ErkJggg==',
			'4D2A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpI37poiGMIQytKKIhYi0Mjo6THVAEmMMEWl0bQgICEASY50i0ujQEOggguS+adOmrcxamZk1Dcl9ASB1rYwwdWAYGgoUm8IYGoLiFqBYAKo6oBhQJ7qYaAhraCCq2ECFH/UgFvcBAKFVy5wAVuMWAAAAAElFTkSuQmCC',
			'ED7A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA1qRxQIaRID8gKkOqGKNDg0BAQHoYo2ODiJI7guNmrYya+nKrGlI7gOrm8IIU4cQC2AMDUETc3TAUNfK2oAqBnYzmthAhR8VIRb3AQCHu82n12RcUwAAAABJRU5ErkJggg==',
			'A00E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIYGIImxBjCGMIQCZZDERKawtjI6OqKIBbSKNLo2BMLEwE6KWjptZeqqyNAsJPehqQPD0FBMsYBWbHZguiWgFdPNAxV+VIRY3AcAHTzKBqlgGAwAAAAASUVORK5CYII=',
			'FFBB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAATUlEQVR4nGNYhQEaGAYTpIn7QkNFQ11DGUMdkMQCGkQaWBsdHQLQxRoCHURwqwM7KTRqatjS0JWhWUjuI8k8/HYgxNDcPFDhR0WIxX0Au9rNdWBYUDMAAAAASUVORK5CYII=',
			'A1A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YAhimMLQii7EGMAYwhDJMdUASE5kCFHV0CAhAEgtoZQhgbQh0EEFyX9RSEIrMmobkPjR1YBgaChQLRRWDqAvAYkcAilsCWllDWUEygyD8qAixuA8AKSrLGMjf+AAAAAAASUVORK5CYII=',
			'9068' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGaY6IImJTGEMYXR0CAhAEgtoZW1lbXB0EEERE2l0bWCAqQM7adrUaStTp66amoXkPlZXoDo08xjAegNRzBMA24Eqhs0t2Nw8UOFHRYjFfQBzTcuOTucXRwAAAABJRU5ErkJggg==',
			'4513' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37poiGMkxhCHVAFgsRAWJGhwAkMUagGGMIQ4MIkhjrFJEQoN6GACT3TZs2demqaauWZiG5L2AKQ6MDQh0YhoZCxERQ3CKCRYy1lWEKqluA/BDGUAdUNw9U+FEPYnEfABa+zFlNT19UAAAAAElFTkSuQmCC',
			'C78C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WENEQx1CGaYGIImJtDI0Ojo6BIggiQU0MjS6NgQ6sCCLNTC0MgIVIrsvatWqaatCV2Yhuw+oLgBJHVSM0YEVaB6KWCNrAyuaHSKtIg2MaG5hDQHy0Nw8UOFHRYjFfQAoectAr7eb/QAAAABJRU5ErkJggg==',
			'0491' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YWhlCgRhJjDWAYSqjo8NUZDGRKQyhrA0BochiAa2MrkAxmF6wk6KWLl26MjNqKbL7AlpFWhlCAlpR9YqGOjSgigHtaGVEEwO6pRXoFhQxqJtDAwZB+FERYnEfAK9uyxtsItolAAAAAElFTkSuQmCC',
			'72AD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkMZQximMIY6IIu2srYyhDI6BKCIiTQ6Ojo6iCCLTWFodG0IhIlB3BS1aunSVZFZ05Dcx+jAMIUVoQ4MWRsYAlhDUcVEgCrR1QUAVYLEAlDEREOB9qK6eYDCj4oQi/sAAg/LhorJXBwAAAAASUVORK5CYII=',
			'6140' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHVqRxUSmMAYwtDpMdUASC2gBqpzqEBCALNYA1Bvo6CCC5L7IqFVRKzMzs6YhuS9kCkMAayNcHURvK1AsNBBDDOgWFDtEpoDFUNzCCtSJ7uaBCj8qQizuAwDM2ssfepJwZgAAAABJRU5ErkJggg==',
			'CACA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WEMYAhhCHVqRxURaGUMYHQKmOiCJBTSytrI2CAQEIIs1iDS6NjA6iCC5L2rVtJWpq1ZmTUNyH5o6qJhoKFAsNATFDpA6QRR1Iq0ijY4OgShirCEijQ6hjihiAxV+VIRY3AcAhQjMkIxD6LMAAAAASUVORK5CYII=',
			'0DF6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA6Y6IImxBoi0sjYwBAQgiYlMEWl0BaoWQBILaIWIIbsvaum0lamhK1OzkNwHVYdiHkyvCBY7RAi4BezmBgYUNw9U+FERYnEfAE2Py3z3C2tFAAAAAElFTkSuQmCC',
			'21E8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHaY6IImJTGEMYG1gCAhAEgtoZQWKMTqIIOtuZUBWB3HTtFVRS0NXTc1Cdl8AA4Z5jA4MGOaB1KCLiTRg6g0NZQ1Fd/NAhR8VIRb3AQDkLsi9Skhs7wAAAABJRU5ErkJggg==',
			'D59B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGUMdkMQCpog0MDo6OgQgi7WKNLA2BDqIoIqFgMQCkNwXtXTq0pWZkaFZSO4LaGVodAgJRDMPKIZpXqMjutgU1lZ0t4QGMIagu3mgwo+KEIv7AFiQzSXVqv/gAAAAAElFTkSuQmCC',
			'85A3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQxmmMIQ6IImJTBFpYAhldAhAEgtoFWlgdHRoEEFVF8LaENAQgOS+pVFTly5dFbU0C8l9IlMYGl0R6qDmAcVCA1DMA9oBVodqB2sra0MgiltYAxhB9qK4eaDCj4oQi/sAOETOG2RmXVEAAAAASUVORK5CYII=',
			'BCD5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDGUMDkMQCprA2ujY6OiCrC2gVaXBtCEQVmyLSwNoQ6OqA5L7QqGmrlq6KjIpCch9EXUCDCJp52MRAdohguMUhANl9EDczTHUYBOFHRYjFfQD2z86U2EA5LgAAAABJRU5ErkJggg==',
			'F163' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGUIdkMQCGhgDGB0dHQJQxFgDWBscGkRQxBiAYmAa7r7QqFVRS6euWpqF5D6wOkeHhgAMvQFYzMMUw+KWUHQ3D1T4URFicR8AlavLyy70f4cAAAAASUVORK5CYII=',
			'A458' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YWllDHaY6IImxBjBMZW1gCAhAEhOZwhDKClQtgiQW0MroyjoVrg7spKilQJCZNTULyX0BrSKtQBLFvNBQ0VCHhkA084BuwSLG6OiAohckxhDKgOLmgQo/KkIs7gMA9qPMVne8cAcAAAAASUVORK5CYII=',
			'6B7F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA0NDkMREpoi0MjQEOiCrC2gRaXRAF2sAqmt0hImBnRQZNTVs1dKVoVlI7gsBmTeFEVVvK9C8AEwxRwdUMZBbWBtQxcBuRhMbqPCjIsTiPgBqf8pxeSMD3AAAAABJRU5ErkJggg==',
			'C6D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDGVqRxURaWVtZGx2mOiCJBTSKNLI2BAQEIIs1iDSwNgQ6iCC5L2rVtLClqyKzpiG5L6BBtBVJHUxvoyu6WCNIDNUObG7B5uaBCj8qQizuAwCPVM1gkOrZ5AAAAABJRU5ErkJggg==',
			'316B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RAMYAhhCGUMdkMQCpjAGMDo6OgQgq2xlDWBtcHQQQRabwgAUY4SpAztpZdSqqKVTV4ZmIbsPpA7DPJDeQFTzsIgFAPWiu0U0gDUU3c0DFX5UhFjcBwDK/8i0YR6OSQAAAABJRU5ErkJggg==',
			'EBA7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQximMIaGIIkFNIi0MoQyNIigijU6Ojqgi7WyAskAJPeFRk0NW7oqamUWkvug6loZ0MxzDQ2YgiHWEBDAgGFHoAO6m9HFBir8qAixuA8AnDrOI9XWoLUAAAAASUVORK5CYII=',
			'00BD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUMdkMRYAxhDWBsdHQKQxESmsLayNgQ6iCCJBbSKNLoC1YkguS9q6bSVqaErs6YhuQ9NHUIMzTxsdmBzCzY3D1T4URFicR8ArjbLBiBeUQ8AAAAASUVORK5CYII=',
			'9962' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bXB0EMEQA6pHct+0qUuXpk5dtSoKyX2sroyBro4Ojch2MLQyAPUGtCK7RaCVBSQ2hQGLWzDdzBgaMgjCj4oQi/sAeDPMSmn4nKAAAAAASUVORK5CYII=',
			'A361' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGVqRxVgDRFoZHR2mIouJTGFodG1wCEUWC2hlaGVtgOsFOylq6aqwpVNXLUV2H1idowOKHaGhIPMCWtHMwyIGdguaGNjNoQGDIPyoCLG4DwAvx8yU16KUQQAAAABJRU5ErkJggg==',
			'DF03' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QgNEQx2mMIQ6IIkFTBFpYAhldAhAFmsVaWB0dGgQQRNjbQhoCEByX9TSqWFLgWQWkvvQ1KGIoZuHYQcWt4QGAMXQ3DxQ4UdFiMV9AAUCzm9iDkJCAAAAAElFTkSuQmCC',
			'B6CE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHUMDkMQCprC2MjoEOiCrC2gVaWRtEEQVmyLSwNrACBMDOyk0alrY0lUrQ7OQ3BcwRbQVSR3cPFesYuh2YLoFm5sHKvyoCLG4DwDtncs5zh/VwAAAAABJRU5ErkJggg==',
			'95CA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpIn7WANEQxlCHVqRxUSmiDQwOgRMdUASC2gVaWBtEAgIQBULYQWqFEFy37SpU5cuXbUyaxqS+1hdGRpdEeogsBUsFhqCJCbQKgIUE0RRJzKFtZXRIRBFjDWAMYQh1BHVvAEKPypCLO4DADptyzJ11BZ3AAAAAElFTkSuQmCC',
			'9367' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANYQxhCGUNDkMREpoi0Mjo6NIggiQW0MjS6NmCItbKCaCT3TZu6Kmzp1FUrs5Dcx+oKVOfo0IpiM9i8gCnIYgIQsQAGDLc4OmBxM4rYQIUfFSEW9wEAe4LLW0wdiwUAAAAASUVORK5CYII=',
			'AF38' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1EQx1DGaY6IImxBog0sDY6BAQgiYlMEQGSgQ4iSGIBrUAeQh3YSVFLp4atmrpqahaS+9DUgWFoKA7zsIihuwUkxojm5oEKPypCLO4DAN0UzcvImX3BAAAAAElFTkSuQmCC',
			'6993' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUIdkMREprC2Mjo6OgQgiQW0iDS6NgQ0iCCLNUDEApDcFxm1dGlmZtTSLCT3hUxhDHQIgauD6G1laHRAN6+VpdERTQybW7C5eaDCj4oQi/sAxPbNeKwdE2gAAAAASUVORK5CYII=',
			'E10A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYAhimMLQiiwU0MAYwhDJMdUARYw1gdHQICEARYwhgbQh0EEFyX2jUqqilqyKzpiG5D00dslhoCJoYo6MjhjqGUEYUsdAQ1lCGKahiAxV+VIRY3AcA21jKKuX7eIoAAAAASUVORK5CYII=',
			'BA19' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYAhimMEx1QBILmMIYwhDCEBCALNbK2soYwugggqJOpNFhClwM7KTQqGkrs6atigpDch9EHcNUFL2toqFAsQZUMbA6LHaguiU0QKTRMdQBxc0DFX5UhFjcBwCpX83K7gBDJwAAAABJRU5ErkJggg==',
			'FCD8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkMZQ1lDGaY6IIkFNLA2ujY6BASgiIk0uDYEOoigibE2BMDUgZ0UGjVt1dJVUVOzkNyHpg5JDNM8TDuwuQXTzQMVflSEWNwHAO+czzobNGt6AAAAAElFTkSuQmCC',
			'300F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7RAMYAhimMIaGIIkFTGEMYQhldEBR2crayujoiCo2RaTRtSEQJgZ20sqoaStTV0WGZiG7D1Ud1DxsYph2YHML1M2oegco/KgIsbgPADFQyOETzoIQAAAAAElFTkSuQmCC',
			'30A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYAhimMIYGIIkFTGEMYQhldEBR2crayujoiCo2RaTRtSHQ1QHJfSujpq1MXRUZFYXsPrC6gAYRFPOAYqHoYqytrA2BDiJobmFtCAhAdh/IzUCxqQ6DIPyoCLG4DwAKpcut6gxesAAAAABJRU5ErkJggg==',
			'0870' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA1qRxVgDWIH8gKkOSGIiU0QaHRoCAgKQxAJageoaHR1EkNwXtXRl2KqlK7OmIbkPrG4KI0wdVAxoXgCqGMgORwcGFDtAbmFtYEBxC9jNDQwobh6o8KMixOI+ANoEy7oFkO9hAAAAAElFTkSuQmCC',
			'E5AE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMIYGIIkFNIg0MIQyOjCgiTE6OqKLhbA2BMLEwE4KjZq6dOmqyNAsJPcBzW50RahDiIWii4lgUcfayoomFhrCCLIXxc0DFX5UhFjcBwDF4MweD7Ma9gAAAABJRU5ErkJggg=='        
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
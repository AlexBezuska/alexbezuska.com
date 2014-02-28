<?php

# This block must be placed at the very top of page.
# --------------------------------------------------
require_once( dirname(__FILE__).'/form.lib.php' );
phpfmg_display_form();
# --------------------------------------------------



function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>

<form name="frmFormMail" action='' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onsubmit(this);'>
<input type='hidden' name='formmail_submit' value='Y'>
<div id='err_required' class="form_error" style='display:none;'>
    <label class='form_error_title'>Oops, looks like you missed something! Please try again.</label>
</div>

            
            
<ol class='phpfmg_form' >

<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field'>Email</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
	<div id='field_0_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>Interested in...</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<?php phpfmg_dropdown( 'field_1', "Web Design|Graphic Design|Animation|Video|Hosting|Support|Other" );?>
	<div id='field_1_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_2_div'><div class='col_label'>
	<label class='form_field'>Questions/Comments?</label> <label class='form_required' >&nbsp;</label> </div>
	<div class='col_field'>
	<textarea name="field_2" id="field_2" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_2"); ?></textarea>

	<div id='field_2_tip' class='instruction'></div>
	</div>
</li>


            <li id="button_front">
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>

                <input type='image' src="../img/submit_btn.png"  class='form_button'>
                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> 
                    <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
</li>
            
</ol>
            
            


</form>




<?php
			
    phpfmg_javascript($sErr);

} 
# end of form




function phpfmg_form_css(){
?>
<style type='text/css'>

body{
    margin-left: 80px;
    margin-top: 38px;
    width: 310px;
    overflow: hidden;
}

body{
     font-family: "georgia", "times new roman", times, serif;
    font-size : 15px;
    color : #000;
    font-style: italic;
    background-color: transparent;
}

select, option{
    font-size:15px;
    margin-top: 10px;
	margin-bottom: 10px;
}

ol.phpfmg_form{
    list-style-type:none;
    padding:0px;
    margin:0px;
}

ol.phpfmg_form li{
    margin-bottom:5px;
    clear:both;
    display:block;
    overflow:hidden;
	width: 100%
}


.form_field, .form_required{
    
}

.form_required{
    color:#49a8b4;
    margin-right:8px;
}

.field_block_over{

}



 #phpfmg_captcha_div{
 	position: relative;
	width: 150px;
	z-index: 200;
	top: -65px;
	}

#phpfmg_processing{
	z-index: 400;
	}

.form_submit_block{
	
	width: 150px;
	z-index: 400;
    position: relative;
  	left: 140px;
   	top: 0px;
}

#button_front{
	z-index: 400;
}

.text_box, .text_area, .text_select {
    width:280px;
    -webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
	height: 22px;
	margin-top: 10px;
	margin-bottom: 10px;
	
	padding: 9px;
	border: solid 1px #E5E5E5;
	outline: 0;
	background: #FFFFFF url('bg_form.png') left top repeat-x;
	background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #EEEEEE), to(#FFFFFF));
	background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE 1px, #FFFFFF 25px);
	box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	-moz-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	-webkit-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
}

.text_area{

    height:120px;
    
    
    
}

select{
padding: 9px;
	border: solid 1px #E5E5E5;
	outline: 0;
	background: #FFFFFF url('bg_form.png') left top repeat-x;
	background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #EEEEEE), to(#FFFFFF));
	background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE 1px, #FFFFFF 25px);
	box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	-moz-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	-webkit-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;}

input:hover, text_area:hover, text_select:hover,
input:focus, text_area:focus { 
	border-color: #C9C9C9; 
	-webkit-box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 8px;
	}


.form_error_title{
    font-weight: 100;
    color: #fff;
    font-size: 22px;
    
}

.form_error{
    background-color: #49a8b4;
    color: #fff;
    padding: 15px;
    width: 300px;
    margin-bottom: 10px;
    -webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}

.form_error_highlight{
    background-color: #62bdc8;
    padding: 10px;
    color:#fff;
    -webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
}

div.instruction_error{
    font-weight: 100;
    
    color:#fff;
}

hr.sectionbreak{
    height:1px;
    color: #ccc;
}

#one_entry_msg{
    background-color: #F4F6E5;
    border: 1px dashed #ff0000;
    padding: 10px;
    margin-bottom: 10px;
}

<?php phpfmg_text_align();?>    



</style>

<?php
}
# end of css
 
# By: formmail-maker.com
?>
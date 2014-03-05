<?php

// if the from is loaded from WordPress form loader plugin, 
// the phpfmg_display_form() will be called by the loader 
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>




<div id='frmFormMailContainer'>

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>
            
            
<ol class='phpfmg_form' >

<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field'>YOUR EMAIL</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class='text_box'>
	<div id='field_0_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>YOUR NAME</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<input type="text" name="field_1"  id="field_1" value="<?php  phpfmg_hsc("field_1", ""); ?>" class='text_box'>
	<div id='field_1_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_2_div'><div class='col_label'>
	<label class='form_field'>MESSAGE</label> <label class='form_required' >*</label> </div>
	<div class='col_field'>
	<textarea name="field_2" id="field_2" rows=4 cols=25 class='text_area'><?php  phpfmg_hsc("field_2"); ?></textarea>

	<div id='field_2_tip' class='instruction'></div>
	</div>
</li>


            <li>
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
	
				
                <input type='submit' value='Submit' class='form_button'>

				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Please check the required fields</label>
				</div>
				


                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
            </div>
            </li>
            
</ol>
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

</div> 
<!-- end of form container -->


<!-- [Your confirmation message goes here] -->
<div id='thank_you_msg' style='display:none;'>
Your message has been sent. Thank you!
</div>

            
            






<?php
			
    phpfmg_javascript($sErr);

} 
# end of form




function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>
<style type='text/css'>
<?php 
if( !$formOnly ){
    echo"
body{
    margin-left: 18px;
    margin-top: 18px;
}

body{
    font-family : 'helvetica neue', helvetica, Verdana, sans-serif;
    font-size : 14px;
    letter-spacing: .1rem;
    color: rgba(100,100,100, 1);
    background-color: transparent;
}

select, option{
    font-size:13px;
}
";
}; // if
?>
body{
    color: rgba(100,100,100, 1);
    width: 100%;
}
input[type=text]{
    margin-top: 4px;
    height: 32px;
    width: 99%;
}
textarea{
    margin-top: 4px;
    resize: none;
    width: 99%!important;
}
ol.phpfmg_form{
    list-style-type:none;
    padding:0px;
    margin:0px;
}

ol.phpfmg_form input, ol.phpfmg_form textarea, ol.phpfmg_form select{
    border: 1px solid #ccc;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
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
    color: #ef574a;
    margin-right:8px;
}

.field_block_over{
}

.form_submit_block{
    padding-top: 3px;
}



.text_area{
    height:80px;
}

.form_error_title{
    font-weight: bold;
    color: red;
}

.form_error{
    background-color: #ef574a;
    color: white;
    padding: 10px;
    margin-bottom: 10px;
    padding: 15px;
}

.form_error_highlight{
    background-color: #ef574a;
 
}

div.instruction_error{
    color: white;
    font-weight:bold;
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


#frmFormMailContainer input[type="submit"]{
    border: 0px;
    -webkit-border-radius: 0px;
   -moz-border-radius: 0px;
    -ms-border-radius: 0px;
     -o-border-radius: 0px;
        border-radius: 0px;
    padding: 10px 25px; 
    font-weight: 100;
    font-size: 20px;
    letter-spacing: 1px;
    color: white;
    text-transform: uppercase;
    margin-bottom: 10px;
    background-color: #a6c9c2;
}

#frmFormMailContainer input[type="submit"]:hover{
    background-color: #a6c9c2;
}

<?php phpfmg_text_align();?>    



</style>

<?php
}
# end of css
 
# By: formmail-maker.com
?>
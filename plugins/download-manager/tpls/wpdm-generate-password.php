<?php if(!defined('ABSPATH')) die(); ?>
<style type="text/css">
 
#TB_ajaxContent{
    height: 550px !important;
    width: 96% !important;
}
#ps{
    font-size:12pt;
    font-family: 'Courier New';
    height: 80px;
    width: 100%;     
    overflow: auto;
}
</style>
<fieldset class="pfs" style="border: 1px solid #cccccc;padding: 20px;margin: 10px;">
<legend><b><?php _e('Select Options','download-manager'); ?></b></legend>
<div style="width: 42%;float: left;margin-right: 10px;">
<b><?php _e('Number of passwords:','download-manager'); ?></b><Br/>
<input style="width: 98%;" type="text" id='pcnt' value="">
<br/>
<br/>
<b><?php _e('Number of chars for each password:','download-manager'); ?></b><Br/>
<input style="width: 98%;"  type="text" id='ncp' value="">
</div>
<div style="width: 42%;float: right;">
<b><?php _e('Valid chars:','download-manager'); ?></b><br />
<input type="checkbox" id="ls" value="1" checked="=checked"> Small letters<br />
<input type="checkbox" id="lc" value="1"> Capital letters  <br />
<input type="checkbox" id="nm" value="1"> Numbers<br />
<input type="checkbox" id="sc" value="1"> Special chars<br />
</div>
</fieldset>

<fieldset class="pfs" style="border: 1px solid #cccccc;padding: 20px;margin: 10px;">
<legend><b><?php _e('Generated Passwords','download-manager'); ?></b></legend>
<textarea id="ps"></textarea>
</fieldset>
<fieldset class="pfs" style="border: 1px solid #cccccc;padding: 20px;margin: 10px;">
<legend><b><?php _e('Generate','download-manager'); ?></b></legend>
<!--<input type="button" id="gps" class="button button-primary button-large" value="Shuffle" />-->
<input type="button" id="gpsc" class="button button-secondary button-large" value="Generate" />
<input type="button" id="pins" class="button button-primary button-large" value="Insert Password(s)" />
</fieldset>
<script language="JavaScript">
<!--
  jQuery(function(){
      
      var ps = "", pss = "", allps = "";
      allps = jQuery('#<?php echo (int)$_GET['id'];?>').val();
      jQuery('#ps').val(allps.replace(/\]\[/g,"\n").replace(/[\]|\[]+/g,''));
      shuffle = function(){
          var sl = 'abcdefghijklmnopqrstuvwxyz';
          var cl = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
          var nm = '0123456789';
          var sc = '~!@#$%^&*()_';          
          ps = "";
          pss = "";
          if(jQuery('#ls').attr('checked')=='checked') ps = sl;
          if(jQuery('#lc').attr('checked')=='checked') ps += cl;
          if(jQuery('#nm').attr('checked')=='checked') ps += nm;
          if(jQuery('#sc').attr('checked')=='checked') ps +=sc;
          var i=0;
          while ( i <= ps.length ) {
           $max = ps.length-1;
           $num = Math.floor(Math.random()*$max);
           $temp = ps.substr($num, 1);
           pss += $temp;
           i++;
          }
          
          jQuery('#ps').val(pss);
                       
          
      };
      jQuery('#gps').click(shuffle);
      
      jQuery('#gpsc').click(function(){ 
          allps = "";
          shuffle();
          for(k=0;k<jQuery('#pcnt').val();k++){
              allps += "["+randomPassword(pss,jQuery('#ncp').val())+"]";
              
          }
          vallps = allps.replace(/\]\[/g,"\n").replace(/[\]|\[]+/g,'');
          jQuery('#ps').val(vallps);
          
      });
      
      jQuery('#pins').click(function(){
          var aps;
          aps = jQuery('#ps').val();
          aps = aps.replace(/\n/g, "][");
          allps = "["+aps+"]";
          jQuery('#<?php echo (int)$_GET['id'];?>').val(allps);
          tb_remove();
      });
      
      function randomPassword(chars, size) {
           
          //var size = 10;
          if(parseInt(size)==Number.NaN || size == "") size = 8;
          var i = 1;
          var ret = ""
          while ( i <= size ) {
           $max = chars.length-1;
           $num = Math.floor(Math.random()*$max);
           $temp = chars.substr($num, 1);
           ret += $temp;
           i++;
          }
          return ret;
         }

      
  });
//-->
</script>
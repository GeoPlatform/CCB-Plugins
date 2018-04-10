<div class="card">

    <h4 class="card-title"><span class="glyphicon glyphicon-star"></span> Community Related Information</h4>
<?php
 $cat_id = get_query_var('cat');
  //then i get the data from the database
 $cat_data = get_option("category_$cat_id");

  //get data from user
 $meta1 = $cat_data['topic-url1'];
 $meta2 = $cat_data['topic-url2'];
 $meta3 = $cat_data['topic-url3'];
 $meta4 = $cat_data['topic-url4'];
 $meta5 = $cat_data['topic-url5'];

 $url_type1 = '';
 $url_type2 = '';
 $url_type3 = '';
 $url_type4 = '';
 $url_type5 = '';

 $base1='';
 $base2='';
 $base3='';
 $base4='';
 $base5='';

 //Check which url_type is being used
 if (isset($cat_data['url_type1'])){
   $url_type1 = $cat_data['url_type1'];
   if ($url_type1 == "value_1"){
   //get base url
   $base1 = "/newsmap.html";
    } else {
   $base1 = "";
    }
 }
 if (isset($cat_data['url_type2'])){
   $url_type2 = $cat_data['url_type2'];
   if ($url_type2 == "value_1"){
   //get base url
   $base2 = "/newsmap.html";
    } else {
   $base2 = "";
    }
  }
 if (isset($cat_data['url_type3'])){
   $url_type3 = $cat_data['url_type3'];
   if ($url_type3 == "value_1"){
   //get base url
   $base3 = "/newsmap.html";
    } else {
   $base3 = "";
    }
  }
 if (isset($cat_data['url_type4'])){
   $url_type4 = $cat_data['url_type4'];
   if ($url_type4 == "value_1"){
   //get base url
   $base4 = "/newsmap.html";
    } else {
   $base4 = "";
    }
  }

 if (isset($cat_data['url_type5'])){
   $url_type5 = $cat_data['url_type5'];
   if ($url_type5 == "value_1"){
   //get base url
   $base5 = "/newsmap.html";
    } else {
   $base5 = "";
    }
 }
 //add them together
 $url1 = $base1 . $meta1;
 $url2 = $base2 . $meta2;
 $url3 = $base3 . $meta3;
 $url4 = $base4 . $meta4;
 $url5 = $base5 . $meta5;
  // var_dump($url);
 $topic1 = $cat_data['topic-name1'];
 $topic2 = $cat_data['topic-name2'];
 $topic3 = $cat_data['topic-name3'];
 $topic4 = $cat_data['topic-name4'];
 $topic5 = $cat_data['topic-name5'];
?>


    <div id='news-topics'>
    	<h5><a href="<?php echo $url1 ?>" target="_blank"
    	  title="<?php echo $topic1 ?>">
    		<?php echo $topic1 ?>
    	</a></h4>

    <?php if ($topic2){ ?>
      <h5><a href="<?php echo $url2 ?>" target="_blank"
    	  title="<?php echo $topic2 ?>">
    		<?php echo $topic2 ?>
    	</a></h4>
    <?php }?>

    <?php if ($topic3){ ?>
      <h5><a href="<?php echo $url3 ?>" target="_blank"
    	  title="<?php echo $topic3 ?>">
    		<?php echo $topic3 ?>
    	</a></h4>

    <?php }?>
    <?php if ($topic4){ ?>
      <h5><a href="<?php echo $url4 ?>" target="_blank"
    	  title="<?php echo $topic4 ?>">
    		<?php echo $topic4 ?>
    	</a></h4>
    <?php }?>

    <?php if ($topic5){ ?>
      <h5><a href="<?php echo $url5 ?>" target="_blank"
    	  title="<?php echo $topic5 ?>">
    		<?php echo $topic5 ?>
    	</a></h4>
    <?php }?>

  </div><!-- /Community Related Information -->


    <div>
      <hr />
      <h4>Disclaimer:</h4>
      <span class="text-left">
        The opinions expressed by the auto-generated feed above and those providing comments are theirs alone, and do not reflect the opinions of Geoplatform or any employee thereof. Geoplatform is not responsible for the accuracy of any of the information supplied by the generated feed above.
      </span>
    </div>

</div><!-- /card -->

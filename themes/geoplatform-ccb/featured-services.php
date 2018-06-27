<?php 
/*
    Template for featured links of categories in sidebar
*/
$theme_options = geop_ccb_get_options();
//get catergory id
$cat_id = empty(get_query_var('cat') ? '' : get_query_var('cat') );
//then i get the data from the database
 $cat_data = get_option("category_$cat_id");
$cat_data =  $theme_options["category_$cat_id"];
  //get data from user
 $meta1 = $cat_data['topic-url1'];
 $meta2 = $cat_data['topic-url2'];
 $meta3 = $cat_data['topic-url3'];
 $meta4 = $cat_data['topic-url4'];
 $meta5 = $cat_data['topic-url5'];

 $base1='';
 $base2='';
 $base3='';
 $base4='';
 $base5='';

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

//https://stackoverflow.com/questions/2762061/how-to-add-http-if-it-doesnt-exists-in-the-url
//Recognizes ftp://, ftps://, http:// and https:// in a case insensitive way, and adds them if not there
 function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
?>
<?php if (empty($cat_id)) { 

} else { ?>
<div class="card">
  <h4 class="card-title"><span class="glyphicon glyphicon-star"></span> Community Related Information</h4>

    <div id='news-topics' style="word-wrap: break-word;">
      <?php if ($topic1) {?>
    	<h5><a href="<?php echo esc_url(addhttp($url1)); ?>" target="_blank"
    	  title="<?php echo $topic1 ?>">
    		<?php echo $topic1 ?>
      </a></h4>
      <?php } else { ?>
        <p>It seems you don't have any links set for your Category. To set them, go to your category edit page and add some links.</p>

        <?php } ?>
    <?php if ($topic2){ ?>
      <h5><a href="<?php echo esc_url(addhttp($url2)); ?>" target="_blank"
    	  title="<?php echo $topic2 ?>">
    		<?php echo $topic2 ?>
    	</a></h4>
    <?php }?>

    <?php if ($topic3){ ?>
      <h5><a href="<?php echo esc_url(addhttp($url3)); ?>" target="_blank"
    	  title="<?php echo $topic3 ?>">
    		<?php echo $topic3 ?>
    	</a></h4>
    <?php }?>

    <?php if ($topic4){ ?>
      <h5><a href="<?php echo esc_url(addhttp($url4)); ?>" target="_blank"
    	  title="<?php echo $topic4 ?>">
    		<?php echo $topic4 ?>
    	</a></h4>
    <?php }?>

    <?php if ($topic5){ ?>
      <h5><a href="<?php echo esc_url(addhttp($url5)); ?>" target="_blank"
    	  title="<?php echo $topic5 ?>">
    		<?php echo $topic5 ?>
    	</a></h4>
    <?php } //close if(topic5) ?>
  <?php } //close of if(empty($cat_id)) ?>
  </div><!-- /Community Related Information -->
</div><!-- /card -->

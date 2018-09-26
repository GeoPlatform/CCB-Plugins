<?php


class wpdm_affiliate_widget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Pro Affiliate');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);

        echo $before_widget;

        echo "<div class='w3eden'><div class='panel panel-primary'>";
        echo "<div class='panel-heading' style='font-size: 10pt'>Best File & Document Management Plugin</div><div class='panel-body' style='padding-bottom:0;background:#F2F2F2;'><a href='https://www.wpdownloadmanager.com/?affid={$title}'><img src='http://cdn.wpdownloadmanager.com/wp-content/uploads/images/wpdm-main-banner-v4x.png' alt='WordPress Download Manager' /></a></div>";
        echo "<div class='panel-footer' style='line-height: 30px'><a class='pull-right btn btn-sm btn-danger' href='https://www.wpdownloadmanager.com/?affid={$title}'>Buy Now <i class='fa fa-angle-right'></i></a><span class='label label-success' style='font-size: 12pt;border-radius: 2px'>$45.00</span></div></div></div>";
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = isset($instance['title'])?esc_attr($instance['title']):"";
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('WPDM Affiliate ID:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            <em>It is your account <b>username</b> at www.wpdownloadmanager.com. You will get up to 20% from each sale referred by you</em>
        </p>
    <?php
    }

}
 
class wpdm_categories_widget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Categories');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; 

               $args = array(
                    'orderby'           => 'name',
                    'order'             => 'ASC',
                    'hide_empty'        => false,
                    'exclude'           => array(),
                    'exclude_tree'      => array(),
                    'include'           => array(),
                    'number'            => '',
                    'fields'            => 'all',
                    'slug'              => '',
                    'parent'            => '',
                    'hierarchical'      => false,
                    'child_of'          => 0,
                    'childless'         => false,
                    'get'               => '',
                    'name__like'        => '',
                    'description__like' => '',
                    'pad_counts'        => false,
                    'offset'            => '',
                    'search'            => '',
                    'cache_domain'      => 'core'
                );

                $terms = get_terms("wpdmcategory", $args);


            echo "<div class='w3eden'><div class='list-group'>";
               foreach($terms as $term){
               echo "<a href='".get_term_link($term)."'  class='list-group-item'><span class='badge'>{$term->count}</span>{$term->name}</a>\n";
               }

        echo "</div></div>\n";

               echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = isset($instance['title'])?esc_attr($instance['title']):"";
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
    }

}

class wpdm_topdls_widget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Top Downloads');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        global $post;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $sdc = $instance['sdc'];
        $nop = $instance['nop'];

        $newp = new WP_Query(array('post_type'=>'wpdmpro','posts_per_page'=>$nop, 'order_by'=>'publish_date','order'=>'desc','orderby' => 'meta_value_num','meta_key'=>'__wpdm_download_count','order'=>'desc'));

        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title )
            echo $before_title . $title . $after_title;
        echo "<div class='w3eden'>";
        while($newp->have_posts()){
            $newp->the_post();

            $pack = (array)$post;
            echo FetchTemplate($sdc, $pack);
        }
        echo "</div>";
        echo $after_widget;
        wp_reset_query();
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sdc'] = strip_tags($new_instance['sdc']);
        $instance['nop'] = strip_tags($new_instance['nop']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = isset($instance['title'])?esc_attr($instance['title']):"";
        $sdc = isset($instance['sdc'])?esc_attr($instance['sdc']):"link-template-default.php";
        $nop = isset($instance['nop'])?esc_attr($instance['nop']):5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nop'); ?>"><?php _e('Number of packages to show:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('nop'); ?>" name="<?php echo $this->get_field_name('nop'); ?>" type="text" value="<?php echo $nop; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sdc'); ?>"><?php _e('Link Template:'); ?></label>

            <?php echo \WPDM\admin\menus\Templates::Dropdown(array('name' => $this->get_field_name('sdc'), 'id' => $this->get_field_id('sdc'), 'selected' => $sdc)); ?>

        </p>
        <?php
    }

}

class wpdm_newpacks_widget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM New Downloads');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        global $post;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $sdc = $instance['sdc'];
        $nop = $instance['nop1'];

        $newp = new WP_Query(array('post_type'=>'wpdmpro','posts_per_page'=>$nop, 'orderby'=>'date','order'=>'desc'));
        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title )
            echo $before_title . $title . $after_title;
        echo "<div class='w3eden'>";
        while($newp->have_posts()){
            $newp->the_post();

            $pack = (array)$post;
            echo FetchTemplate($sdc, $pack);
        }
        echo "</div>";
        echo $after_widget;
        wp_reset_query();
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sdc'] = strip_tags($new_instance['sdc']);
        $instance['nop1'] = strip_tags($new_instance['nop1']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = isset($instance['title'])?esc_attr($instance['title']):"";
        $sdc = isset($instance['sdc'])?esc_attr($instance['sdc']):'link-template-default.php';
        $nop = isset($instance['nop1'])?esc_attr($instance['nop1']):5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nop1'); ?>"><?php _e('Number of packages to show:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('nop1'); ?>" name="<?php echo $this->get_field_name('nop1'); ?>" type="text" value="<?php echo $nop; ?>" />
        </p>
        <p>

            <label for="<?php echo $this->get_field_id('sdc'); ?>"><?php _e('Link Template:'); ?></label>
            <?php echo \WPDM\admin\menus\Templates::Dropdown(array('name' => $this->get_field_name('sdc'), 'id' => $this->get_field_id('sdc'), 'selected' => $sdc)); ?>
        </p>
        <?php
    }

}

class wpdm_catpacks_widget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Downloads by Category');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        global $post;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $sdc3 = $instance['sdc3'];
        $cat = $instance['scat'];
        $nop = $instance['nop1']<=0?5:$instance['nop1'];
        $html = "";
        $newp = new WP_Query(array('post_type'=>'wpdmpro','posts_per_page'=>$nop, 'order_by'=>'publish_date','order'=>'desc',
            'tax_query'=>array(array('taxonomy'=>'wpdmcategory','terms'=>array($cat),'field'=>'id'))));

        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title )
            echo $before_title . $title . $after_title;
        echo "<div class='w3eden'>";
        while($newp->have_posts()){
            $newp->the_post();

            $pack = (array)$post;
            echo FetchTemplate($sdc3, $pack);
        }
        echo "</div>";
        echo $after_widget;
        wp_reset_query();
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sdc3'] = strip_tags($new_instance['sdc3']);
        $instance['scat'] = strip_tags($new_instance['scat']);
        $instance['nop1'] = strip_tags($new_instance['nop1']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']):'';
        $sdc3 = isset($instance['sdc3'])?$instance['sdc3']:0;
        $scat = isset($instance['scat'])?esc_attr($instance['scat']):0;
        $nop = isset($instance['nop1']) ?esc_attr($instance['nop1']):5;
        $args = array(
            'show_option_all'    => '',
            'show_option_none'   => '',
            'orderby'            => 'ID',
            'order'              => 'ASC',
            'show_count'         => 0,
            'hide_empty'         => 1,
            'child_of'           => 0,
            'exclude'            => '',
            'echo'               => true,
            'selected'           => $scat,
            'hierarchical'       => 0,
            'name'               => $this->get_field_name('scat'),
            'id'                 => '',
            'class'              => 'postform',
            'depth'              => 0,
            'tab_index'          => 0,
            'taxonomy'           => 'wpdmcategory',
            'hide_if_empty'      => false,
            'walker'             => ''
        );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('scat'); ?>"><?php _e('Select Category:','download-manager'); ?></label>

            <?php wp_dropdown_categories($args); ?>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nop1'); ?>"><?php _e('Number of packages to show:','download-manager'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('nop1'); ?>" name="<?php echo $this->get_field_name('nop1'); ?>" type="text" value="<?php echo $nop; ?>" />
        </p>
        <p>

            <label for="<?php echo $this->get_field_id('sdc3'); ?>"><?php _e('Link Template:','download-manager'); ?></label>
            <?php echo \WPDM\admin\menus\Templates::Dropdown(array('name' => $this->get_field_name('sdc3'), 'id' => $this->get_field_id('sdc3'), 'selected' => $sdc3)); ?>


        </p>
        <?php
    }

}

class wpdm_packageinfo_widget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Download Info');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        if(!is_single() || get_post_type() != 'wpdmpro') return;
        global $post;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $package_info = $instance['pinfo'];
        $package_info_labels = array(
            'download_count' => __('Total Downloads','download-manager'),
            'view_count' =>  __('Total Views','download-manager'),
            'create_date' =>  __('Publish Date','download-manager'),
            'update_date' =>  __('Last Updated','download-manager'),
            'package_size' =>  __('Size','download-manager'),
        );

        $package_info_icons = array(
            'download_count' => 'download',
            'view_count' =>  'eye',
            'package_size' =>  'server',
        );
        $download_link = "";
        if(isset($package_info['download_link'])){
            unset($package_info['download_link']);
            $download_link  = "<tr><td colspan='2' class='text-center'>" . \WPDM\Package::downloadLink(get_the_ID()) . "</td>";
        }


        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title )
            echo $before_title . $title . $after_title;
        if(isset($instance['table']) && $instance['table'] == 1) {
            echo "<div class='w3eden'><table class='table table-striped table-bordered' style='font-size: 9pt'>";

            if (is_array($package_info)) {
                foreach ($package_info as $index => $v) {
                    if ($index == 'create_date')
                        echo "<tr><td>{$package_info_labels[$index]}</td><td>" . get_the_date() . "</td></tr>";
                    else if ($index == 'update_date')
                        echo "<tr><td>{$package_info_labels[$index]}</td><td>" . get_the_modified_date() . "</td></tr>";
                    else
                        echo "<tr><td>{$package_info_labels[$index]}</td><td>" . get_post_meta(get_the_ID(), '__wpdm_' . $index, true) . "</td></tr>";
                }
            }
            echo "{$download_link}</table></div>";
        } else {
            echo "<div class='w3eden'><div class='list-group package-info-list'>";

            if(is_array($package_info)){
                foreach($package_info as $index => $v){
                    if($index=='create_date')
                        echo "<div class='list-group-item'><div class='media'><div class='pull-left'><i class='fa fa-calendar'></i></div><div class='media-body'><strong>{$package_info_labels[$index]}</strong><br/>".get_the_date()."</div></div></div>";
                    else if($index=='update_date')
                        echo "<div class='list-group-item'><div class='media'><div class='pull-left'><i class='fa fa-calendar'></i></div><div class='media-body'><strong>{$package_info_labels[$index]}</strong><br/>".get_the_modified_date()."</div></div></div>";
                    else
                        echo "<div class='list-group-item'><div class='media'><div class='pull-left'><i class='fa fa-{$package_info_icons[$index]}'></i></div><div class='media-body'><strong>{$package_info_labels[$index]}</strong><br/>".get_post_meta(get_the_ID(), '__wpdm_'.$index, true)."</div></div></div>";
                }
            }

            echo "<div class='list-group-item'>{$download_link}</div></div>";
        }


        echo $after_widget;
        wp_reset_query();
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $new_instance;
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        if(isset($instance['title']))
            $title = esc_attr($instance['title']);
        else $title = '';
        if(isset($instance['pinfo']))
            $package_info = $instance['pinfo'];



        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('scat'); ?>"><?php _e('Fields to Show:','download-manager'); ?></label>

        <ul>
            <li><label><input type="checkbox" value="download_count" <?php checked(isset($package_info['download_count']), 1) ?> name="<?php echo $this->get_field_name('pinfo'); ?>[download_count]"> <?php _e('Download Count','download-manager'); ?></label></li>
            <li><label><input type="checkbox" value="view_count" <?php checked(isset($package_info['view_count']), 1) ?> name="<?php echo $this->get_field_name('pinfo'); ?>[view_count]"> <?php _e('View Count','download-manager'); ?></label></li>
            <li><label><input type="checkbox" value="create_date" <?php checked(isset($package_info['create_date']), 1) ?> name="<?php echo $this->get_field_name('pinfo'); ?>[create_date]"> <?php _e('Publish Date','download-manager'); ?></label></li>
            <li><label><input type="checkbox" value="update_date" <?php checked(isset($package_info['update_date']), 1) ?> name="<?php echo $this->get_field_name('pinfo'); ?>[update_date]"> <?php _e('Update Date','download-manager'); ?></label></li>
            <li><label><input type="checkbox" value="download_link" <?php checked(isset($package_info['package_size']), 1) ?> name="<?php echo $this->get_field_name('pinfo'); ?>[package_size]"> <?php _e('File Size','download-manager'); ?></label></li>
            <li><label><input type="checkbox" value="download_link" <?php checked(isset($package_info['download_link']), 1) ?> name="<?php echo $this->get_field_name('pinfo'); ?>[download_link]"> <?php _e('Download Link','download-manager'); ?></label></li>
            <li><hr/><?php _e('Style','download-manager'); ?>:<br/><label style="font-weight: 900"><input type="checkbox" value="1" <?php checked(isset($instance['table']), 1) ?> name="<?php echo $this->get_field_name('table'); ?>"> <?php _e('Tabular View','download-manager'); ?></label></li>
        </ul>


        </p>


        <?php
    }

}

add_action('widgets_init', function(){
    register_widget("wpdm_packageinfo_widget");
});
add_action('widgets_init', function(){
    register_widget("wpdm_categories_widget");
});
add_action('widgets_init', function(){
    register_widget("wpdm_topdls_widget");
});
add_action('widgets_init', function(){
    register_widget("wpdm_newpacks_widget");
});
add_action('widgets_init', function(){
    register_widget("wpdm_catpacks_widget");
});
add_action('widgets_init', function(){
    register_widget("wpdm_affiliate_widget");
});


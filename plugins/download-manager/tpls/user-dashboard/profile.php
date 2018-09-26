<?php
if (!defined('ABSPATH')) die();
global $current_user, $wpdb;
?><div class="row">
    <div class="col-md-4">
        <div class="panel panel-default dashboard-panel">
            <div class="panel-heading"><?php _e('User Level','download-manager'); ?></div>
            <div class="panel-body">
                <h3><?php
                    $val = get_option( 'wp_user_roles' );
                    $level = $val[$current_user->roles[0]]['name'];
                    $level = $level==''?ucfirst($current_user->roles[0]):$level;
                    echo apply_filters("wpdm_udb_user_level",$level); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default dashboard-panel">
            <div class="panel-heading"><?php _e('Total Downloads','download-manager'); ?></div>
            <div class="panel-body">
                <h3><?php echo number_format($wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_download_stats where uid = '{$current_user->ID}'"),0,'.',','); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default dashboard-panel">
            <div class="panel-heading"><?php _e("Today's Download",'download-manager'); ?></div>
            <div class="panel-body">
                <h3><?php echo number_format($wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_download_stats where uid = '{$current_user->ID}' and `year` = YEAR(CURDATE()) and `month` = MONTH(CURDATE()) and `day` = DAY(CURDATE())"),0,'.',','); ?></h3>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default dashboard-panel">
    <div class="panel-heading"><?php _e('Recommended Downloads','download-manager'); ?></div>
    <div class="panel-body">
        <div class="panel-row">
            <?php
            $rc = 0;
            $qparams = array(
                'post_type' => 'wpdmpro',
                'posts_per_page' => 20,
                'orderby' => 'rand'
            );
            if(isset($params['recommended']) && term_exists($params['recommended'], 'wpdmcategory')){
                $qparams['tax_query'] = array(array('taxonomy' => 'wpdmcategory', 'field'    => 'slug', 'terms' => $params['recommended']));
            }

            $q = new WP_Query($qparams);
            while($q->have_posts()){ $q->the_post();
                if(\WPDM\Package::userCanAccess(get_the_ID())) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">


                            <?php wpdm_post_thumb(array(400, 300)); ?>
                            <a href="<?php the_permalink(); ?>" class="card-footer">
                                <?php the_title(); ?>
                            </a>
                        </div>
                    </div>

                    <?php
                    $rc++;
                    if ($rc >= 3) break;
                }
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
<div class="panel panel-default dashboard-panel">
    <div class="panel-heading"><?php _e('Last 5 Downloads','download-manager'); ?></div>
    <table class="table">
        <thead>
        <tr>
            <th><?php _e('Package Name','download-manager'); ?></th>
            <th><?php _e('Download Time','download-manager'); ?></th>
            <th><?php _e('IP','download-manager'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $res = $wpdb->get_results("select p.post_title,s.* from {$wpdb->prefix}posts p, {$wpdb->prefix}ahm_download_stats s where s.uid = '{$current_user->ID}' and s.pid = p.ID order by `timestamp` desc limit 0,5");
        foreach($res as $stat){
            ?>
            <tr>
                <td><a href="<?php echo get_permalink($stat->pid); ?>"><?php echo $stat->post_title; ?></a></td>
                <td><?php echo date(get_option('date_format')." H:i",$stat->timestamp); ?></td>
                <td><?php echo $stat->ip; ?></td>
            </tr>
            <?php
        }
        ?>

        </tbody>
    </table>
</div>

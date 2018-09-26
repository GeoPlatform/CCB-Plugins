<?php
if (!defined('ABSPATH')) die();

global $current_user; ?>

<div class="w3eden user-dashboard">
    <div class="row">
        <div class="col-md-3" id="wpdm-dashboard-sidebar">
            <div class="list-group">
                <div class="list-group-item">
                    <?php echo get_avatar( $current_user->user_email, 512 ); ?>
                </div>
                <?php foreach($this->dashboard_menu as $page_id => $menu_item){
                    $menu_url = get_permalink(get_the_ID()).$page_id.($page_id!=''?'/':'');
                    if(isset($params['flaturl']) && $params['flaturl'] == 0 && $page_id != '')
                        $menu_url = get_permalink(get_the_ID()).'?udb_page='.$page_id;
                    ?>
                    <a class="list-group-item <?php echo $udb_page == $page_id?'selected':''; ?>" href="<?php echo $menu_url; ?>"><?php echo $menu_item['name']; ?></a>
                <?php } ?>

            </div>

            <?php do_action("wpdm_user_dashboard_sidebar") ?>

        </div>
        <div class="col-md-9" id="wpdm-dashboard-contents">


            <?php echo $dashboard_contents; ?>


        </div>





    </div>
</div>
<script>
    jQuery(function($){
        var fullwidth = 0;
        $('body').on('click','#btn-fullwidth', function(){
            fullwidth = fullwidth == 0?1:0;
                $('#wpdm-dashboard-sidebar').toggle();
            $('#wpdm-dashboard-contents').toggleClass('col-md-8','col-md-12');
        });
    });
</script>


 
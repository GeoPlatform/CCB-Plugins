<?php
if (!defined('ABSPATH')) die();
global $btnclass;
$p = serialize($params);
$tid = md5($p);
if(!isset($params['items_per_page'])) $params['items_per_page'] = 20;
$cols = isset($params['cols'])?$params['cols']:'title,file_count,download_count|categories|update_date|download_link';
$colheads = isset($params['colheads'])?$params['colheads']:'Title|Categories|Update Date|Download';
$cols = explode("|", $cols);
$colheads = explode("|", $colheads);
foreach ($cols as $index => &$col){
   $col = explode(",", $col);
   $colheads[$index] = !isset($colheads[$index])?$col[0]:$colheads[$index];
}

$column_positions = array();

//$coltemplate['title'] = $coltemplate['post_title'] = "%the_title%";
$coltemplate['page_link'] = "<a class=\"package-title\" href=\"%s\">%s</a>";

if(isset($params['jstable']) && $params['jstable']==1):

    $datatable_col = ( isset($params['order_by']) && $params['order_by'] == 'title' ) ? '0' : '2';
    $datatable_order = ( isset($params['order']) && $params['order'] == 'DESC' ) ? 'desc' : 'asc';

    ?>
    <script src="<?php echo WPDM_BASE_URL.'assets/js/jquery.dataTables.min.js' ?>"></script>
    <script src="<?php echo WPDM_BASE_URL.'assets/js/dataTables.bootstrap.min.js' ?>"></script>
    <link href="<?php echo WPDM_BASE_URL.'assets/css/jquery.dataTables.min.css' ?>" rel="stylesheet" />
    <style>
        #wpdmmydls-<?php echo $tid; ?>{
            border-bottom: 1px solid #dddddd;
            border-top: 3px solid #bbb;
            font-size: 10pt;
            min-width: 100%;
        }
        #wpdmmydls-<?php echo $tid; ?> .wpdm-download-link img{
            box-shadow: none !important;
            max-width: 100px;
        }
        .w3eden .pagination{
            margin: 0 !important;
        }
        #wpdmmydls-<?php echo $tid; ?> th{
            background-color: #e8e8e8;
            border-bottom: 0;
        }

        #wpdmmydls-<?php echo $tid; ?>_filter input[type=search],
        #wpdmmydls-<?php echo $tid; ?>_length select{
            padding: 5px !important;
            border-radius: 3px !important;
            border: 1px solid #dddddd !important;
        }

        #wpdmmydls-<?php echo $tid; ?> .package-title{
            color:#36597C;
            font-size: 11pt;
            font-weight: 700;
        }
        #wpdmmydls-<?php echo $tid; ?> .small-txt{
            margin-right: 7px;
        }
        #wpdmmydls-<?php echo $tid; ?> #categories{
            max-width: 170px;
        }
        #wpdmmydls-<?php echo $tid; ?> #download_link{
            max-width: 100px;
        }
        #wpdmmydls-<?php echo $tid; ?> .small-txt,
        #wpdmmydls-<?php echo $tid; ?> small{
            font-size: 9pt;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            margin: 0 !important;
            padding: 0 !important;
        }

        @media (max-width: 799px) {
            #wpdmmydls-<?php echo $tid; ?> tr {
                display: block;
                border: 3px solid rgba(0,0,0,0.3) !important;
                margin-bottom: 10px !important;
                position: relative;
            }
            #wpdmmydls-<?php echo $tid; ?> thead{
                display: none;
            }
            #wpdmmydls-<?php echo $tid; ?>,
            #wpdmmydls-<?php echo $tid; ?> td:first-child {
                border: 0 !important;
            }
            #wpdmmydls-<?php echo $tid; ?> td {
                display: block;
            }
        }


    </style>
    <script>
        jQuery(function($){
            var __dt = $('#wpdmmydls-<?php echo $tid; ?>').dataTable({
                responsive: true,
                "order": [[ <?php echo $datatable_col; ?>, "<?php echo $datatable_order; ?>" ]],
                "language": {
                    "lengthMenu": "<?php _e("Display _MENU_ downloads per page",'download-manager')?>",
                    "zeroRecords": "<?php _e("Nothing _START_ to - sorry",'download-manager')?>",
                    "info": "<?php _e("Showing _START_ to _END_ of _TOTAL_ downloads",'download-manager')?>",
                    "infoEmpty": "<?php _e("No downloads available",'download-manager')?>",
                    "infoFiltered": "<?php _e("(filtered from _MAX_ total downloads)",'download-manager');?>",
                    "emptyTable":     "<?php _e("No data available in table",'download-manager');?>",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "loadingRecords": "<?php _e("Loading...",'download-manager'); ?>",
                    "processing":     "<?php _e("Processing...",'download-manager'); ?>",
                    "search":         "<?php _e("Search:",'download-manager'); ?>",
                    "paginate": {
                        "first":      "<?php _e("First",'download-manager'); ?>",
                        "last":       "<?php _e("Last",'download-manager'); ?>",
                        "next":       "<?php _e("Next",'download-manager'); ?>",
                        "previous":   "<?php _e("Previous",'download-manager'); ?>"
                    },
                    "aria": {
                        "sortAscending":  " : <?php _e("activate to sort column ascending",'download-manager'); ?>",
                        "sortDescending": ": <?php _e("activate to sort column descending",'download-manager'); ?>"
                    }
                },
                "iDisplayLength": <?php echo $params['items_per_page'] ?>,
                "aLengthMenu": [[<?php echo $params['items_per_page']; ?>, 10, 25, 50, -1], [<?php echo $params['items_per_page']; ?>, 10, 25, 50, "<?php _e("All",'download-manager'); ?>"]]
            });

        });
    </script>
<?php endif; ?>

<div class="w3eden">
    <div class="container-fluid" id="wpdm-all-packages">
        <table id="wpdmmydls-<?php echo $tid; ?>" class="table table-striped wpdm-all-packages-table">
            <thead>
            <tr>
                <?php foreach ($colheads as $ix => $colhead){ ?>
                <th  id="<?php echo $cols[$ix][0]; ?>" class="<?php if($ix > 0) echo 'hidden-sm hidden-xs'; ?>"><?php _e($colhead,'download-manager'); ?></th>
                <?php } ?>
                <!-- th class="hidden-sm hidden-xs"><?php _e('Categories','download-manager'); ?></th>
                <th class="hidden-xs"><?php _e('Create Date','download-manager'); ?></th>
                <th style="width: 100px;"><?php _e('Download','download-manager'); ?></th -->
            </tr>
            </thead>
            <tbody>
            <?php


            $cfurl = get_permalink();

            if(strpos($cfurl, "?")) $cfurl.="&wpdmc="; else $cfurl.="?wpdmc=";
            $query_params = array("post_type"=>"wpdmpro","posts_per_page"=>$items,"offset"=>$offset);
            if(isset($tax_query)) $query_params['tax_query'] = $tax_query;
            $query_params['orderby'] = (isset($params['order_by']))?$params['order_by']:'date';

            $order_field = isset($params['order_by']) ? $params['order_by'] : 'date';
            $order = isset($params['order']) ? $params['order'] : 'DESC';

            $order_fields = array('__wpdm_download_count','__wpdm_view_count','__wpdm_package_size_b');
            if(!in_array( "__wpdm_".$order_field, $order_fields)) {
                $query_params['orderby'] = $order_field;
                $query_params['order'] = $order;
            } else {
                $query_params['orderby'] = 'meta_value_num';
                $query_params['meta_key'] = "__wpdm_".$order_field;
                $query_params['order'] = $order;
            }

            $q = new WP_Query($query_params);
            $total_files = $q->found_posts;
            global $post;
            while ($q->have_posts()): $q->the_post();

                $ext = "unknown";
                $data = (array)$post + wpdm_custom_data(get_the_ID());

                if(isset($data['files'])&&count($data['files'])){
                    if(count($data['files']) == 1) {
                        $tmpavar = $data['files'];
                        $ffile = $tmpvar = array_shift($tmpavar);
                        $tmpvar = explode(".", $tmpvar);
                        $ext = count($tmpvar) > 1 ? end($tmpvar) : $ext;
                        if(!file_exists(WPDM_BASE_DIR."assets/file-type-icons/".$ext.".svg")){
                          $ext = "unknown";
                          if(strstr($ffile, "youtu")) $ext = "video";
                          else if(strstr($ffile, "://")) $ext = "link";
                        }
                    } else
                        $ext = 'zip';
                } else $data['files'] = array();

                $ext = isset($data['icon']) && $data['icon'] != ''?$data['icon']:$ext.".svg";

                $cats = wp_get_post_terms(get_the_ID(), 'wpdmcategory');
                $fcats = array();

                foreach($cats as $cat){
                    $fcats[] = "<a class='sbyc' href='{$cfurl}{$cat->slug}'>{$cat->name}</a>";
                }
                $cats = @implode(", ", $fcats);
                $data['ID'] = $data['id'] = get_the_ID();
                $data['title'] = get_the_title();
                $author = get_user_by('id', get_the_author_meta('ID'));
                $data['author_name'] = $author->display_name;
                $data['author_profile_url'] = get_author_posts_url($author->ID);
                $data['avatar_url'] = get_avatar_url($author->user_email);
                $data['author_pic'] = "<img style='width: 32px' class='ttip' src='{$data['avatar_url']}' alt='{$data['author_name']}' title='{$data['author_name']}' />"; //get_avatar($author->user_email, 32, '', $author->display_name);
                $data['author_package_count'] = count_user_posts( $author->ID , "wpdmpro"  );
                if($ext=='') $ext = 'unknown.svg';
                if($ext==basename($ext) && file_exists(WPDM_BASE_DIR."assets/file-type-icons/".$ext)) $ext = plugins_url("download-manager/assets/file-type-icons/".$ext);
                else $ext = plugins_url("download-manager/assets/file-type-icons/unknown.svg");
                $data['download_url'] = '';
                $data['download_link'] = \WPDM\Package::downloadLink($data['ID']);//DownloadLink($data, 0);
                $data = apply_filters("wpdm_after_prepare_package_data", $data);
                $download_link = $data['download_link'];
                if(isset($data['base_price']) && $data['base_price'] > 0 && function_exists('wpdmpp_currency_sign')) $download_link = "<a href='".$data['addtocart_url']."' class='btn btn-sm btn-info'>Buy ( ".$data['currency'].$data['effective_price']." )</a>";
                if($download_link != 'blocked'){
                    ?>

                    <tr class="__dt_row">
                        <?php
                        $tcols = $cols;
                        array_shift($tcols);
                        foreach ($cols as $colx => $cold){
                            $dor = array('publish_date' => strtotime(get_the_date()), 'create_date' => strtotime(get_the_date()), 'update_date' => strtotime(get_the_modified_date('', get_the_ID())));
                            ?>
                        <td <?php if(in_array($cold[0], array('publish_date', 'update_date','create_date'))) { ?> data-order="<?php echo $dor[$cold[0]]; ?>" <?php } ?> class="__dt_col_<?php echo $colx; ?> __dt_col" <?php if($colx == 0) { ?>style="background-image: url('<?php echo $ext ; ?>');background-size: 40px;background-position: 5px 8px;background-repeat:  no-repeat;padding-left: 52px;line-height: normal;"<?php } ?>>
                            <?php

                            foreach ($cold as $cx => $c){
                                $cxc = ($cx > 0)?'small-txt':'';
                                switch ($c) {
                                    case 'title':
                                        echo "<strong>".get_the_title()."</strong><br/>";
                                        break;
                                    case 'page_link':
                                        echo "<a class=\"package-title\" href='".get_the_permalink(get_the_ID())."'>".get_the_title()."</a><br/>";
                                        break;
                                    case 'file_count':
                                        if($cx > 0)
                                            echo "<span class='__dt_file_count {$cxc}'><i class=\"far fa-copy\"></i>&nbsp;". count($data['files'])." " . __('file(s)','download-manager')." </span>";
                                        else
                                            echo "<span class=\"hidden-md hidden-lg td-mobile\">{$colheads[$colx]}: </span><span class='__dt_file_count {$cxc}'>".count($data['files'])." </span>";
                                        break;
                                    case 'download_count':
                                        if($cx > 0)
                                            echo "<span class='__dt_download_count {$cxc}'><i class=\"fa fa-download\"></i>&nbsp;". (isset($data['download_count'])?$data['download_count']:0)." ".(isset($data['download_count']) && $data['download_count'] > 1 ?  __('downloads','download-manager') : __('download','download-manager'))."</span>";
                                        else
                                            echo "<span class=\"hidden-md hidden-lg td-mobile\">{$colheads[$colx]}: </span><span class='__dt_download_count {$cxc}'>{$data['download_count']}</span>";
                                        break;
                                    case 'view_count':
                                        if($cx > 0)
                                            echo "<span class='__dt_view_count {$cxc}'><i class=\"fa fa-eye\"></i>&nbsp;". (isset($data['view_count'])?$data['view_count']:0)." ".(isset($data['view_count']) && $data['view_count'] > 1 ?  __('views','download-manager') : __('view','download-manager'))."</span>";
                                        else
                                            echo "<span class=\"hidden-md hidden-lg td-mobile\">{$colheads[$colx]}: </span><span class='__dt_view_count'>{$data['view_count']}</span>";
                                        break;
                                    case 'categories':
                                        echo "<span class='__dt_categories {$cxc}'>".$cats."</span>";
                                        break;
                                    case 'update_date':
                                        echo "<span class='__dt_update_date {$cxc}'>".get_the_modified_date('', get_the_ID())."</span>";
                                        break;
                                    case 'publish_date':
                                        echo "<span class='__dt_publish_date {$cxc}'>".get_the_date()."</span>";
                                        break;
                                    case 'download_link':
                                        echo $download_link;
                                        break;
                                    default:
                                        if(isset($data[$c])) {
                                            if ($cx > 0)
                                                echo "<span class='__dt_{$c} {$cxc}'>" . $data[$c] . "</span>";
                                            else
                                                echo $data[$c];
                                        }
                                        break;


                            }}
                            if($colx == 0) echo '<div class="hidden-md hidden-lg td-mobile"></div>';
                                        ?>


                        </td>
                        <?php }  ?>

                    </tr>
                <?php } endwhile; ?>
            <?php if((!isset($params['jstable']) || $params['jstable']==0) && $total_files==0): ?>
                <tr>
                    <td colspan="4" class="text-center">

                        <?php echo isset($params['no_data_msg']) && $params['no_data_msg']!=''?$params['no_data_msg']:__('No Packages Found','download-manager'); ?>

                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php
        global $wp_rewrite,$wp_query;

        isset($wp_query->query_vars['paged']) && $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

        $pagination = array(
            'base' => @add_query_arg('paged','%#%'),
            'format' => '',
            'total' => ceil($total_files/$items),
            'current' => $cp,
            'show_all' => false,
            'type' => 'list',
            'prev_next'    => True,
            'prev_text' => '<i class="fa fa-long-arrow-left"></i> '.__('Previous', 'download-manager'),
            'next_text' => __('Next', 'download-manager').' <i class="fa fa-long-arrow-right"></i>',
        );

        if( $wp_rewrite->using_permalinks() )
            $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');

        if( !empty($wp_query->query_vars['s']) )
            $pagination['add_args'] = array('s'=>get_query_var('s'));

        echo  wpdm_paginate_links( $pagination['total'], $items, $cp, 'paged');

        wp_reset_query();
        ?>

    </div>
</div>

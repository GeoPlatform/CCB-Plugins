<?php
if (!defined('ABSPATH')) die();
/**
 * User: shahnuralam
 * Date: 1/16/18
 * Time: 12:33 AM
 */
error_reporting(0);
global $post;
$post = get_post(wpdm_query_var('__wpdmlo'));
setup_postdata($post);
$pack = new \WPDM\Package();
$pack->Prepare(get_the_ID());

?>
<!DOCTYPE html>
<html style="background: transparent">
<head>
    <title>Download <?php the_title(); ?></title>
    <link rel="stylesheet" href="<?php echo WPDM_BASE_URL; ?>assets/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo WPDM_BASE_URL; ?>assets/css/front.css" />
    <link rel="stylesheet" href="<?php echo WPDM_BASE_URL; ?>assets/fontawesome/css/fontawesome.min.css" />
    <script src="<?php echo includes_url(); ?>/js/jquery/jquery.js"></script>
    <script src="<?php echo includes_url(); ?>/js/jquery/jquery.form.min.js"></script>
    <script src="<?php echo WPDM_BASE_URL; ?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo WPDM_BASE_URL; ?>assets/js/front.js"></script>
    <?php if((isset($pack->PackageData['form_lock']) && $pack->PackageData['form_lock'] == 1)  || $pack->PackageData['base_price'] > 0) wp_head(); ?>
    <style>
        html, body{
            overflow: visible;
            height: 100%;
            width: 100%;
            padding: 0;
            margin: 0;
            font-family: 'Josefin Sans', sans-serif;
            font-weight: 300;
            font-size: 10pt;
        }
        h4.modal-title{
            font-family: 'Josefin Sans', sans-serif;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555555;
            font-size: 11pt;
            display: inline-block;
        }

        .w3eden label{
            font-weight: 400;
        }
        img{
            max-width: 100%;
        }
        .modal-backdrop{
            background: rgba(0,0,0,0.5);
        }


        .modal.fade{
            opacity:1;
        }
        .modal.fade .modal-dialog {
            -webkit-transform: translate(0);
            -moz-transform: translate(0);
            transform: translate(0);
        }

        .modal {
            text-align: center;
            padding: 0!important;
        }

        .wpdm-social-lock.btn {
            display: block;
            width: 100%;
        }

        @media (min-width: 768px) {
            .modal:before {
                content: '';
                display: inline-block;
                height: 100%;
                vertical-align: middle;
                margin-right: -4px;
            }

            .modal-dialog {
                display: inline-block;
                text-align: left;
                vertical-align: middle;
            }

            .wpdm-social-lock.btn {
                display: inline-block;
                width: 47%;
            }
        }

        @-moz-keyframes spin {
            from { -moz-transform: rotate(0deg); }
            to { -moz-transform: rotate(360deg); }
        }
        @-webkit-keyframes spin {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
            from {transform:rotate(0deg);}
            to {transform:rotate(360deg);}
        }
        .spin{
            -webkit-animation-name: spin;
            -webkit-animation-duration: 2000ms;
            -webkit-animation-iteration-count: infinite;
            -webkit-animation-timing-function: linear;
            -moz-animation-name: spin;
            -moz-animation-duration: 2000ms;
            -moz-animation-iteration-count: infinite;
            -moz-animation-timing-function: linear;
            -ms-animation-name: spin;
            -ms-animation-duration: 2000ms;
            -ms-animation-iteration-count: infinite;
            -ms-animation-timing-function: linear;

            animation-name: spin;
            animation-duration: 2000ms;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
            display: inline-block;
        }


        .w3eden .panel-default {
            border-radius: 3px;
            margin-top: 10px !important;
        }
        .w3eden .panel-default:last-child{
            margin-bottom: 0 !important;
        }
        .w3eden .panel-default .panel-heading{
            letter-spacing: 0.5px;
            font-weight: 600;
            background-color: #f6f8f9;
        }

        .w3eden .panel-default .panel-footer{
            background-color: #fafafa;
        }

        .btn{
            outline: none !important;
        }
        .w3eden .panel{
            margin-bottom: 0;
        }
        .w3eden .modal-header{
            border: 0;
        }
        .w3eden .modal-content{
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            border: 0;
            border-radius: 6px;
            background: rgb(255,255,255);
            background: -moz-linear-gradient(-45deg,  rgba(255,255,255,1) 0%, rgba(243,243,243,1) 50%, rgba(237,237,237,1) 51%, rgba(255,255,255,1) 100%);
            background: -webkit-linear-gradient(-45deg,  rgba(255,255,255,1) 0%,rgba(243,243,243,1) 50%,rgba(237,237,237,1) 51%,rgba(255,255,255,1) 100%);
            background: linear-gradient(135deg,  rgba(255,255,255,1) 0%,rgba(243,243,243,1) 50%,rgba(237,237,237,1) 51%,rgba(255,255,255,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ffffff',GradientType=1 );
            overflow: hidden;
            max-width: 100%;
        }
        .w3eden .modal-body{
            max-height:  calc(100vh - 210px);
            overflow-y: auto;
            padding-top: 0 !important;
        }


        .w3eden .input-group-lg .input-group-btn .btn{
            border-top-right-radius: 4px !important;
            border-bottom-right-radius: 4px !important;
        }
        .w3eden .wpforms-field-medium{
            max-width: 100% !important;
            width: 100% !important;
        }

        .w3eden .input-group.input-group-lg .input-group-btn .btn {
            font-size: 11pt !important;
        }



    </style>
    <?php do_action("wpdm_modal_iframe_head"); ?>
</head>
<body class="w3eden" style="background: transparent">

<div class="modal fade" id="wpdm-locks" tabindex="-1" role="dialog" aria-labelledby="wpdm-optinmagicLabel">
    <div class="modal-dialog" role="document" style="width: <?php echo isset($pack->PackageData['terms_lock']) && $pack->PackageData['terms_lock'] == 1?395:365; ?>px;max-width: calc(100% - 20px);">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img style="width: 20px" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMyIDMyIiBoZWlnaHQ9IjMycHgiIGlkPSLQodC70L7QuV8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMycHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnIGlkPSJDYW5jZWwiPjxwYXRoIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTE2LDBDNy4xNjMsMCwwLDcuMTYzLDAsMTZjMCw4LjgzNiw3LjE2MywxNiwxNiwxNiAgIGM4LjgzNiwwLDE2LTcuMTYzLDE2LTE2QzMyLDcuMTYzLDI0LjgzNiwwLDE2LDB6IE0xNiwzMEM4LjI2OCwzMCwyLDIzLjczMiwyLDE2QzIsOC4yNjgsOC4yNjgsMiwxNiwyczE0LDYuMjY4LDE0LDE0ICAgQzMwLDIzLjczMiwyMy43MzIsMzAsMTYsMzB6IiBmaWxsPSIjMTIxMzEzIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz48cGF0aCBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMi43MjksMjEuMjcxbC01LjI2OC01LjI2OWw1LjIzOC01LjE5NSAgIGMwLjM5NS0wLjM5MSwwLjM5NS0xLjAyNCwwLTEuNDE0Yy0wLjM5NC0wLjM5LTEuMDM0LTAuMzktMS40MjgsMGwtNS4yMzEsNS4xODhsLTUuMzA5LTUuMzFjLTAuMzk0LTAuMzk2LTEuMDM0LTAuMzk2LTEuNDI4LDAgICBjLTAuMzk0LDAuMzk1LTAuMzk0LDEuMDM3LDAsMS40MzJsNS4zMDEsNS4zMDJsLTUuMzMxLDUuMjg3Yy0wLjM5NCwwLjM5MS0wLjM5NCwxLjAyNCwwLDEuNDE0YzAuMzk0LDAuMzkxLDEuMDM0LDAuMzkxLDEuNDI5LDAgICBsNS4zMjQtNS4yOGw1LjI3Niw1LjI3NmMwLjM5NCwwLjM5NiwxLjAzNCwwLjM5NiwxLjQyOCwwQzIzLjEyMywyMi4zMDgsMjMuMTIzLDIxLjY2NywyMi43MjksMjEuMjcxeiIgZmlsbD0iIzEyMTMxMyIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9nPjxnLz48Zy8+PGcvPjxnLz48Zy8+PGcvPjwvc3ZnPg==" alt="&times;" /></span></button>
                <h4 class="modal-title"><?php echo ($pack->PackageData['base_price'] > 0)? __('Buy','download-manager'): __('Download','download-manager'); ?></h4>
                <div style="letter-spacing: 1px;font-weight: 400" class="color-blue"><?php echo $pack->PackageData['title']; ?></div>
            </div>
            <div class="modal-body">
                <?php
                echo $pack->PackageData['download_link_extended'];
                ?>
            </div>

        </div>

    </div>
    <?php

    ?>
</div>

<script>

    jQuery(function ($) {

        $('a').each(function () {
            $(this).attr('target', '_blank');
        });

        $('body').on('click','a', function () {
            $(this).attr('target', '_blank');
        });

        $('#wpdm-locks').on('hidden.bs.modal', function (e) {
            window.parent.hideLockFrame();
        });


    });

    function showModal() {
        jQuery('#wpdm-locks').modal('show');
    }
    showModal();
</script>
<div style="display: none">
    <?php  if((isset($pack->PackageData['form_lock']) && $pack->PackageData['form_lock'] == 1)  || $pack->PackageData['base_price'] > 0) wp_footer(); ?>
    <?php do_action("wpdm_modal_iframe_footer"); ?>
</div>
</body>
</html>

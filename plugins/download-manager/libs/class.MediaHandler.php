<?php
namespace WPDM\libs;

class MediaHandler {

    function __construct(){

        add_action('init', array($this, 'PlayAudio'));

    }

    function PlayAudio(){
        if(isset($_REQUEST['wpdm_play_audio']))
            $url = \WPDM\libs\Crypt::Decrypt($_REQUEST['wpdm_play_audio']);
            echo do_shortcode('[audio');
        die();
    }




} 
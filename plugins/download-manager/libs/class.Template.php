<?php

namespace WPDM;


class Template
{
    public $Vars;

    function __construct(){
        return $this;
    }

    public static function locate($file, $tpldir = ''){

        if(file_exists(get_stylesheet_directory().'/download-manager/'.$file))
            $path = get_stylesheet_directory().'/download-manager/'.$file;
        else if(file_exists(get_template_directory().'/download-manager/'.$file))
            $path = get_template_directory().'/download-manager/'.$file;
        else if($tpldir !='' && file_exists($tpldir.'/'.$file))
            $path = $tpldir.'/'.$file;
        else if($tpldir !='' && file_exists(get_template_directory().'/download-manager/'.$tpldir.'/'.$file))
            $path = get_template_directory().'/download-manager/'.$tpldir.'/'.$file;
        else $path = WPDM_BASE_DIR.'tpls/'.$file;

        return $path;

    }

    function assign($var, $val){
        $this->Vars[$var] = $val;
        return $this;
    }

    function fetch($template, $tpldir = ''){
        $template = self::locate($template, $tpldir);
        if(is_array($this->Vars))
            extract($this->Vars);
        ob_start();
        include $template;
        return ob_get_clean();
    }

    function display($template, $tpldir = ''){
        echo $this->fetch($template, $tpldir);
    }

}
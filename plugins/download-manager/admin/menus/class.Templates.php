<?php

namespace WPDM\admin\menus;


class Templates
{

    function __construct()
    {

    }


    public static function Dropdown($params)
    {
        extract($params);
        $type = isset($type) ? $type : 'link';
        $ltpldir = get_stylesheet_directory() . '/download-manager/' . $type . '-templates/';
        if (!file_exists($ltpldir))
            $ltpldir = WPDM_BASE_DIR . '/tpls/' . $type . '-templates/';
        $ctpls = scandir($ltpldir);
        array_shift($ctpls);
        array_shift($ctpls);
        $name = isset($name)?$name:$type.'_template';
        $css = isset($css)?"style='$css'":'';
        $id = isset($id)?$id:uniqid();
        $default = $type == 'link'?'link-template-calltoaction3.php':'page-template-default.php';
        $html = "<select name='$name' id='$id' class='form-control template {$type}_template' {$css}><option value='$default'>Select ".ucfirst($type)." Template</option>";
        $data = array();

        foreach ($ctpls as $ctpl) {
            $tmpdata = file_get_contents($ltpldir . $ctpl);
            $regx = "/WPDM.*Template[\s]*:([^\-\->]+)/";
            if (preg_match($regx, $tmpdata, $matches)) {
                $data[$ctpl] = $matches[1];
                $oselected = isset($selected) && $selected == $ctpl ? 'selected=selected':'';
                $html .= "<option value='{$ctpl}' {$oselected}>{$matches[1]}</option>";
            }
        }
        $html .= "</select>";
        return isset($data_type) && $data_type == 'ARRAY'? $data : $html;
    }
}
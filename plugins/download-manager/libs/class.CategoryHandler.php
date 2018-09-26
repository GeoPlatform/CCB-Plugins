<?php
namespace WPDM\libs;

class CategoryHandler {

    function __construct() {

    }


    public static function  getAllowedRoles( $term_id ){
        $MetaData = get_option( "__wpdmcategory" );
        $MetaData = maybe_unserialize($MetaData);
        $roles = maybe_unserialize(get_term_meta($term_id, '__wpdm_access', true));
        if(!is_array($roles))
            $roles = isset($MetaData[$term_id], $MetaData[$term_id]['access']) && is_array($MetaData[$term_id]['access']) ? $MetaData[$term_id]['access'] : array();
        $roles = apply_filters("wpdm_categoryhandler_getallowedroles", $roles, $term_id);
        return $roles;
    }

    function parentRoles($cid){
        if(!$cid) return array();
        $roles = array();
        $parents = \WPDM\libs\CategoryHandler::categoryParents($cid, 0);
        $MetaData = get_option( "__wpdmcategory" );
        $MetaData = maybe_unserialize($MetaData);
        foreach ($parents as $catid) {
            $croles = maybe_unserialize(get_term_meta($catid, '__wpdm_access', true));
            if(!is_array($roles))
                $croles = isset($MetaData[$catid], $MetaData[$catid]['access']) && is_array($MetaData[$catid]['access']) ? $MetaData[$catid]['access'] : array();
            $roles += $croles;
        }
        return array_unique($roles);
    }

    public static function  icon( $term_id ){
        $icon = get_term_meta($term_id, '__wpdm_icon', true);
        if($icon == '') {
            $MetaData = get_option("__wpdmcategory");
            $MetaData = maybe_unserialize($MetaData);
            $icon = get_term_meta($term_id, '__wpdm_icon', true);
            if($icon == '')
                $icon = isset($MetaData[$term_id]['icon'])?$MetaData[$term_id]['icon']:'';
        }
        return $icon;
    }

    public static function categoryParents($cid, $offset = 1){
        $CategoryBreadcrumb = array();
        if($cid > 0) {
            $cat = get_term($cid, 'wpdmcategory');
            $parent = $cat->parent;
            $CategoryParents[] = $cat->term_id;
            while ($parent > 0) {
                $cat = get_term($parent, 'wpdmcategory');
                $CategoryParents[] = $cat->term_id;
                $parent = $cat->parent;
            }
            if($offset)
                array_pop($CategoryBreadcrumb);
            $CategoryParents = array_reverse($CategoryParents);
        }

        return $CategoryParents;

    }

    public static function userHasAccess($term_id){
        global $current_user;
        $roles = self::GetAllowedRoles($term_id);
    }

    public static function categoryBreadcrumb($cid, $offset = 1){
        $CategoryBreadcrumb = array();
        if($cid > 0) {
            $cat = get_term($cid, 'wpdmcategory');
            $parent = $cat->parent;
            $CategoryBreadcrumb[] = "<a href='#' class='folder' data-cat='{$cat->term_id}'>{$cat->name}</a>";
            while ($parent > 0) {
                $cat = get_term($parent, 'wpdmcategory');
                $CategoryBreadcrumb[] = "<a href='#' class='folder' data-cat='{$cat->term_id}'>{$cat->name}</a>";
                $parent = $cat->parent;
            }
            if($offset)
                array_pop($CategoryBreadcrumb);
            $CategoryBreadcrumb = array_reverse($CategoryBreadcrumb);
        }
        echo "<a href='#' class='folder' data-cat='0'>Home</a>&nbsp; <i class='fa fa-angle-right'></i> &nbsp;".implode("&nbsp; <i class='fa fa-angle-right'></i> &nbsp;", $CategoryBreadcrumb);

    }




}


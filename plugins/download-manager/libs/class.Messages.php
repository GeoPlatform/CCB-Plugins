<?php

class WPDM_Messages {
    public static function Message($msg, $die = 0){
        if(is_array($msg))
            $message = "<div class='w3eden'><div class='alert alert-{$msg['type']}' data-title='{$msg['title']}'>{$msg['message']}</div></div>";
        else
            $message = $msg;
        if($die==-1) return $message;

        if($die==1) {
            $t = new \WPDM\Template();
            if(!is_array($msg))
                $t->assign('message', $message)->display("message.php");
            else
                $t->assign('message', $msg['message'])
                    ->assign('title', $msg['title'])
                    ->assign('type', $msg['type'])
                    ->assign('icon', $msg['icon'])
                    ->display("message.php");
            die();
        }
        return true;
    }

    public static function Error($msg, $die = 0){
        if(!is_array($msg)) {
            $message = $msg;
            $msg = array();
            $msg['message'] = $message;
        }
        if(!isset($msg['title'])) $msg['title'] = __('Operation Failed!','download-manager');
        $msg['type'] = 'danger';
        $msg['icon'] = 'exclamation-triangle';
        return self::Message($msg, $die);
    }

    public static function Warning($msg, $die = 0){
        if(!is_array($msg)) {
            $message = $msg;
            $msg = array();
            $msg['message'] = $message;
        }
        if(!isset($msg['title'])) $msg['title'] =  __('Warning!','download-manager');
        $msg['type'] = 'warning';
        $msg['icon'] = 'exclamation-circle';
        return self::Message($msg, $die);
    }

    public static function Info($msg, $die = 0){
        if(!is_array($msg)) {
            $message = $msg;
            $msg = array();
            $msg['message'] = $message;
        }
        if(!isset($msg['title'])) $msg['title'] =  __('Attention!','download-manager');
        $msg['type'] = 'info';
        $msg['icon'] = 'info-circle';
        return self::Message($msg, $die);
    }

    public static function Success($msg, $die = 0){
        if(!is_array($msg)) {
            $message = $msg;
            $msg = array();
            $msg['message'] = $message;
        }
        if(!isset($msg['title'])) $msg['title'] = __('Awesome!','download-manager');
        $msg['type'] = 'success';
        $msg['icon'] = 'check-circle';
        return self::Message($msg, $die);
    }
} 
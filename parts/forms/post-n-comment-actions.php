<?php
    global $current_user;
    get_currentuserinfo();

    $buttons = array();

    $acts = array('upvote', 'downvote', 'follow');

    if(isset($actions) && !empty($actions)) {
        foreach($actions as $action) {
            switch($action->action) {
                case 'upvote':
                    $acts['upvote']['action'] = $action;

                    break;
                case 'downvote':
                    $acts['downvote']['action'] = $action;

                    break;
                case 'follow':
                    $acts['follow']['text'] = 'following';
                    $acts['follow']['myaction'] = ' active';

                    break;
                default:
                    break;
            }

            if(isset($action->user) && !is_string($action->user) && get_class($action->user) === 'UserAction') {
                if(is_user_logged_in()) {
                    if($current_user->id == $action->user->userId) {
                        switch($action->action) {
                            case 'upvote':
                                $acts['upvote']['myaction'] = ' active';

                                break;
                            case 'downvote':
                                $acts['downvote']['myaction'] = ' active';

                                break;
                            case 'follow':
                                $acts['follow']['text'] = 'following';
                                $acts['follow']['myaction'] = ' active';

                                break;
                            default:
                                break;
                        }
                    }
                }
            } elseif(isset($action->user) && $action->user->id != 0) {
                switch($action->action) {
                    case 'upvote':
                        $acts['upvote']['myaction'] = ' active';
                        $acts['upvote']['nli_reset'] = ',nli_reset:\'deactivate\'';

                        break;
                    case 'downvote':
                        $acts['downvote']['myaction'] = ' active';
                        $acts['downvote']['nli_reset'] = ',nli_reset:\'deactivate\'';

                        break;
                    case 'follow':
                        $acts['follow']['text'] = 'following';
                        $acts['follow']['myaction'] = ' active';

                        break;
                    default:
                        break;
                }
            } else {

            }
        }
    }

    $downvoteTotal = isset($acts['downvote']['action']->total) ? $acts['downvote']['action']->total : 0;
    $upvoteTotal = isset($acts['upvote']['action']->total) ? $acts['upvote']['action']->total : 0;

    $myActionDownvote = isset($acts['downvote']['myaction']) ? $acts['downvote']['myaction'] : '';
    $myActionFollow = isset($acts['follow']['myaction']) ? $acts['follow']['myaction'] : '';
    $myActionFollowText = isset($acts['follow']['text']) ? 'following' : 'follow';
    $myActionUpvote = isset($acts['upvote']['myaction']) ? $acts['upvote']['myaction'] : '';

    $nliDownvote = isset($acts['downvote']['nli_reset']) ? $acts['downvote']['nli_reset'] : '';
    $nliUpvote = isset($acts['upvote']['nli_reset']) ? $acts['upvote']['nli_reset'] : '';

    if ( isset( $options ) && ( !empty( $options ) ) ) {
        if ( in_array( "reply", $options ) ) {
        	if(! is_user_logged_in()) {
            	$buttons[] = '<div class="reply link-emulator" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:\'POST\', data:{action: \'get_template_ajax\', template: \'page-login\'}}}" shc:gizmo="moodle">Reply</div>';
        	} else {
        		$buttons[] = '<div class="reply link-emulator" >Reply</div>';
        	}
        }
        if ( in_array( "follow", $options ) ) {
            if(! is_user_logged_in()) {
                $buttons[] = '<button
                                type="button"
                                name="button1"
                                value="follow"
                                title="Follow this '.$type.'"
                                id="follow-question-'.$id.'"
                                class="follow"
                                shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:\'POST\', data:{action: \'get_template_ajax\', template: \'page-login\'}}}"
                                shc:gizmo="moodle">
                                '.$myActionFollowText.'</button>';
           	} else {
                $buttons[] = '<button
                                type="button"
                                name="button1"
                                value="follow"
                                title="Follow this '.$type.'"
                                id="follow-question-'.$id.'"
                                class="follow'.$myActionFollow.'"
                                shc:gizmo:options="{actions:{post:{id:'.$id.',name:\'follow\',sub_type:\''.$sub_type.'\',type:\''.$type.'\'}}}"
                                shc:gizmo="actions">
                                '.$myActionFollowText.'</button>';
           	}
        }
        if ( in_array( "share", $options ) ) {
            $buttons[] = return_partial( 'parts/share', array( "url" => $url ) );
        }
        if ( in_array( "flag", $options ) ) {
            $buttons[] = '<button type="button" name="button1" value="flag" title="Flag this ' . $type . '" id="flag-comment-' . $id . '" class="flag">flag</button>';
        }
        if ( in_array( "downvote", $options ) ) {
            $buttons[] = '<label class="metainfo" for="downvote-comment-' . $id . '">('.$downvoteTotal.')</label><button shc:gizmo="actions" shc:gizmo:options="{actions:{post:{id:'.$id.',name:\'downvote\',sub_type:\''.$sub_type.'\',type:\''.$type.'\''.$nliDownvote.'}}}" type="button" name="downvote" value="down vote" title="Down vote this ' . $type . '" id="downvote-comment-' . $id . '" class="downvote'.$myActionDownvote.'">down vote</button>';
        }
        if ( in_array( "upvote", $options ) ) {
            $buttons[] = '<label class="metainfo" shc:gizmo="actions" for="upvote-comment-' . $id . '">('.$upvoteTotal.')</label><button shc:gizmo="actions" shc:gizmo:options="{actions:{post:{id:'.$id.',name:\'upvote\',sub_type:\''.$sub_type.'\',type:\''.$type.'\''.$nliUpvote.'}}}" type="button" name="upvote" value="helpful" title="Up vote this ' . $type . '" id="upvote-comment-' . $id . '" class="upvote'.$myActionUpvote.'">helpful</button>';
        }
    }
    
    if ( !empty( $buttons ) ) {
?>

<form class="actions clearfix" id="comment-<?php echo $id; ?>" method="post" action="">

    <?php echo implode( $buttons, "\r    " ) ?>

</form>

<?php
    }
?>
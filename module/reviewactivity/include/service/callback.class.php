<?php
defined('PHPFOX') or exit('NO DICE!');
class ReviewActivity_Service_Callback extends Phpfox_Service 
{
    public function getProfileLink()
    {
        
        return 'profile.reviewactivity';
    }    
    public function getActivityFeed($aRow)
    {
        $aRow = Phpfox::getService('reviewactivity')->getReviewId($aRow['item_id']);
        if(!isset($aRow['user_id']))
        {
            return false;
        }
        $oTpl = Phpfox::getLib('template');
        $sYear = $aRow['year'];
        $sPrivacyList = "0,1,2,3,4";
        $aUserReviewPhotos = phpfox::getService('reviewactivity')->getUserPhotos($aRow['user_id'],$sPrivacyList,4,$sYear);
        
        $oTpl->assign(array(
            'aUserReviewPhotos' => $aUserReviewPhotos,
            'sReviewUserName' => $aRow['user_name'],
            'sReviewYear' => $sYear,
            'bIsViewDescription' => true,
            'bFeedView' => true,
            'aRow' => $aRow,
            'bIsTimeline' => Phpfox::getService('profile')->timeline(),
        ));
        $sOutput = $oTpl->getTemplate('reviewactivity.block.entry', true);
      
        $aReturn = array(
            'feed_title' => '',
            'feed_info' => Phpfox::getPhrase('reviewactivity.shared_review_activities'),
            'feed_link' => phpfox::getLib('url')->makeUrl($aRow['user_name'].'.reviewactivity'),
            'total_comment' => $aRow['total_comment'],
            'feed_total_like' => $aRow['total_like'],
            'feed_is_liked' => $aRow['is_liked'],
            'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'feed/link.png', 'return_url' => true)),
            //'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/asking.png', 'return_url' => true)),
            //'feed_icon' => Phpfox::getLib('image.helper')->display(array(Phpfox::getParam('core.path'). => 'module/asking.png', 'return_url' => true)),
            'time_stamp' => $aRow['time_stamp'],            
            'enable_like' => true,            
            'comment_type_id' => 'reviewactivity',
            'like_type_id' => 'reviewactivity',
            'feed_custom_html' => $sOutput
        );
        return $aReturn;
    }
    public function getAjaxCommentVar()
    {
        return 'reviewactivity.can_post_comment_on_reviewactivity';
    }
    public function getCommentItem($iId)
    {
        $aReview = $this->database()->select('review_id AS comment_item_id, user_id AS comment_user_id')
            ->from(phpfox::getT('review_activity'))
            ->where('review_id = ' . (int) $iId)
            ->execute('getSlaveRow');
        
        $aReview['comment_view_id'] = 1;
            
        return $aReview;
    }   
    public function addComment($aVals, $iUserId = null, $sUserName = null)
    {
        $aRow = $this->database()->select('m.review_id, m.description, u.full_name, u.user_id, u.gender, u.user_name')
            ->from(phpfox::getT('review_activity'), 'm')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
            ->where('m.review_id = ' . (int) $aVals['item_id'])
            ->execute('getSlaveRow');
            
        if (!isset($aRow['review_id']))
        {
            return false;
        }
        
        (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add($aVals['type'] . '_comment', $aVals['comment_id']) : null);
        
        if ($iUserId === null)
        {
            $iUserId = Phpfox::getUserId();
        }
        if (empty($aVals['parent_id']))
        {
            $this->database()->updateCounter('review_activity', 'total_comment', 'review_id', $aVals['item_id']);
        }
        
        (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add($aVals['type'] . '_comment', $aVals['comment_id'], 0, 0, 0, $iUserId) : null);
        
        // Send the user an email
        $sLink = phpfox::getLib('url')->makeUrl($aRow['user_name'].'.reviewactivity');
        
        Phpfox::getService('comment.process')->notify(array(
                'user_id' => $aRow['user_id'],
                'item_id' => $aRow['review_id'],
                'owner_subject' => Phpfox::getPhrase('reviewactivity.full_name_commented_on_your_review_activities', array('full_name' => Phpfox::getUserBy('full_name'))),
                'owner_message' => Phpfox::getPhrase('reviewactivity.full_name_commented_on_your_review_activities_to_see_the_comment_thread_follow_the_link_below_a', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink)),
                'owner_notification' => 'comment.add_new_comment',
                'notify_id' => 'comment_reviewactivity',
                'mass_id' => 'reviewactivity',
                'mass_subject' =>  Phpfox::getPhrase('reviewactivity.full_name_commented_on_your_review_activities', array('full_name' => Phpfox::getUserBy('full_name'))),
                'mass_message' =>  Phpfox::getPhrase('reviewactivity.full_name_commented_on_your_review_activities_to_see_the_comment_thread_follow_the_link_below_a', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink))
            )
        );
    }
    public function addLike($iItemId, $bDoNotSendEmail = false)
    {
        $aRow = $this->database()->select('review_id, description, user_id')
            ->from(Phpfox::getT('review_activity'))
            ->where('review_id = ' . (int) $iItemId)
            ->execute('getSlaveRow');
            
        if (!isset($aRow['review_id']))
        {
            return false;
        }
        
        $this->database()->updateCount('like', 'type_id = \'reviewactivity\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'review_activity', 'review_id = ' . (int) $iItemId);    
        
        Phpfox::getService('notification.process')->add('reviewactivity_like', $aRow['review_id'], $aRow['user_id']);
    }   
    public function getCommentNotification($aNotification)
    {
        $aRow = $this->database()->select('b.review_id, b.user_id, u.gender, u.full_name,u.user_name')    
            ->from(Phpfox::getT('review_activity'), 'b')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
            ->where('b.review_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');
        $sLink = phpfox::getLib('url')->makeUrl($aRow['user_name'].'.reviewactivity');
        if (!isset($aRow['review_id']))
        {
            return false;
        }
        
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        return array(
            'link' => $sLink,
            'message' => Phpfox::getPhrase('reviewactivity.full_name_commented_on_your_review_activities', array('full_name' => $sUsers)),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }  
    public function getNotificationLike($aNotification)
    {
        $aRow = $this->database()->select('b.review_id, b.user_id, u.gender, u.full_name,u.user_name')    
            ->from(Phpfox::getT('review_activity'), 'b')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
            ->where('b.review_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');
            
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        if (!isset($aRow['review_id']))
        {
            return false;
        }
        $sLink = phpfox::getLib('url')->makeUrl($aRow['user_name'].'.reviewactivity');
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        return array(
            'link' => $sLink,
            'message' => Phpfox::getPhrase('reviewactivity.full_name_liked_your_review_activities', array('full_name' => $sUsers)),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }    
}
?>

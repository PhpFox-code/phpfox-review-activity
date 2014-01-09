<?php
defined('PHPFOX') or exit('NO DICE!');
class ReviewActivity_Component_Controller_Profile extends Phpfox_Component
{
    public function process()
    {
        $aUser = $this->getParam('aUser',false);
        if(!isset($aUser['user_id']))
        {
            $this->url()->send('');
        }
        $sPrivacyList = "0,1,2,3,4";
        if(phpfox::getUserId() != $aUser['user_id'])
        {
            $sPrivacyList = "0";
        }
        $sYear = date('Y',PHPFOX_TIME);
        $aUserReviewPhotos = phpfox::getService('reviewactivity')->getUserPhotos($aUser['user_id'],$sPrivacyList);
        list($iTotalFriend,$aUserReviewFriends) = phpfox::getService('reviewactivity')->getFriends($aUser['user_id'],$sYear);
        $sMoreFriend = 0;
        if($iTotalFriend > count($aUserReviewFriends))
        {
            $sMoreFriend = $iTotalFriend - count($aUserReviewFriends);
        }
        list($iTotalPage,$aUserReviewPages) = phpfox::getService('reviewactivity')->getPages($aUser['user_id'],$sYear);
        $sMorePage = 0;
        if($iTotalPage > count($aUserReviewPages))
        {
            $sMorePage = $iTotalFriend - count($aUserReviewPages);
        }
        $sReviewUserName = $aUser['user_name']; 
        /*$this->setParam('aFeed', array(                
                'comment_type_id' => 'reviewactivity',
                'privacy' => $aItem['privacy'],
                'comment_privacy' => $aItem['privacy_comment'],
                'like_type_id' => 'blog',
                'feed_is_liked' => isset($aItem['is_liked']) ? $aItem['is_liked'] : false,
                'feed_is_friend' => $aItem['is_friend'],
                'item_id' => $aItem['review_id'],
                'user_id' => $aItem['user_id'],
                'total_comment' => $aItem['total_comment'],
                'total_like' => $aItem['total_like'],
                'feed_link' => $aItem['bookmark_url'],
                'feed_title' => $aItem['title'],
                'feed_display' => 'view',
                'feed_total_like' => $aItem['total_like'],
                'report_module' => 'reviewactivity',
                'report_phrase' => Phpfox::getPhrase('reviewactivity.report_this_review_activity'),
                'time_stamp' => $aItem['time_stamp']
            )
        ); */       
        $this->template()->assign(array(
            'sReviewYear' => $sYear,
            'sReviewUserName' => $sReviewUserName,
            'sCoreUrl' => phpfox::getParam('core.path'),
            'iTotalFriend' => $iTotalFriend,
            'aUserReviewFriends' => $aUserReviewFriends,
            'iTotalPage' => $iTotalPage,
            'aUserReviewPages' => $aUserReviewPages,
            'sMorePage' => $sMorePage,
            'sMoreFriend' => $sMoreFriend,
        ));
        $this->template()->setBreadCrumb(
            Phpfox::getPhrase('reviewactivity.review_activities')
        );
        $this->template()->assign(array(
            'aUserReviewPhotos' => $aUserReviewPhotos,
            'aReviewUser' => $aUser
            ))
            ->setHeader(array(
                'reviewactivity.css' => 'module_reviewactivity',
                'reviewactivity.js' => 'module_reviewactivity',
                'jquery.cycle.all.js' => 'module_reviewactivity'
            ))
        ;
    }
}
?>


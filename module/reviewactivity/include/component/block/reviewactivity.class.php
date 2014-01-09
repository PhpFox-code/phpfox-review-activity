<?php
defined('PHPFOX') or exit('NO DICE!');
class ReviewActivity_Component_Block_reviewactivity extends Phpfox_Component
{
    public function process()
    {
        $sPrivacyList = "0,1,2,3,4";
        $sYear = date('Y',PHPFOX_TIME);
        $aUserReviewPhotos = phpfox::getService('reviewactivity')->getUserPhotos(phpfox::getUserId(),$sPrivacyList,4);  
        $this->template()->assign(array(
            'aUserReviewPhotos' => $aUserReviewPhotos,
            'sReviewUserName' => phpfox::getUserBy('user_name'),
            'sReviewYear' => $sYear,
            'bIsViewDescription' => true,
            'bIsTimeline' => false,
        ));      
    }
}
?>

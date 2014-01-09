<?php
defined('PHPFOX') or exit('NO DICE!');
class ReviewActivity_Component_Block_Review extends Phpfox_Component
{
    public function process()
    {
        $aUser = $this->getParam('aUser',false);
        if(!isset($aUser['user_name']))
        {
            return false;
        }
        $sYear = date('Y',PHPFOX_TIME);
        $sReviewUserName = $aUser['user_name']; 
        $this->template()->assign(array(
            'sReviewYear' => $sYear,
            'sReviewUserName' => $sReviewUserName,
        ));
    }
}
?>

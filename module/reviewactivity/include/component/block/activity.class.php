<?php
defined('PHPFOX') or exit('NO DICE!');
class ReviewActivity_Component_Block_Activity extends Phpfox_Component
{
    public function process()
    {
        $aUser = $this->getParam('aUser',false);
        if(!isset($aUser['user_id']))
        {
            return false;
        }
        if ( $aUser['user_id'] != phpfox::getUserId())
        {
            return false;
        }
    }
}
?>

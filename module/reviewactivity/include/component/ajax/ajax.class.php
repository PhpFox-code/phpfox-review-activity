<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Ajax
 * @version 		$Id: ajax.class.php 609 2009-05-31 21:44:42Z Raymond_Benc $
 */
class reviewactivity_Component_Ajax_Ajax extends Phpfox_Ajax
{
    public function shareReview()
    {
        $iUserId = $this->get('user');
        phpfox::isUser(true);
        if($iUserId != phpfox::getUserId())
        {
            $this->call("<script>tb_remove();</script>");
            return false;
        }
        $this->setTitle(Phpfox::getPhrase('reviewactivity.share_review_activities'));
        phpfox::getBlock('reviewactivity.reviewactivity',array(
        ));
    }
    public function share()
    {
        $aVal = $this->getAll();
        if(!isset($aVal['val']))
        {
            return false;
        }
        if(phpfox::getService('reviewactivity')->shareReview($aVal['val']))
        {
            $this->call("tb_remove();");
        }
    }
}

?>
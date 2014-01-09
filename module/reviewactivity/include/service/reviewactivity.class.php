<?php
defined('PHPFOX') or exit('NO DICE!');
class ReviewActivity_Service_reviewactivity  extends Phpfox_Service
{
    public function getUserPhotos($iUserId,$sPrivacyList = "0,1,2,3,4",$iLimit = 18,$sYear = null)
    {
        if($sYear == null)
        {
            $sYear = date("Y");
        }
        $iTimeStartYear = phpfox::getLib('date')->mktime(0,0,0,1,1,$sYear);
        $iTimeEndYear = phpfox::getLib('date')->mktime(23,59,59,12,31,$sYear);
        $aRows = $this->database()->select("pa.name AS album_name, pa.profile_id AS album_profile_id, ppc.name as category_name, ppc.category_id, photo.*,".phpfox::getUserField())
                    ->from(phpfox::getT('photo'),'photo')
                    ->leftjoin(phpfox::getT('photo_album'),'pa','pa.album_id = photo.album_id')
                    ->leftjoin(phpfox::getT('photo_category_data'),'ppcd','ppcd.photo_id = photo.photo_id')
                    ->leftjoin(phpfox::getT('photo_category'),'ppc','ppc.category_id = ppcd.category_id')
                    ->join(phpfox::getT('user'),'u','u.user_id = photo.user_id')
                    ->where('photo.view_id IN(0,2) AND photo.group_id = 0 AND photo.type_id = 0 AND photo.privacy IN('.$sPrivacyList.') AND photo.user_id = '.(int)$iUserId.' AND photo.time_stamp >= '.$iTimeStartYear. ' AND photo.time_stamp <='.$iTimeEndYear)
                    ->group('photo.photo_id')
                    ->order('RAND()')
                    ->limit($iLimit)
                    ->execute('getSlaveRows');
        return $aRows;
    }
    public function getFriends($iUserId,$sYear = null)
    {
        if($sYear == null)
        {
            $sYear = date("Y");
        }
        $iTimeStartYear = phpfox::getLib('date')->mktime(0,0,0,1,1,$sYear);
        $iTimeEndYear = phpfox::getLib('date')->mktime(23,59,59,12,31,$sYear);
        $iCnt = $this->database()->select('count(u.user_id)')
                    ->from(phpfox::getT('friend'),'friend')
                    ->join(phpfox::getT('user'),'u','u.user_id = friend.friend_user_id')
                    ->where('friend.is_page = 0 AND friend.user_id = '.(int)$iUserId.' AND friend.time_stamp >= '.$iTimeStartYear. ' AND friend.time_stamp <='.$iTimeEndYear)
                    ->execute('getSlaveField');
        if(!$iCnt)
        {
            return array(0,array());
        }
        $aRows = $this->database()->select('uf.dob_setting, friend.friend_id, friend.friend_user_id, friend.is_top_friend, friend.time_stamp, ' . Phpfox::getUserField())  
                ->from(phpfox::getT('friend'), 'friend')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = friend.friend_user_id')
                ->join(Phpfox::getT('user_field'), 'uf', 'u.user_id = uf.user_id')  
                ->where('friend.is_page = 0 AND friend.user_id = '.(int)$iUserId.' AND friend.time_stamp >= '.$iTimeStartYear. ' AND friend.time_stamp <='.$iTimeEndYear)
                ->limit(0, 29, $iCnt)
                ->group('u.user_id')
                ->execute('getSlaveRows');         
        return array($iCnt,$aRows);
    }
    public function getPages($iUserId, $sYear = null)
    {
         if($sYear == null)
        {
            $sYear = date("Y");
        }
        $iTimeStartYear = phpfox::getLib('date')->mktime(0,0,0,1,1,$sYear);
        $iTimeEndYear = phpfox::getLib('date')->mktime(23,59,59,12,31,$sYear);
        $iCnt = $this->database()->select('count(pages.page_id)')
                    ->from(phpfox::getT('like'),'lke')
                    ->join(phpfox::getT('pages'),'pages','pages.page_id = lke.item_id AND lke.type_id ="pages"')
                    ->where('lke.user_id = '.(int)$iUserId.' AND pages.view_id = 0 AND lke.time_stamp >= '.$iTimeStartYear. ' AND lke.time_stamp <='.$iTimeEndYear)
                    ->execute('getSlaveField');
        if(!$iCnt)
        {
            return array(0,array());
        }
        $aRows = $this->database()->select('pages.*,u2.server_id AS profile_server_id, u2.user_image AS profile_user_image,pu.vanity_url')
                ->from(phpfox::getT('like'),'lke')
                ->join(phpfox::getT('pages'),'pages','pages.page_id = lke.item_id AND lke.type_id ="pages"')
                ->leftJoin(Phpfox::getT('user'), 'u2', 'u2.profile_page_id = pages.page_id')
                ->leftJoin(Phpfox::getT('pages_url'), 'pu', 'pu.page_id = pages.page_id')
                ->where('lke.user_id = '.(int)$iUserId.' AND pages.view_id = 0 AND lke.time_stamp >= '.$iTimeStartYear. ' AND lke.time_stamp <='.$iTimeEndYear)
                ->limit(0, 29, $iCnt)
                ->order('RAND()')
                ->execute('getSlaveRows');
        foreach($aRows as $iKey=>$aRow)
        {
            $aRows[$iKey]['page_url'] = Phpfox::getService('pages')->getUrl($aRow['page_id'], $aRow['title'], $aRow['vanity_url']);
        }
        return array($iCnt,$aRows);
    }
    public function shareReview($aVals)
    {
        $sDescription = isset($aVals['description'])?$aVals['description']:"";
        $oFilter = Phpfox::getLib('parse.input');
        $sDescription = $oFilter->clean($sDescription, 255);    
        $aInsert = array(
            'user_id' => Phpfox::getUserId(),
            'view_id' => 0,
            'time_stamp' => PHPFOX_TIME,
            'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
            'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
            'description' => $sDescription,
            'year' => (isset($aVals['year']) ? $aVals['year'] : date('Y',PHPFOX_TIME)),
        );        
        $iId = $this->database()->insert(Phpfox::getT('review_activity'), $aInsert);
        (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('reviewactivity', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0)) : null);
        if ($aVals['privacy'] == '4')
        {
            Phpfox::getService('privacy.process')->add('reviewactivity', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));            
        }   
        return true;         
    }
    public function getReviewId($iId)
    {
        $aRow = $this->database()->select('ra.*,l.like_id AS is_liked,'.phpfox::getUserField())
                ->from(phpfox::getT('review_activity'),'ra')
                ->join(phpfox::getT('user'),'u','u.user_id = ra.user_id')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'reviewactivity\' AND l.item_id = ra.review_id AND l.user_id = ' . Phpfox::getUserId())
                ->where('ra.review_id = '.(int)$iId)
                ->execute('getRow');
        return $aRow;
    }
}
?>

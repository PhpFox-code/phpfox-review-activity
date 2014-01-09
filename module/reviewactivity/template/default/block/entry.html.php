<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
<div class="user_feed_reviewactivity" style="position:relative;">
<div class="photo_background" style="width:{if $bIsTimeline}316px{else}377px{/if};height:236px;border: 1px solid #C4CDE0;">
{if isset($aUserReviewPhotos) && count($aUserReviewPhotos)}
        {foreach from=$aUserReviewPhotos item=aUserReviewPhoto key=index}
            <div id="review_bg_photo-{$index}" class="review_bg_photo_a" style="width: {if !$bIsTimeline}188px{else}158px{/if}; height: 118px;overflow:hidden;float:left;">
            {if $bIsTimeline}
                {img server_id=$aUserReviewPhoto.server_id path='photo.url_photo' file=$aUserReviewPhoto.destination suffix='_240' width="158" height="118" class='hidden_photo'}
            {else}
                {img server_id=$aUserReviewPhoto.server_id path='photo.url_photo' file=$aUserReviewPhoto.destination suffix='_240' width="188" class='hidden_photo'}
            {/if}
            </div>
        {/foreach}
{else}
    
{/if}
        <div class="clear"></div>
    </div>
      <div class="review_profile_" style="background: none repeat scroll 0 0 white;box-shadow: 0 0 80px rgba(28, 42, 71, 0.9);left: 50%;margin-left: {if isset($bFeedView)}{if $bIsTimeline}-87px{else}-124px{/if}{else}-84px{/if};padding: 8px;position: absolute;text-align: center;top: 71px;width: 150px;">
        <div class="review_profile_content_" style="border: 1px solid #8EA0C4; padding: 3px 5px; height:70px;">
            <div class="reviewactivity_year_" style="  color: #627AAD;font-size: 50px;font-weight: bold;text-align: center;">
                <span onclick="javascript:void(0);">{$sReviewYear}</span>
            </div>
        </div>
        
</div>
{if isset($aRow.description)}
    <div class="extra_info">{$aRow.description|clean}</div>
    <div class="t_right"><a href="{url link=$aRow.user_name.'.reviewactivity'}">{$aRow.full_name}'s {phrase var='reviewactivity.review'}</a></div>
    <div class="clear"></div>
{/if}
</div>
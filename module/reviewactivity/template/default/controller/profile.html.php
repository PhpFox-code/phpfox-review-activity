<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<style>
.review_block_title.friend
{
    background:url({/literal}{$sCoreUrl}{literal}module/reviewactivity/static/image/friends.png) no-repeat center left;
} 
.review_block_title.pages
{
    background:url({/literal}{$sCoreUrl}{literal}module/reviewactivity/static/image/like.png) no-repeat center left;
} 
</style>
{/literal}
<div class="photo_background_activity">
    <div class="photo_background">
        {foreach from=$aUserReviewPhotos item=aUserReviewPhoto key=index}
            <div id="review_bg_photo-{$index}" class="review_bg_photo {if $index < 3}slide_top_{$index}{/if} {if $index == 3 || $index == 5}slide_left{/if}{if $index >5 }slide_right_{$index}{/if}" index="{$index}" {if $index > 8}style="display:none;" {/if}>
                {if $index > 8}
                    {img server_id=$aUserReviewPhoto.server_id path='photo.url_photo' file=$aUserReviewPhoto.destination suffix='_500' width=240 height=213 class='hidden_photo'}
                {else}
                    {img server_id=$aUserReviewPhoto.server_id path='photo.url_photo' file=$aUserReviewPhoto.destination suffix='_500' width=240 height=213 class='display_photo'}
                {/if}
                
            </div>
        {/foreach}
        <div class="clear"></div>
    </div>                               
    <div class="review_profile {if phpfox::getUserId() != $aReviewUser.user_id}profile_view_activity{/if}">
        <div class="review_profile_content">
            {img user=$aReviewUser suffix='_100_square' width=100 height=100}
            <div class="reviewactivity_year">
                <a href="{url link=$sReviewUserName.'.reviewactivity'}">{$sReviewYear}</a>
            </div>
            {if phpfox::getUserId() == $aReviewUser.user_id}
            <div class="reviewactivity_message">
                {phrase var='reviewactivity.year_in_review'}
            </div>
            <div class="reviewactivity_message">
                {$aReviewUser.full_name}
            </div>
            {else}
                <div class="reviewactivity_message">
                {$aReviewUser.full_name}'s {phrase var='reviewactivity.review'}
            </div>
            {/if}
            {if phpfox::getUserId() == $aReviewUser.user_id}
            <div class="extra_info" style="margin-top:20px;">
                {phrase var='reviewactivity.a_look_at_your_activities_from_the_year_including_life_events_photos_videos_highlighted_posts_and'}
            </div>
            <div class="extra_info" style="margin-top:20px;">
                 <input type="button" class="button reviewactivity_button_share" value="{phrase var='reviewactivity.share_review'}" user="{$aReviewUser.user_id}"/>
            </div>
            
            {/if}
        </div>
        
        
        <div class="clear"></div>
        
    </div>
</div>
<div class="review_blocks">
    <div class="review_block friends">
        <div class="review_block_title friend">
            <span class="review_number">{$iTotalFriend}</span>
            {phrase var='reviewactivity.friends_added_this_year'}
        </div>
        <div class="review_block_content">
            {foreach from=$aUserReviewFriends item=aUserReviewFriend key=index}
                <div class="friend_image_review" id="js_image_div_{$aUserReviewFriend.friend_id}">    
                    {img id='sJsUserImage_'$aUserReviewFriend.friend_id'' user=$aUserReviewFriend suffix='_50_square' width=34 height=34}
                </div>
            {foreachelse}
                <div class="message">{phrase var='reviewactivity.you_have_no_new_friends_in_this_year'}.</div>
            {/foreach}
            {if count($aUserReviewFriends)}
                <div class="friend_image_review review_more" id="js_image_div_0">
                    <a href="{url link=$sReviewUserName.'.friend'}">
                    {if $sMoreFriend >0}    
                        +{$sMoreFriend}
                    {else}
                        ...
                    {/if}
                    </a>
                </div>
            {/if}
            <div class="clear"></div>
        </div>
        
    </div>
    <div class="review_block pages">
        <div class="review_block_title pages">
            <span class="review_number">{$iTotalPage}</span>
            {phrase var='reviewactivity.pages_liked_this_year'}
        </div>
        <div class="review_block_content">
            {foreach from=$aUserReviewPages item=aUserReviewPage key=index}
                <div class="friend_image_review" id="js_image_div_{$aUserReviewPage.page_id}">
                    <a href="{$aUserReviewPage.page_url}">    
                    {img server_id=$aUserReviewPage.profile_server_id title=$aUserReviewPage.title path='core.url_user' file=$aUserReviewPage.profile_user_image suffix='_50_square' width='34' height='34' is_page_image=true}
                    </a>
                </div>
            {foreachelse}
                <div class="message">{phrase var='reviewactivity.you_did_not_like_any_pages_this_year'}.</div>
            {/foreach}
            {if count($aUserReviewPages)}
                <div class="friend_image_review review_more" id="js_image_div_0">
                    <a href="{url link=$sReviewUserName.'.pages'}">
                    {if $sMorePage >0}    
                        +{$sMorePage}
                    {else}
                        ...
                    {/if}
                    </a>
                </div>
            {/if}
            <div class="clear"></div>
        </div>
    </div>
</div>
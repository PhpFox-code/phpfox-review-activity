<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<style>
.reviewactivity_block
{
    border:1px solid #627AAD;
    padding:7px 5px;
}
.reviewactivity_year
{
    font-size:30px;
    color:#627AAD;
    float:left;
    text-align:left;
    font-weight:bold;
    width:89px;
}
.reviewactivity_year span
{
    font-size:11px;
}
.reviewactivity_message
{
    float: right;
    font-weight: bold;
    line-height: 15px;
    margin-top: 4px;
    width: 148px;
}
.reviewactivity_year a:hover
{
     text-decoration: none;
}
</style>
{/literal}
<div class="reviewactivity_block">
    <div class="reviewactivity_year">
        <a href="{url link=$sReviewUserName.'.reviewactivity'}">{$sReviewYear}</a>
    </div>
    <div class="reviewactivity_message">
        <div class="reviewactivity_title">
            <span><a href="{url link=$sReviewUserName.'.reviewactivity'}">{phrase var='reviewactivity.see_your_syearreview_year_in_review' sYearReview =$sReviewYear }</a></span>    
        </div>
    </div>
    <div class="clear"></div>
    <div class="extra_info">
            {phrase var='reviewactivity.look_back_at_your_activities_from_the_past_year'}
        </div>
    
</div>
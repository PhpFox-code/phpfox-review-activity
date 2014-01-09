<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
<form id="reviewactivity_share">
<div class="text_review" style="margin-bottom:10px;">
    <textarea col="50" row="5" name="val[description]" style="width:366px;"></textarea>
</div>
<div class="review_content">
    {template file='reviewactivity.block.entry'}
</div>
<div class="submit_content">
    {if Phpfox::isModule('privacy')}
        <div class="table" style="padding:0;">
            <div class="table_left">
                {phrase var='reviewactivity.privacy'}:
            </div>
            <div class="table_right">    
                {module name='privacy.form' privacy_name='privacy' privacy_info='reviewactivity.control_who_can_see_this_review' default_privacy='reviewactivity.default_privacy_setting'}
            </div>            
        </div>
    {/if}
    <div id="js_custom_privacy_input_holder">
    </div>
    <input class="button" type="button" value="{phrase var='reviewactivity.share'}" onclick="$Core.ReviewActivity.share();" style="bottom: 24px; position: absolute; right: 0;"/>
</div>
</form>
<script>$Core.loadInit();</script>
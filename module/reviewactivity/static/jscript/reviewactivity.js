var $bIsInit = false;
$Core.ReviewActivity = {    
    your_domain_url:'',
    init:function()
    {
        $('.reviewactivity_button_share').unbind('click');
        $('.reviewactivity_button_share').bind('click',function(){
            $Core.box('reviewactivity.shareReview',410,'user='+$(this).attr('user'));
        })
        if($bIsInit == true)
        {
            return;
        }
        $('.hidden_photo').each(function(i,e){
            $('#review_bg_photo-'+i).append($(e));
            //$(e).parent().remove();    
        });
        $('.review_bg_photo.slide_top_1').cycle({ 
             fx:    'zoom', 
            sync:  false, 
            delay: -2000 
        });
        $('.review_bg_photo.slide_top_0').cycle({ 
            fx:      'turnDown', 
            delay:   -4000 
        });
        $('.review_bg_photo.slide_top_2').cycle({ 
            fx:    'curtainX', 
            sync:  false, 
            delay: -2000 
        });
        $('.review_bg_photo.slide_left').cycle({ 
           
        });
        $('.review_bg_photo.slide_right_6').cycle({ 
            fx:      'turnUp', 
            delay:   -6000 
        });
        $('.review_bg_photo.slide_right_7').cycle({ 
            fx:    'zoom', 
            sync:  false, 
            delay: -5000 
        });
        $('.review_bg_photo.slide_right_8').cycle({ 
            fx:    'curtainY', 
            sync:  false, 
            delay: -3000 
        });
        
        $bIsInit = true;
    },
    share:function()
    {
        $('#reviewactivity_share').ajaxCall('reviewactivity.share');   
    }
};
$Behavior.initReviewActivity = function(){
    $Core.ReviewActivity.init();
}
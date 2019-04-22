<?php
/**
* Template Name:     Basic
* Description:       Basic ad template.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div id="bam_ad_<?php echo $attributes['id']; ?>" class="bam-basice-ad-container">
    <div class="ad-body" style="background-color: <?php echo $bam_ad_data['bgcolor']; ?>">
        <div class="left-section">
            <?php if ($bam_ad_data['type'] == 'pick') { ?>
            <div class="timer">
                <div class="countdown">
                    <table>
                        <tr>
                            <th>DAYS</th>
                            <th>HOURS</th>
                            <th>MIN</th>
                            <th>SEC</th>
                        </tr>
                        <tr>
                            <td id="days"><?php echo $bam_ad_data['remaining_time']['days']; ?></td>
                            <td id="hours"><?php echo $bam_ad_data['remaining_time']['hours']; ?></td>
                            <td id="min"><?php echo $bam_ad_data['remaining_time']['min']; ?></td>
                            <td id="sec"><?php echo $bam_ad_data['remaining_time']['sec']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="countdown-text">Remaining Time To Place Bet</div>
            </div>
            <?php } ?>
            <div class="main-text">
                <h1 class="ad-title"><?php echo $bam_ad_data['title']; ?></h1>
                <p class="ad-text">Hurry up! <strong>25</strong> people have placed this bet.</p>
            </div>
        </div>
        <div class="right-section">
            <div>
                <a class="call-to-action">BET &amp; WIN</a>
                <p class="call-to-action-text">Trusted by sportsbetting.ag</p>
            </div>
        </div>
    </div>
</div>
<?php if ($bam_ad_data['type'] == 'pick') { ?>
<script type="text/javascript">
    (function( $ ) {
        'use strict';
        $(function() {
            var days = parseInt($("#bam_ad_<?php echo $attributes['id']; ?> #days").text());
            var hours = parseInt($("#bam_ad_<?php echo $attributes['id']; ?> #hours").text());
            var min = parseInt($("#bam_ad_<?php echo $attributes['id']; ?> #min").text());
            var sec = parseInt($("#bam_ad_<?php echo $attributes['id']; ?> #sec").text());
            
            setInterval(function() {

                sec--;
                if (sec < 0) {
                    min--;
                    sec = 59;
                    if (min < 0 && (hours > 0 || days > 0)) {
                        hours--;
                        min = 59;
                        if (hours < 0 && days > 0) {
                            days--;
                            hours = 23;
                        }
                    }
                }
              

                $("#bam_ad_<?php echo $attributes['id']; ?> #days").text(number_format(days, 2));
                $("#bam_ad_<?php echo $attributes['id']; ?> #hours").text(number_format(hours, 2));
                $("#bam_ad_<?php echo $attributes['id']; ?> #min").text(number_format(min, 2));
                $("#bam_ad_<?php echo $attributes['id']; ?> #sec").text(number_format(sec, 2));
            }, 1000);
        });

        function number_format(num, size) {
            var s = num+"";
            while (s.length < size) s = "0" + s;
            return s;
        }
        
    })( jQuery );
</script>
<?php } ?>
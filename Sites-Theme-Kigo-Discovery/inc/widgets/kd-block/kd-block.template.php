<?php

$class = isset($i['useVideo']) ? '' : 'image';
$bgImage = !isset($i['useVideo']) && isset($i['image']) ? 'background-image: url('.$i['image'].');' : '';
$bgColor = isset($i['bgColor']) ? 'background-color: '.$i['bgColor'].';' : '';

$style = $bgColor.$bgImage;
?>

<?php if(isset($i['font']) && !empty($i['font'])): ?>
    <style>
        @import url('https://fonts.googleapis.com/css?family=<?php echo str_replace(' ', '+', $i['font']) ?>');
    </style>
<?php endif; ?>

<!-- Widget: Block -->


<h1 class="widget_title">
    <?php echo $i['title'] ?>
</h1>

<div class="kd-widget" style="<?php echo isset($i['font']) && !empty($i['font']) ? 'font-family: '.$i['font'] : '' ?>">
    <div class="kd-block <?php echo $class ?>" style="<?php echo $style ?>" >
        <?php if($i['useVideo'] && $i['whichVideo'] == 'yt'): ?>

            <div class="yt-overlay videoPlayer">
                <div id="yt-player"></div>
            </div>

            <script>
                var player, video = '<?php echo $i['video-yt'] ?>';

                if(video.length > 0) {
                    // Load the IFrame Player API code asynchronously.
                    var tag = document.createElement('script');
                    tag.src = "https://www.youtube.com/player_api";
                    var firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                    // Replace the 'ytplayer' element with an <iframe> and
                    // YouTube player after the API code downloads.

                    function onYouTubePlayerAPIReady() {
                        player = new YT.Player('yt-player', {
                            height: '750',
                            width: '100%',
                            videoId: video,
                            playerVars: {
                                controls: 0,
                                autoplay: 1,
                                modestbranding: 1,
                                rel: 0,
                                loop: 1,
                                disablekb: 1,
                                showinfo: 0
                            }
                        });
                    }
                }
            </script>
        <?php endif; ?>


        <?php if($i['useVideo'] && $i['whichVideo'] == 'ct'): ?>
            <video class="videoPlayer" loop autoplay="autoplay" muted="muted" poster="" style="width: 100%; height: auto; visibility: visible;">
                <source src="<?php echo $i['video-ct'] ?>" type="video/mp4">
            </video>
        <?php endif; ?>

        <div class="page-width">
            <div class="inner">
                <div class="subtitle">
                    <?php echo $i['content'] ?>
                </div>
            </div>
        </div>
    </div>
</div>
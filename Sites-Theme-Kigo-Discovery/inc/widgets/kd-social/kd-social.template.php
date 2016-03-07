<div class="kd-widget kd-social lg-left">
    <ul style="color: <?php echo $i['color'] ?>">

        <?php
        /* Remove other fields from the loop */
        unset($i['color']);

        /* Sort social icon array by order */
        usort($i, function($a, $b){
            if ($a['order'] == $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });

        $socialIcons = [];
        foreach($i as $key => $val){
            //Print socials that have both URL & ICON
            if($val['url'] && $val['icon']){
                printf('<li><a href="%s" target="_blank" title=""><i class="fa %s"></i></a></li>', $val['url'], $val['icon']);
            }
        }

        ?>
    </ul>
</div>

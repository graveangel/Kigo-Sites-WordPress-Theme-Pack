<?php //Template Name: Search ?>
<?php get_header() ?>

    <div class="page-width">

        <div class="row">
            <?php the_widget('KD_Search') ?>
        </div>

        <div class="row">

            <div class="mapView hidden col-xs-12">

                <div class="map mapContainer col-xs-12 col-md-6">
                    <div>
                        <div class="loader"><div class="bar"></div></div>
                        <div id="mapContainer" class="loading" data-color="<?php echo get_theme_mod('primary-color') ?>"></div>
                        <div id="resetMap"><i class="kd-icon-toggle-fscreen"></i></div>
                    </div>
                </div>

                <div class="mapProps col-xs-12 col-md-6">
                    <div class="col-xs-12 top">
                        <div class="available">
                            <div>
                                <span class="ppty-count-current">0</span>
                                <span>&nbsp;out of&nbsp;</span>
                                <span class="ppty-count-total">0</span>
                                <span>&nbsp;properties loaded.</span>
                            </div>

                            <div class="btn-group viewToggle" data-toggle="buttons-radio">
                                <button class="btn changeview v-list"><i class="fa fa-list"></i>List</button>
                                <button class="btn changeview"><i class="fa fa-map-marker"></i>&nbsp;Map</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 bottom">
                        <div id="mapPropertiesContainer" class="row">

                        </div>
                    </div>
                </div>
            </div>

            <div class="listView hidden col-xs-12">
                <div class="row listProps">
                    <div class="col-xs-12 top">
                        <div class="available">
                            <div>
                                <span class="ppty-count-current">0</span>
                                <span>&nbsp;out of&nbsp;</span>
                                <span class="ppty-count-total">0</span>
                                <span>&nbsp;properties loaded.</span>
                            </div>

                            <div class="btn-group viewToggle" data-toggle="buttons-radio">
                                <button class="btn changeview"><i class="fa fa-list"></i>List</button>
                                <button class="btn changeview v-map" data-showallresults="1"><i class="fa fa-map-marker"></i>&nbsp;Map</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 bottom">
                        <div id="listPropertiesContainer" class="row pad-y-15">

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

<?php get_footer() ?>
<?php //Template Name: Search ?>
<?php get_header() ?>

    <div class="page-width">

        <div class="row">
            <?php the_widget('KD_Search') ?>
        </div>

        <div class="row pad-x-15">

            <div class="mapView">

                <div class="map mapContainer col-xs-12 col-md-6">
                    <div id="mapContainer" data-markercolor="#33baaf"></div>
                    <div id="resetMap"><i class="kd-icon-toggle-fscreen"></i></div>
                </div>

                <div class="row mapProps  col-xs-12 col-md-6">
                    <div class="col-xs-12 top">
                        <div class="available">
                            <div>
                                <span class="ppty-count-current">0</span>
                                <span>&nbsp;out of&nbsp;</span>
                                <span class="ppty-count-total">0</span>
                                <span>&nbsp;properties loaded.</span>
                            </div>

                            <div class="btn-group" data-toggle="buttons-radio">
                                <button class="btn changeview"><i class="fa fa-list"></i>List</button>
                                <button class="btn changeview active" data-showallresults="1"><i class="fa fa-map-marker"></i>&nbsp;Map</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 bottom">
                        <div id="mapPropertiesContainer" class="row">

                        </div>
                    </div>
                </div>
            </div>

            <div class="listView">

            </div>

        </div>

    </div>

<?php get_footer() ?>
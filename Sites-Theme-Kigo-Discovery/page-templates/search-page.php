<?php //Template Name: Search ?>
<?php get_header() ?>

    <div class="page-width">

        <div class="row">
            <?php the_widget('KD_Search') ?>
        </div>

        <div class="row pad-x-15">
            <div class="split-search col-xs-12">

                <div class="map mapContainer col-xs-12 col-md-6">
                    <div id="mapContainer" data-markercolor="<?php echo get_theme_mod('primary-color') ?  : '#33baaf' ?>"></div>
                    <div id="resetMap"><i class="kd-icon-toggle-fscreen"></i></div>
                </div>

                <div
                    id="results"
                    class="bapi-summary propContainer col-xs-12 col-md-6"
                    data-log="0"
                    data-defaultsearchresultview="1"
                    data-templatename="tmpl-propertysearch-listview"
                    data-entity="property"
                    data-showallresults="1">
                </div>

            </div>
        </div>

    </div>

<?php get_footer() ?>
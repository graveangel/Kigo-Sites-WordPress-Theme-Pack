<?php
//Template name: Contact Page
get_header();
the_post();
$themeBAPI = new Discovery\BAPIHelper();
$bapi_solutiondata = $themeBAPI->getData();
$textDataArray = $themeBAPI->getTextDataArray();

?>
    <div class="page-width">
        <div class="contact-wrapper">
    <div class="col-xs-12 hero-section">
        <h3 class="widget-title"><?php echo $textDataArray['Contact Us']; ?></h3>
        <p><?php echo get_theme_mod('contact-subtitle', "Here your subtitle"); ?></p>
    </div>

    <div class="col-md-4 col-xs-12">
        <div class="pd2">
<!-- start data from the editor -->
            <?php the_content(); ?>
<!-- end data from the editor -->
            
            <div class="contact-left"><?php echo get_theme_mod('contact-left', "Here your contact left content"); ?></div>
        </div>
    </div>

    <div class="col-md-8 col-xs-12 enquiry-form-box">
        <!-- BAPI Contact form -->
        <div id="bapi-inquiryform" class="bapi-inquiryform" data-templatename="tmpl-contactus-form" data-log="0" data-shownamefield="1" data-showemailfield="1" data-showphonefield="1" data-showdatefields="0" data-shownumberguestsfields="0" data-showleadsourcedropdown="1" data-showcommentsfield="1"></div>
        <!-- Main content -->
        <div class="officemap">
            <img src="//maps.googleapis.com/maps/api/staticmap?center=<?php echo $bapi_solutiondata->Office->Latitude; ?>,<?php echo $bapi_solutiondata->Office->Longitude; ?>&zoom=8&size=900x300&maptype=roadmap&markers=color:blue%7Clabel:%20%7C<?php echo $bapi_solutiondata->Office->Latitude; ?>,<?php echo $bapi_solutiondata->Office->Longitude; ?>&sensor=false" />
            <div class="pagination-centered"><small><a href="//maps.google.com/?q=<?php echo $bapi_solutiondata->Office->Latitude; ?>,<?php echo $bapi_solutiondata->Office->Longitude; ?>" target="_blank"><?php echo $textDataArray['View Larger Map']; ?></a></small></div>
        </div>
        <div class="contact-under"><?php echo get_theme_mod('contact-under', "Here your contact under content"); ?></div>
    </div>

</div>
    </div>
<?php get_footer(); ?>
<div class="modal fade" id="page-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($)
    {
        // Add the buttons
        jQuery(jQuery(".wrap h1")[0]).append("\
                                        <a href='#' id='generate-from-mkta' class='add-new-h2'>Auto Generate from Market Areas</a> \n\
                                        <a href='#' id='generate-from-ppf' class='add-new-h2'>Quick Create Property Finders</a>\n\
                                        \n\
                    ");
        
        // Add the actions
        $('#generate-from-mkta').on('click', function(e)
        {
            e.preventDefault();
            modal(
                    {
                        title: '<i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i> <h2>Heads up!</h2>',
                        body: '<p> This action will crawl the <b>sitemap.xml</b> and create all pages in it. This will take some time. <b>Are you sure you want to do this?</b></p>',
                        footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn primary-button mkta-btn-ok">Ok</button>'
                    });
            $('.mkta-btn-ok').on('click', function(e)
            {
                e.preventDefault();
				var txtresult = $('.modal-body');
                                txtresult.css('background-image','url(http://smashinghub.com/wp-content/uploads/2014/08/cool-loading-animated-gif-1.gif)')
                                        .css('background-repeat','no-repeat')
                                        .css('background-size','100% auto');
                                
				txtresult.html('<h5>Crawling Sitemap.xml</h5>\n\
                                                <p>Please wait. This can take a while...</p>\n\
                                                <p>The page will reload automatically.<br>Do not close this window.\n\
                                                <br><br>');
				var url = '/sitemap_crawler.svc';
				jQuery.post(url, {}, function(res) {
                                        txtresult.css('background-image','none')
                                        txtresult.html('<h5>Completed</h5>');
					txtresult.append(res);
                                        document.location.href ="/wp-admin/edit.php?post_type=stacks&generate-from=market-areas";
				});	
                            $('.mkta-btn-ok').remove();
                            
            });
        });
         $('#generate-from-ppf').on('click', function(e)
        {
             modal(
        {
            title: '<h1><i class="fa fa-align-right fa-1x" aria-hidden="true"></i> Add New Stack Node:</h1>',
            body:'<p class="description">Add the Name of the Stack and the words separated by blank spaces to filter the properties;\n\
                   <h3>Stack Name: <input type="text" class="stack-name"></h3> <h3>Stack Words: <input type="text" class="stack-words"></h3> </p>',
            footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn btn-primary btn-ok-stack-node">Ok</button>'
        });
        });
        
    });
</script>
<style type="text/css">
@import url(https://fonts.googleapis.com/css?family=Varela+Round);
.modal-title * {font-size: 40px; margin: 0; display: inline-block;}
.modal-body {font-family: 'Varela Round', sans-serif; color: white; background: #66ceff; font-size: 18px;}
.modal-body h5 { font-size: 25px;}
</style>
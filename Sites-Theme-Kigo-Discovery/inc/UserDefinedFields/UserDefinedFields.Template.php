<div class="udpa-wrapper">
    <h1><?php echo $title; ?></h1>

    <!--Need a description-->
    <p class="description">
        Here you can create fields that will appear in all property pages so that users can filter them from the search bar
    </p>

    <!--Need a button to create the user defined fields-->
    <a href="#" class="btn button-primary udf-create">Create new user defined field</a>

    <!--Need a delete selected button-->
    <a href="#" class="btn button-default udf-delete">Remove selected UDFS</a>
    
    <!--Need a save button-->
    <a href="#" class="btn button-save udfs-save">Save</a>
 

    <!--The Fields Container-->
    <div class="udf-content">

    </div>
</div>
<form method="POST" class="form-udfs">
    <input type="hidden" name="udfs" id="udfs">
</form>
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
/**
 * On load
 */
var UDFS = <?php if(empty($udfs)) echo '{}'; else echo $udfs; ?>;
</script>
<table style="display: none;" id="quick_edit_form">
    <tr  class="inline-edit-row inline-edit-row-page inline-edit-page quick-edit-row quick-edit-row-page inline-edit-page inline-editor">
        <td colspan="3" class="colspanchange">
            <form action="" method="post" id="qedit_form">
                <fieldset class="inline-edit-col-left">
                    <legend class="inline-edit-legend">Quick Edit</legend>
                    <div class="inline-edit-col">

                        <label>
                            <span class="title">Title</span>
                            <span class="input-text-wrap"><input type="text" name="post[post_title]" class="ptitle" value="[%post_title%]"></span>
                        </label>

                        <label>
                            <span class="title">Slug</span>
                            <span class="input-text-wrap"><input type="text" name="post[post_name]" value="[%post_name%]"></span>
                        </label>

                        <br class="clear">

                    </div>
                </fieldset>


                <fieldset class="inline-edit-col-right">
                    <div class="inline-edit-col">

                        <div class="inline-edit-group wp-clearfix">
                            <label class="inline-edit-status alignleft">
                                <span class="title">Status</span>
                                <select name="post[status]">
                                    <option value="publish">Published</option>
                                    <option value="pending">Pending Review</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </label>

                        </div>


                    </div>
                </fieldset>

                <p class="submit inline-edit-save">
                    <button type="button" class="button-secondary cancel alignleft">Cancel</button>
                    [%nonce%]
                    <button type="button" class="button-primary save alignright">Update</button>
                    <span class="spinner"></span>
                    <input type="hidden" name="post_view" value="list">
                    <input type="hidden" name="post[post_id]" value="[%post_id%]">
                    <input type="hidden" name="screen" value="edit-page">
                    <span class="error" style="display:none"></span>
                    <br class="clear">
                </p>
            </form>
        </td>
    </tr>
</table>
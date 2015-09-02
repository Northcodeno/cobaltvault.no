<script src="scripts/createprj.js"></script>

<div class="modal fade" id="modal_uploadfile" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data" action="scripts/project.php?a=update_file&id=<?php echo $id; ?>">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          		<h4 class="modal-title">Update Project</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-lg-2 control-label">Map File:</label>
                    <div class="col-lg-10"><input type="file" name="file" id="file" class="form-control" /></div>
                </div>
                <div class="form-group">
                    <label for="localization" class="col-lg-2 control-label">Localization<br/><i>Must be valid JSON</i><br/><input type="button" id="loc_auto" value="Generate" class="btn btn-default"></label>
                    <div class="col-lg-10" id="error_localization">
                        <textarea class="form-control" name="localization" id="localization" style="height:300px;"><?php echo $data['localization']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            	<input type="submit" class="btn btn-primary" value="Submit"/>
            </div>
            </form>
        </div>
    </div>
</div>
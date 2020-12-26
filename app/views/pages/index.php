<?php require_once APPROOT.'/views/inc/header.php';?>
<section class="mt-3 p-1">
	<div class="container p-1">
		<div class="row">
			<div class="col-3">
				<p>Change tree</p>
				<div id="treeSelect-container">
				<?php 
					include 'partials/treeSelect.php';
				?>
				</div>
				<div class="row">
					<button class="btn btn-primary m-h-05" onclick="addNewTree();">Add new Tree</button>
					<button class="btn btn-danger m-h-05" onclick="deleteTree();">Delete Tree</button>
				</div>
			</div>
			<div class="col-5">
				<p>Tree stucture</p>
				<div id="tree-container">
					<?php 
						include 'partials/tree.php';
					?>
				</div>
			</div>
			<div class="col-4">
				<p>Settings</p>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" checked name="inlineRadioOptions" id="settings_rename_modal" value="modal" onchange="hideAllNameInputs();">
					<label class="form-check-label" for="settings_rename_modal">Rename by modal</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="inlineRadioOptions" id="settings_rename_input" value="input" onchange="hideAllNameInputs();">
					<label class="form-check-label" for="settings_rename_input">Rename by input</label>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal" tabindex="-1" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete confirmation</h5>
         <button type="button" class="btn btn-close btn-primary" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
        <p>This is very dangerous, you shouldn't do it! Are you really sure? </p>
      </div>
      <div class="modal-footer">
		  	<div id="timer">
				20
			</div>
		  	<div>
				<input type="hidden" id="deleteElementId" value="">
				<button type="button" class="btn btn-primary" onclick="confirmDeletion();">Yes i am</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
			</div>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" id="renameModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change element name</h5>
         <button type="button" class="btn btn-close btn-primary" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
		<form method="post" action="trees/edit" id="editForm">
			<input type="hidden" name="id" value="" id="renameElementId">
			<div class="input-group mb-3">
				<span class="input-group-text">Name</span>
				<input type="text" name="name" value="" class="form-control" aria-label="Enter new name">
			</div>
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick="editFormSubmit('editForm')">Save</button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" id="createTreeModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create new Tree</h5>
         <button type="button" class="btn btn-close btn-primary" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
		<form method="post" action="trees/edit" id="createForm">
			<div class="input-group mb-3">
				<span class="input-group-text">Name</span>
				<input type="text" name="name" value="" class="form-control" aria-label="Enter new name">
			</div>
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick="createFormSubmit('createForm')">Save</button>
		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<?php require_once APPROOT.'/views/inc/footer.php';?>
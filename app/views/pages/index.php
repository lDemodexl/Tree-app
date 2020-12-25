<?php require_once APPROOT.'/views/inc/header.php';?>
<section class="mt-3">
	<div class="container p-1">
		<div id="tree-container">
			<?php 
				include 'partials/tree.php';
			?>
		</div>
	</div>
</section>
<div class="modal" tabindex="-1" id="myModal">
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

<?php require_once APPROOT.'/views/inc/footer.php';?>
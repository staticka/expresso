<div class="modal fade" id="<?php echo $id ?>" tabindex="-1" aria-labelledby="<?php echo $id ?>-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white bg-danger border-bottom-0">
        <span class="modal-title fs-5 fw-bold" id="<?php echo $id ?>-label">Delete page?</span>
      </div>
      <div class="modal-body">
        <p class="mb-0">Are you sure that you want to delete the page <span class="fw-bold" x-text="deleteName"></span>? This action cannot be undone.</p>
        <template x-if="deleteError">
          <p class="text-danger small mb-0" x-text="deleteError"></p>
        </template>
      </div>
      <div class="modal-footer border-top-0 bg-light">
        <div class="me-auto">
          <div class="spinner-border align-middle text-danger" role="status" x-show="loading">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal" :disabled="loading">Cancel</button>
        <button type="button" class="btn btn-danger" :disabled="loading" @click="<?php echo $action ?>">
          Delete Page
        </button>
      </div>
    </div>
  </div>
</div>

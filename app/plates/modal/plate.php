<div x-data="modal">
  <div class="modal fade" id="create-page-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="create-page-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white bg-black border-bottom-0">
          <div class="modal-title fs-5 fw-bold" id="create-page-modal-label">Create New Page</div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label mb-0">Page Title <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" x-model="name" :disabled="loading">
            <template x-if="error.name">
              <p class="text-danger small mb-0" x-text="error.name[0]"></p>
            </template>
          </div>
          <div class="mb-3">
            <label class="form-label mb-0">Description</label>
            <input type="text" name="description" class="form-control" x-model="description" :disabled="loading">
          </div>
          <div>
            <label class="form-label mb-0">URL Link</label>
            <input type="text" name="link" class="form-control" x-model="link" :disabled="loading">
            <span class="small text-muted">If not specified, Expresso will try to guess it based on the Page Title.</span>
            <template x-if="error.link">
              <p class="text-danger small mb-0" x-text="error.link[0]"></p>
            </template>
          </div>
        </div>
        <div class="modal-footer border-top-0 bg-light">
          <div class="me-auto">
            <div class="spinner-border align-middle text-black" role="status" x-show="loading">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <button type="button" class="btn btn-link text-black text-decoration-none" data-bs-dismiss="modal" :disabled="loading">Cancel</button>
          <button type="button" class="btn btn-dark" @click="store" :disabled="loading">Create Page</button>
        </div>
      </div>
    </div>
  </div>
</div>
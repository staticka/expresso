<div x-data="trash" @delete-page.window="open($event.detail)">
  <div class="modal fade" id="<?php echo $id ?>" tabindex="-1" aria-labelledby="<?php echo $id ?>-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white bg-danger border-bottom-0">
          <span class="modal-title fs-5 fw-bold" id="<?php echo $id ?>-label">Delete page?</span>
        </div>
        <div class="modal-body">
          <p class="mb-0">Are you sure that you want to delete the this page? This action cannot be undone.</p>
          <template x-if="deleteError">
            <p class="text-danger small" x-text="deleteError"></p>
          </template>
          <div class="card small mt-3">
            <div class="card-body">
              <label class="form-label mb-0">Name of Page</label>
              <div class="fw-bold" x-text="pageName"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 bg-light">
          <div class="me-auto">
            <div class="spinner-border align-middle text-danger" role="status" x-show="loading">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal" :disabled="loading">Cancel</button>
          <button type="button" class="btn btn-danger" :disabled="loading" @click="remove">
            Delete Page
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  const trash =
  {
    pageId: null,

    pageName: null,

    deleteError: null,

    loading: false,

    open(payload)
    {
      this.pageId = payload.id

      this.pageName = payload.name

      this.deleteError = null

      bootstrap.Modal.getOrCreateInstance(document.getElementById('<?php echo $id ?>')).show()
    },

    remove()
    {
      const self = this

      self.loading = true

      axios.delete('<?php echo $link->set('/pages') ?>/' + self.pageId)
        .then(function ()
        {
          window.location.href = '<?php echo $link->set('/pages') ?>'
        })
        .catch(function (error)
        {
          self.deleteError = error.response.data

          self.loading = false
        })
    },
  }
</script>

<div x-data="build">
  <div class="modal fade" id="build-pages" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white bg-success border-bottom-0">
          <span class="modal-title fs-5 fw-bold">Build all available pages?</span>
        </div>
        <div class="modal-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="0" id="accept" x-model="accept" :disabled="loading">
            <div>
              <label class="form-check-label" for="accept">
                I understand that selecting the button below will <span class="fw-bold">BUILD</span> all the available pages from its source folder.
                <span class="text-danger">*</span>
              </label>
              <template x-if="error">
                <div class="mb-3">
                  <p class="text-danger small mb-0">An error occurred, <span x-text="error"></span>.</p>
                </div>
              </template>
            </div>
          </div>
          <div class="alert alert-warning mb-0 mt-3" role="alert">
            Do not close this entire window nor press the back button from the web browser (<span class="bi bi-arrow-left"></span>) during the rebuilding process.
          </div>
        </div>
        <div class="modal-footer border-top-0 bg-light">
          <div class="me-auto">
            <div class="spinner-border align-middle text-success" role="status" x-show="loading">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal" :disabled="loading">Cancel</button>
          <button type="button" class="btn btn-success" :disabled="loading || ! accept" @click="publish">
            <span>Rebuild pages</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  const build =
  {
    accept: false,

    loading: false,

    error: null,

    publish()
    {
      const self = this

      self.loading = true

      const link = '<?php echo $link->set('/build') ?>'

      self.error = null

      axios.post(link)
        .then(function (response)
        {
          setTimeout(function ()
          {
            window.location.reload()
          }, 800)
        })
        .catch(function (error)
        {
          self.loading = false

          self.error = error.response.data
        })
    },
  }
</script>

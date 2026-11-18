<?php $title = htmlspecialchars($page['name']) ?>
<?php echo $layout->load('main', compact('link', 'plate', 'csrf', 'title')) ?>

<?php echo $block->body() ?>
  <?php echo $plate->add('navbar', compact('link')) ?>

  <div x-data="editor" x-init="init()">
    <div class="text-bg-light py-3 border-bottom">
      <div class="container">
        <div class="fw-bold mb-0 fs-4">Page Details</div>
      </div>
    </div>

    <div class="container my-3">
      <div x-show="saved" x-cloak class="alert alert-success mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i>Page details updated successfully!
      </div>
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a href="<?php echo $link->set('/pages') ?>" class="btn btn-outline-dark shadow-sm" :disabled="loading"><i class="bi bi-arrow-left me-1"></i>Back to Pages</a>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-outline-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#delete-page-modal" :disabled="loading"><i class="bi bi-trash me-1"></i>Delete page</button>
          <button type="button" class="btn btn-dark shadow-sm" @click="save()" :disabled="loading">
            <span x-show="! loading"><i class="bi bi-check-lg me-1"></i>Save Details</span>
            <span x-show="loading"><span class="spinner-border spinner-border-sm me-1" role="status"></span>Saving...</span>
          </button>
        </div>
      </div>
    </div>

    <div class="container mb-3">
      <div class="card">
        <div class="card-body pb-0">
          <div class="row">
            <?php foreach ($fields as $field): ?>
              <div class="col-sm-3 mb-3">
                <label for="<?php echo $field ?>" class="form-label mb-0"><?= ucfirst($field) ?></label>
                <input type="text" id="<?php echo $field ?>" name="<?php echo $field ?>" class="form-control" x-model="input.<?php echo $field ?>" :disabled="loading">
                <template x-if="error.<?php echo $field ?>">
                  <p class="text-danger small mb-0" x-text="error.<?php echo $field ?>[0]"></p>
                </template>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>

    <div class="container mb-5">
      <div class="row g-3">
        <div class="col-md-6">
          <div class="card h-100" style="max-height: 600px;">
            <div class="card-header bg-white border-bottom">
              <span class="fw-bold"><i class="bi bi-pencil me-1"></i>Write</span>
            </div>
            <div class="card-body p-0 text-start d-flex flex-column">
              <div x-init="$watch('body', value => parse())" class="flex-grow-1">
                <textarea class="form-control rounded-0 rounded-bottom font-monospace h-100 border-0" style="min-height: 440px;" rows="21" x-model="body" :disabled="loading"></textarea>
              </div>
              <div class="px-3 py-2 bg-light rounded-bottom">
                <small><i class="bi bi-question-circle"></i> The above textbox follows the <a target="_blank" href="https://www.markdownguide.org/basic-syntax/">Markdown format</a>.</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card h-100" style="max-height: 600px;">
            <div class="card-header bg-white border-bottom">
              <span class="fw-bold"><i class="bi bi-eye me-1"></i>Preview</span>
            </div>
            <div class="card-body text-start overflow-auto">
              <div x-html="html"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php echo $plate->add('modal/delete', array('id' => 'delete-page-modal', 'action' => 'remove()')) ?>
  </div>
<?php echo $block->end() ?>

<?php echo $block->set('scripts') ?>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

  <script>
    let editor = <?php echo $data ?>

    const link = '<?php echo $link->set('/pages/' . $page['id']) ?>'

    const deleteLink = '<?php echo $link->set('/pages') ?>/<?php echo $page['id'] ?>'

    editor.error = {}

    editor.deleteName = <?php echo json_encode($page['name']) ?>

    editor.saved = false

    editor.deleteError = null

    editor.init = function ()
    {
      this.parse()
    }

    editor.parse = function ()
    {
      this.html = marked.parse(this.body)
    }

    editor.save = function ()
    {
      const self = this

      let input = self.input
      input.body = self.body

      const form = new FormData

      for (const key of Object.keys(input))
      {
        form.append(key, input[key])
      }

      self.error = {}
      self.loading = true

      axios.post(link, form)
        .then(function ()
        {
          self.saved = true

          setTimeout(function () { self.saved = false }, 2500)
        })
        .catch(function (error)
        {
          self.error = error.response.data
        })
        .finally(function ()
        {
          self.loading = false
        })
    }

    editor.remove = function ()
    {
      const self = this

      self.loading = true

      axios.delete(deleteLink)
        .then(function ()
        {
          window.location.href = '<?php echo $link->set('/pages') ?>'
        })
        .catch(function (error)
        {
          self.deleteError = error.response.data || 'Unable to delete the page.'
        })
        .finally(function ()
        {
          self.loading = false
        })
    }
  </script>
<?php echo $block->end() ?>

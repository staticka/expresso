<?= $layout->load('main'); ?>

<?= $block->set('styles') ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<?= $block->end() ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('link')) ?>

  <div x-data="editor">
    <div class="container mb-3">
      <a href="<?= $link->set('/pages') ?>" class="btn btn-outline-dark shadow-lg" :disabled="loading">Back to Pages</a>
      <button type="button" class="btn btn-dark shadow-lg" @click="save()" :disabled="loading">Save Details</button>
    </div>

    <div class="container mb-3">
      <div class="card shadow-lg">
        <div class="card-body pb-0">
          <div class="row">
            <?php foreach ($fields as $field): ?>
              <div class="col-sm-3 mb-3">
                <label for="<?= $field ?>" class="form-label mb-0"><?= ucfirst($field) ?></label>
                <input type="text" name="<?= $field ?>" class="form-control" x-model="input.<?= $field ?>" :disabled="loading">
                <template x-if="error.<?= $field ?>">
                  <p class="text-danger small mb-0" x-text="error.<?= $field ?>[0]"></p>
                </template>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>

    <div class="container mb-5">
      <div class="row">
        <div class="col-sm-6">
          <div class="card shadow-lg h-100" style="max-height: 600px;">
            <div class="card-header">
              <div class="nav nav-tabs card-header-tabs">
                <div class="nav-item">
                  <div class="nav-link active">Write</div>
                </div>
              </div>
            </div>
            <div class="card-body p-0 text-start">
              <div x-init="$watch('body', value => parse())">
                <textarea class="form-control rounded-0 rounded-bottom font-monospace h-100 border-0" rows="21" x-model="body" :disabled="loading"></textarea>
              </div>
              <div class="px-2">
                <small><i class="bi bi-question-circle"></i> The above textbox follows the <a target="_blank" href="https://www.markdownguide.org/basic-syntax/">Markdown format</a>.</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card shadow-lg h-100" style="max-height: 600px;">
            <div class="card-header">
              <div class="nav nav-tabs card-header-tabs">
                <div class="nav-item">
                  <div class="nav-link active">Preview</div>
                </div>
              </div>
            </div>
            <div class="card-body text-start overflow-auto">
                <div x-html="html"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?= $block->end() ?>

<?= $block->set('scripts') ?>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

  <script>
    let editor = <?= $data ?>

    const link = '<?= $link->set('/pages/' . $page['id']) ?>'

    editor.error = {}

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
          console.log('Updated successfully!')
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
  </script>
<?= $block->end() ?>
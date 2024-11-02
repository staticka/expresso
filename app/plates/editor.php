<?= $layout->load('main'); ?>

<?= $block->set('styles') ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<?= $block->end() ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('url')) ?>

  <div class="container mb-3">
    <a href="<?= $url->set('/pages') ?>" class="btn btn-dark shadow-lg">Back to Pages</a>
  </div>

  <div x-data="editor">
    <div class="container mb-3">
      <div class="card shadow-lg">
        <div class="card-body pb-0">
          <div class="row">
            <?php foreach ($fields as $field): ?>
              <div class="col-sm-3 mb-3">
                <label for="<?= $field ?>" class="form-label mb-0"><?= ucfirst($field) ?></label>
                <input type="text" name="<?= $field ?>" class="form-control" x-model="<?= $field ?>">
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="card shadow-lg h-100" style="max-height: 700px;">
            <div class="card-header">
              <div class="nav nav-tabs card-header-tabs">
                <div class="nav-item">
                  <div class="nav-link active">Write</div>
                </div>
              </div>
            </div>
            <div class="card-body p-0 text-start">
              <div x-init="$watch('body', value => parse())">
                <textarea class="form-control rounded-0 rounded-bottom font-monospace h-100 border-0" rows="25" x-model="body" :disabled="loading"></textarea>
              </div>
              <div class="px-2">
                <small><i class="bi bi-question-circle"></i> The above textbox follows the <a target="_blank" href="https://www.markdownguide.org/basic-syntax/">Markdown format</a>.</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card shadow-lg h-100" style="max-height: 700px;">
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
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <script>
    let editor =
    {
      <?php foreach ($fields as $field): ?>
        <?php if (array_key_exists($field, $page)): ?>
          <?= $field ?>: <?= json_encode($page[$field]) ?>,
        <?php endif ?>
      <?php endforeach ?>
      body: <?= $page['body'] ?>,
      html: null,
      loading: false,
    }

    editor.init = function ()
    {
      this.parse()
    }

    editor.parse = function ()
    {
      this.html = marked.parse(this.body)
    }
  </script>
<?= $block->end() ?>
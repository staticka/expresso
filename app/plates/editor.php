<?= $layout->load('main'); ?>

<?= $block->set('styles') ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<?= $block->end() ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('url')) ?>

  <div class="container mb-3">
    <a href="<?= $url->set('/pages') ?>" class="btn btn-dark shadow-lg">Back to Pages</a>
  </div>

  <div class="container mb-3">
    <h1><?= $page->getName() ?></h1>
  </div>

  <div class="container" x-data="editor">
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
<?= $block->end() ?>

<?= $block->set('scripts') ?>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <script>
    const editor =
    {
      body: <?= json_encode($page->getBody()) ?>,
      html: null,
      loading: false,
      init()
      {
        this.html = marked.parse(this.body)
      },
      parse()
      {
        this.html = marked.parse(this.body)
      },
    }
  </script>
<?= $block->end() ?>
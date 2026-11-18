<?php $title = 'Pages' ?>
<?php echo $layout->load('main', compact('link', 'plate', 'csrf', 'title')) ?>

<?php echo $block->body() ?>
  <?php echo $plate->add('navbar', compact('block', 'link')) ?>

  <div x-data="pages">
    <div class="text-bg-light py-3 border-bottom">
      <div class="container">
        <div class="fw-bold mb-0 fs-4">Pages</div>
      </div>
    </div>
    <div class="container pb-5">
      <?php if (isset($flash)): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3 mb-0" role="alert">
          <i class="bi bi-check-circle-fill me-1"></i><span><?= htmlspecialchars($flash) ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif ?>
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 my-3">
        <button type="button" class="btn btn-primary shadow-lg" data-bs-toggle="modal" data-bs-target="#create-page-modal">
          Create New...
        </button>
        <div>
          <label for="search" class="small text-uppercase fw-bold d-block mb-0">Search by Keyword</label>
          <input type="search" id="search" class="form-control shadow-lg" style="min-width: 200px;" placeholder="Search pages..." x-model="query">
        </div>
      </div>

      <div class="card shadow-lg">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th width="35%">Name of Page</th>
                  <th width="20%">Tags</th>
                  <th width="15%">Page Created</th>
                  <th width="15%">Last Updated</th>
                  <th width="10%"></th>
                </tr>
              </thead>
              <tbody>
                <?php if (! $items): ?>
                  <tr>
                    <td colspan="5" class="text-center text-secondary px-0">
                      <i class="bi bi-folder2-open fs-1 d-block mb-2 opacity-50"></i>
                      No pages create yet. Select the "Create New..." button to create your first page.
                    </td>
                  </tr>
                <?php endif ?>
                <?php foreach ($items as $item): ?>
                  <?php
                  $raw = isset($item['tags']) ? $item['tags'] : '';
                  $tags = array();

                  if (is_array($raw))
                  {
                      $tags = $raw;
                  }
                  else
                  {
                      foreach (explode(',', $raw) as $part)
                      {
                          $part = trim($part);

                          if ($part !== '')
                          {
                              $tags[] = $part;
                          }
                      }
                  }

                  $search = $item['name'] . ' ' . $item['link'] . ' ' . $item['description'] . ' ' . implode(' ', $tags);
                  ?>
                  <tr x-show="matches($el.dataset.search)" data-search="<?= htmlspecialchars($search, ENT_QUOTES) ?>">
                    <td>
                      <a href="<?php echo $link->set('/pages/' . $item['id']) ?>"><?php echo $item['name'] ?></a>
                      <br>
                      <?php if (isset($item['description'])): ?>
                        <span class="small text-secondary"><?php echo $str->truncate($item['description']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($tags): ?>
                        <?php foreach ($tags as $tag): ?>
                          <span class="badge text-bg-light border ex-tag"><?php echo htmlspecialchars($tag) ?></span>
                        <?php endforeach ?>
                      <?php else: ?>
                        <span class="text-muted small">—</span>
                      <?php endif ?>
                    </td>
                    <td><?= date('d M Y h:i A', $item['created_at']) ?></td>
                    <td><?= $item['updated_at'] ? date('d M Y h:i A', $item['updated_at']) : '—' ?></td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <li>
                            <a class="dropdown-item" href="<?php echo $url->set($item['link']) ?>" target="_blank">
                              Open Link <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                          </li>
                          <li><hr class="dropdown-divider"></li>
                          <li>
                            <button type="button" class="dropdown-item text-danger small" data-id="<?= $item['id'] ?>" data-name="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>" @click="trash($el.dataset.id, $el.dataset.name, $el)">
                              <i class="bi bi-trash"></i> Delete page
                            </button>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <?php echo $plate->add('modal/plate') ?>

    <?php echo $plate->add('modal/delete', array('id' => 'delete-modal', 'action' => 'remove()')) ?>
  </div>
<?php echo $block->end() ?>

<?php echo $block->set('scripts') ?>
  <?php echo $plate->add('modal/script', compact('link')) ?>

  <script>
    const pages =
    {
      query: '',

      deleteId: null,

      deleteName: null,

      loading: false,

      deleteError: null,

      matches(text)
      {
        const query = (this.query || '').trim().toLowerCase()

        return ! query || (text || '').toLowerCase().includes(query)
      },

      remove()
      {
        const self = this

        const link = '<?php echo $link->set('/pages') ?>/' + self.deleteId

        self.loading = true

        axios.delete(link)
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

      trash(id, name, el)
      {
        this.deleteId = id

        this.deleteName = name

        const dropdown = el ? el.closest('.dropdown') : null

        if (dropdown)
        {
          const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]')

          if (toggle)
          {
            bootstrap.Dropdown.getOrCreateInstance(toggle).hide()
          }
        }

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('delete-modal'))

        modal.show()
      },
    }
  </script>
<?php echo $block->end() ?>

<?= $layout->load('main'); ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('url')) ?>

  <div class="container mb-3">
    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#new-page-modal">
      Create New Page
    </button>

    <form method="POST" action="<?= $url->set('/pages') ?>">
      <div class="modal fade" id="new-page-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="new-page-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header text-white bg-black border-bottom-0">
              <div class="modal-title fs-5 fw-bold" id="new-page-modal-label">Create New Page</div>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label mb-0">Page title</label>
                <input type="text" name="name" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label mb-0">URL Link</label>
                <input type="text" name="link" class="form-control">
              </div>
              <div>
                <label class="form-label mb-0">Description</label>
                <input type="text" name="description" class="form-control">
              </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
              <button type="button" class="btn btn-link text-black text-decoration-none" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-dark">Create Page</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="container">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Link</th>
          <th>Timestamp</th>
          <th width="5%"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= $item['name'] ?></td>
            <td><?= $item['description'] ?></td>
            <td><?= $item['link'] ?></td>
            <td><?= $item['created_at'] ?></td>
            <td>
              <div class="d-flex">
                <span>
                  <a class="btn btn-dark btn-sm" href="javascript:void(0)">Edit</a>
                </span>
                <span>
                  <a class="btn btn-link btn-sm text-danger text-decoration-none" href="javascript:void(0)">Delete</a>
                </span>
              </div>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
<?= $block->end() ?>
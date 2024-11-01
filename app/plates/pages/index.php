<?= $plate->add('layout/header') ?>
<?= $plate->add('layout/navbar', compact('url')) ?>

<div class="container mb-3">
  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#new-page-modal">
    Create New Page
  </button>

  <form method="POST" action="<?= $url->set('/pages') ?>">
    <div class="modal fade" id="new-page-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="new-page-modal-label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-white bg-secondary border-bottom-0">
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
            <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-secondary">Create Page</button>
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
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= $item['name'] ?></td>
          <td><?= $item['description'] ?></td>
          <td><?= $item['link'] ?></td>
          <td><?= $item['created_at'] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

<?= $plate->add('layout/footer') ?>
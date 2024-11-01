<?= $layout->load('main'); ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('url')) ?>

  <div class="container mb-3">
    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#create-page-modal">
      Create New Page
    </button>

    <?= $plate->add('modal/plate') ?>
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
            <td><?= date('d M Y h:i:s A', $item['created_at']) ?></td>
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

<?= $block->set('scripts') ?>
  <?= $plate->add('modal/script', compact('url')) ?>
<?= $block->end() ?>
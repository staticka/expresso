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
          <th width="20%">Name</th>
          <th width="15%">URL Link</th>
          <th width="30%">Description</th>
          <th width="15%">Tags</th>
          <th width="15%">Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= $item['name'] ?></td>
            <td>
              <a href="<?= $url->set($item['link']) ?>" target="_blank"><?= $item['link'] ?></a>
            </td>
            <td><?= $str->truncate($item['description']) ?></td>
            <td><?= isset($item['tags']) ? $item['tags'] : '' ?></td>
            <td><?= date('d M Y h:i:s A', $item['created_at']) ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
<?= $block->end() ?>

<?= $block->set('scripts') ?>
  <?= $plate->add('modal/script', compact('url')) ?>
<?= $block->end() ?>
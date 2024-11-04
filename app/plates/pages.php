<?= $layout->load('main', compact('link', 'plate')) ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('block', 'link')) ?>

  <div class="container my-3">
    <button type="button" class="btn btn-dark shadow-lg" data-bs-toggle="modal" data-bs-target="#create-page-modal">
      Create New Page
    </button>

    <?= $plate->add('modal/plate') ?>
  </div>

  <div class="container mb-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <table class="table mb-0">
          <thead>
            <tr>
              <th width="15%">Name</th>
              <th width="15%">URL Link</th>
              <th width="30%">Description</th>
              <th width="15%">Tags</th>
              <th width="15%">Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr>
                <td>
                  <a href="<?= $link->set('/pages/' . $item['id']) ?>"><?= $item['name'] ?></a>
                </td>
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
    </div>
  </div>
<?= $block->end() ?>

<?= $block->set('scripts') ?>
  <?= $plate->add('modal/script', compact('link')) ?>
<?= $block->end() ?>
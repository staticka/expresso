<?php echo $layout->load('main', compact('link', 'plate')) ?>

<?php echo $block->body() ?>
  <?php echo $plate->add('navbar', compact('block', 'link')) ?>

  <div class="container my-3">
    <button type="button" class="btn btn-dark shadow-lg" data-bs-toggle="modal" data-bs-target="#create-page-modal">
      Create New Page
    </button>

    <?php echo $plate->add('modal/plate') ?>
  </div>

  <div class="container mb-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <div class="table-responsive">          
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
                    <a href="<?php echo $link->set('/pages/' . $item['id']) ?>"><?php echo $item['name'] ?></a>
                  </td>
                  <td>
                    <a href="<?php echo $url->set($item['link']) ?>" target="_blank"><?php echo $item['link'] ?></a>
                  </td>
                  <td><?php echo $str->truncate($item['description']) ?></td>
                  <td><?= isset($item['tags']) ? $item['tags'] : '' ?></td>
                  <td><?= date('d M Y h:i:s A', $item['created_at']) ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php echo $block->end() ?>

<?php echo $block->set('scripts') ?>
  <?php echo $plate->add('modal/script', compact('link')) ?>
<?php echo $block->end() ?>
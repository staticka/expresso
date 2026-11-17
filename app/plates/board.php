<?php $title = 'Dashboard' ?>
<?php echo $layout->load('main', compact('link', 'plate', 'csrf', 'title')) ?>

<?php echo $block->body() ?>
  <?php echo $plate->add('navbar', compact('link')) ?>

  <section class="text-white py-5" style="background: linear-gradient(135deg, #0b0f19 0%, #12251a 100%);">
    <div class="container py-3">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <h1 class="display-5 fw-bold mb-2">Welcome to Expresso!</h1>
          <p class="lead mb-4 text-white-50">A simple static blog platform based on Staticka for creating and building pages through a web-based interface.</p>
          <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#create-page-modal">Create your first page</button>
        </div>
        <div class="col-lg-5">
          <div class="row g-3">
            <div class="col-12">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white bg-opacity-10">
                <div class="rounded-3 bg-success p-3 fs-4"><i class="bi bi-file-earmark-text"></i></div>
                <div>
                  <div class="fs-2 fw-bold"><?php echo $count ?></div>
                  <div class="text-white-50 small">Total pages created</div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-white bg-opacity-10">
                <div class="rounded-3 bg-success p-3 fs-4"><i class="bi bi-clock-history"></i></div>
                <div>
                  <?php if ($latest): ?>
                    <a href="<?php echo $link->set('/pages/' . $latest['id']) ?>" class="fw-bold text-white text-decoration-underline d-block"><?php echo $latest['name'] ?></a>
                    <div class="text-white-50 small">Last updated at <?= $latest['updated_at'] ? date('d M Y h:i A', $latest['updated_at']) : '—' ?></div>
                  <?php else: ?>
                    <div class="fw-bold">—</div>
                    <div class="text-white-50 small">Most recent page</div>
                  <?php endif ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container pt-3 pb-5">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card shadow-lg h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold">Managing your Pages</h5>
            <p class="text-secondary mb-3">View, edit, and manage all of your pages from the Pages page.</p>
            <a href="<?php echo $link->set('/pages') ?>" class="btn btn-outline-secondary">Go to Pages <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-lg h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold">Compiling pages to HTML</h5>
            <p class="text-secondary mb-3">Build all available pages to HTML. This requires Staticka Console to be installed.</p>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#build-modal">Build site</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php echo $plate->add('modal/plate') ?>
<?php echo $block->end() ?>

<?php echo $block->set('scripts') ?>
  <?php echo $plate->add('modal/script', compact('link')) ?>
<?php echo $block->end() ?>

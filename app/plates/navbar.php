<nav class="navbar py-2 navbar-expand-lg navbar-dark bg-dark shadow-sm" data-bs-theme="dark">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?php echo $link->set('/') ?>">
      <i class="bi bi-cup-hot-fill text-success"></i>
      <span>Expresso</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#x-navbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="x-navbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <?php if ($link->isCurrent('/')): ?>
            <a class="nav-link active" href="<?php echo $link->set('/') ?>">Dashboard</a>
          <?php else: ?>
            <a class="nav-link" href="<?php echo $link->set('/') ?>">Dashboard</a>
          <?php endif ?>
        </li>
        <li class="nav-item">
          <?php if ($link->isCurrent('pages')): ?>
            <a class="nav-link active" href="<?php echo $link->set('/pages') ?>">Pages</a>
          <?php else: ?>
            <a class="nav-link" href="<?php echo $link->set('/pages') ?>">Pages</a>
          <?php endif ?>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <button class="btn btn-success d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#build-pages">
            Build pages
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

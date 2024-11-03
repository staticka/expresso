<div class="navbar py-0 navbar-expand-lg bg-black" data-bs-theme="dark">
  <div class="container py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <span class="navbar-brand mb-1 h1">Expresso</span>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <?php if ($link->isCurrent('/')): ?>
              <div class="nav-link active">Dashboard</div>
            <?php else: ?>
              <a class="nav-link" href="<?= $link->set('/'); ?>">Dashboard</a>
            <?php endif ?>
          </li>
          <li class="nav-item">
            <?php if ($link->isCurrent('pages')): ?>
              <div class="nav-link active">Pages</div>
            <?php else: ?>
              <a class="nav-link" href="<?= $link->set('/pages'); ?>">Pages</a>
            <?php endif ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
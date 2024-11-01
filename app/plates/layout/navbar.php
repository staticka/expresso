<div class="mb-3">
  <div class="navbar navbar-expand-lg bg-black" data-bs-theme="dark">
    <div class="container py-3">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <span class="navbar-brand mb-1 h1">Expresso</span>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link <?= $url->isCurrent('/') ? 'active' : '' ?>" href="<?= $url->set('/'); ?>">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $url->isCurrent('pages') ? 'active' : '' ?>" href="<?= $url->set('/pages'); ?>">Pages</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
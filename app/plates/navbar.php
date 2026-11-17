<nav class="navbar py-2 navbar-expand-lg navbar-dark bg-dark shadow-sm" data-bs-theme="dark">
  <div class="container py-2">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?php echo $link->set('/') ?>">
      <i class="bi bi-cup-hot-fill text-success"></i>
      <span>Expresso</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <?php if ($link->isCurrent('/')): ?>
            <a class="nav-link active" aria-current="page" href="<?php echo $link->set('/') ?>">Dashboard</a>
          <?php else: ?>
            <a class="nav-link" href="<?php echo $link->set('/') ?>">Dashboard</a>
          <?php endif ?>
        </li>
        <li class="nav-item">
          <?php if ($link->isCurrent('pages')): ?>
            <a class="nav-link active" aria-current="page" href="<?php echo $link->set('/pages') ?>">Pages</a>
          <?php else: ?>
            <a class="nav-link" href="<?php echo $link->set('/pages') ?>">Pages</a>
          <?php endif ?>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <button class="btn btn-success d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#build-modal">
            Build pages
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div x-data="build">
  <div class="modal fade" id="build-modal" tabindex="-1" aria-labelledby="build-modal-label" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white bg-success border-bottom-0">
          <span class="modal-title fs-5 fw-bold" id="build-modal-label">Build all available pages?</span>
        </div>
        <div class="modal-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="0" id="accept" x-model="accept" :disabled="loading">
            <label class="form-check-label" for="accept">
              I understand that selecting the button below will <span class="fw-bold">BUILD</span> all the pages.
              <span class="text-danger">*</span>
            </label>
          </div>
          <div class="alert alert-warning mb-0 mt-3" role="alert">
            Do not close this entire window nor press the back button from the web browser (<span class="bi bi-arrow-left"></span>) during the rebuilding process.
          </div>
          <template x-if="error">
            <div class="mt-3">
              <p class="text-danger small mb-0">An error occurred, <span x-text="error"></span>.</p>
            </div>
          </template>
        </div>
        <div class="modal-footer border-top-0 bg-light">
          <div class="me-auto">
            <div class="spinner-border align-middle text-success" role="status" x-show="loading">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal" :disabled="loading">Cancel</button>
          <button type="button" class="btn btn-success" :disabled="loading || ! accept" @click="publish">
            <span>Rebuild pages</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

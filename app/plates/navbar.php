<div class="navbar py-0 navbar-expand-lg bg-black" data-bs-theme="dark">
  <div class="container py-3">
    <span class="navbar-brand mb-1 h1">Expresso</span>
    <div class="collapse navbar-collapse d-flex" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <?php if ($link->isCurrent('/')): ?>
            <div class="nav-link active">Dashboard</div>
          <?php else: ?>
            <a class="nav-link" href="<?= $link->set('/') ?>">Dashboard</a>
          <?php endif ?>
        </li>
        <li class="nav-item">
          <?php if ($link->isCurrent('pages')): ?>
            <div class="nav-link active">Pages</div>
          <?php else: ?>
            <a class="nav-link" href="<?= $link->set('/pages') ?>">Pages</a>
          <?php endif ?>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#build-modal">Build site</button>
        </li>
      </ul>
    </div>
  </div>
</div>

<div x-data="build">
  <div class="modal fade" id="build-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white bg-success border-bottom-0">
          <span class="modal-title fs-5 fw-bold">Rebuild all available pages?</span>
        </div>
        <div class="modal-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="0" id="accept" x-model="accept" :disabled="loading">
            <label class="form-check-label" for="accept">
              I understand that selecting the button below will <span class="fw-bold">REBUILD</span> all the pages.
              <span class="text-danger">*</span>
            </label>
          </div>
          <p class="mb-0 mt-2"><span class="fw-bold text-danger">NOTE:</span> Do not close this entire window nor press the back button from the web browser (<span class="bi bi-arrow-left"></span>) during the rebuilding process.</p>
          <template x-if="error">
            <div class="mt-3">
              <p class="text-danger small mb-0">An error occured, <span x-text="error"></span>.</p>
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
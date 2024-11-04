<?= $layout->load('main', compact('link', 'plate')) ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('link')) ?>

  <div class="p-5 bg-body-tertiary">
    <div class="container">
      <p class="fs-1 fw-bold">Welcome to Expresso!</p>
      <span><a href="https://roug.in/staticka/expresso" target="_blank">Expresso</a> is a simple static blog platform based on <a href="https://roug.in/staticka" target="_blank">Staticka</a> which allows creating and building of pages through a web-based user interface.</span>
    </div>
  </div>

  <div class="my-3">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="card h-100">
            <div class="card-header border-bottom-0 p-3">
                <span class="fw-bold">Creating a new Page</span>
            </div>
            <div class="card-body">
              <p>To create a new page, kindly select the <a href="<?= $link->set('/pages') ?>">Pages</a> link from the navigation bar on top then select the button <code class="p-1 bg-dark text-white rounded">Create New Page</code>.</p>
            </div>
          </div>
        </div>
        <!-- <div class="col-sm-4">
          <div class="card h-100">
            <div class="card-header border-bottom-0 p-3">
                <span class="fw-bold">Compiling the pages to HTML</span>
            </div>
            <div class="card-body">
              <p>Expresso does not yet have a user interface for building pages.</p>
              <p>With this, kindly install <a href="https://roug.in/staticka/console" target="_blank">Staticka Console</a> in the mean time then use its <code>build</code> command:</p>
              <div>
                <pre class="mb-0 p-2 bg-dark text-white"><code>$ vendor/bin/staticka build</code></pre>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
<?= $block->end() ?>
<?php echo $layout->load('main', compact('link', 'plate')) ?>

<?php echo $block->body() ?>
  <?php echo $plate->add('navbar', compact('link')) ?>

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
              <p>Kindly select the <a href="<?php echo $link->set('/pages') ?>">Pages</a> link from the navigation bar on top then select the <code class="p-1 bg-dark text-white rounded">Create New Page</code> button to create a new page.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card h-100">
            <div class="card-header border-bottom-0 p-3">
                <span class="fw-bold">Compiling the pages to HTML</span>
            </div>
            <div class="card-body">
              <p>Select the <code class="p-1 bg-success text-white rounded">Build site</code> button from the navigation bar to build all available pages. This functionality requires <a href="https://roug.in/staticka/console" target="_blank">Staticka Console</a> to be installed and configured.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php echo $block->end() ?>
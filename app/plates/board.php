<?= $layout->load('main'); ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('link')) ?>

  <div class="container">
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione accusantium iste quas et, delectus debitis fugit minima temporibus illum. Architecto officia esse officiis, tempore, voluptates aliquam voluptatibus laudantium eius necessitatibus.</p>
  </div>
<?= $block->end() ?>
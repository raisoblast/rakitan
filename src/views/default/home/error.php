<?php $this->layout('layout') ?>

<section id="doc" class="py4 white">
  <div class="clearfix mxn2">
    <div class="col-8 mx-auto">
      <div class="border rounded bg-red">
        <div class="px2">
          <p class="h2 m1">Error occured!</p>
        </div>
        <div class="p2 bg-lighten-2">
          <?= $message ?>
        </div>
      </div>
    </div>
  </div>
</section>

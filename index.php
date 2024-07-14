<?php
require_once'header.php';
?>
<div class="container mt-5">
  <h1 class="text-center">Wetteranzeige</h1>
  <div class="row featurette">
    <div class="col-md-4">
      <img src="assets/img/Wetter.png" class="img-fluid mx-auto rounded my-5 d-block" width="400" height="400" alt="Logo Wetter">
    </div>
    <div class="col-md-8">
      <h3 class="featurette-heading fw-normal lh-1" id="placeholder-heading">
        <span class="text-body-secondary ms-2">Wetterinformationen</span>
      </h3>
      <div class="lead px-2" id="placeholder-content">
        <p>Wetterdaten werden geladen...</p>
      </div>
    </div>
  </div>
</div>
<?php
require_once'footer.php';

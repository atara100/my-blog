<?php
include './include/header.php';
?>

<h2 class="">My-Blog</h2>
<h1 class="mt-5">My-Blog</h1>
<h3 class="mt-3">About us...</h3>
<?php
echo <<<ABOTTEXT
<div class="mt-5 lh-base card w-75  mx-auto d-flex">
  <div class="card-body fs-4 row mx-auto">
   <p class="text-center">
    we are a blogging site,</br>
    here you can see posts of other people in all kinds of topics.</br>
    you can also right and upload posts by yourself</br>
     for uploading posts you have to register.</br>
    we are waiting for you!</br>
   </p>
   
   <img  src="https://cdn.pixabay.com/photo/2012/05/07/18/57/blog-49006_640.png" alt="my-blog image">
  </div>
</div>
ABOTTEXT;
?>


<?php
include './include/footer.php';

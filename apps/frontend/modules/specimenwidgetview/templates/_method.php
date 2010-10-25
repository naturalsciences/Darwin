<ul class="tool">
 <?php foreach ($form as $method) : ?>
   <?php echo ("<li>".$method->getMethod()."</li>") ; ?>
 <?php endforeach ; ?>
</ul>

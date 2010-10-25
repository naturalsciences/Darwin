<ul class="tool">
 <?php foreach ($form as $tool) : ?>
   <?php echo ("<li>".$tool->getTool()."</li>") ; ?>
 <?php endforeach ; ?>
</ul>

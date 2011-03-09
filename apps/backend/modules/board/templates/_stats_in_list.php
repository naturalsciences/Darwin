  <ul>
  <?php foreach($result as $value) : ?>
    <li><?php echo($field.' : '.$value[strtolower($field)]) ; ?></li>
  <?php endforeach ; ?>
  </ul>

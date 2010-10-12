<?php echo '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/css" href="/css/rss.css" ?>';?>

<rdf:RDF  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns="http://purl.org/rss/1.0/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:georss="http://www.georss.org/georss">
<docs>Rss File Of Results</docs>
<channel>
<link>http://platial.com</link>
<title>Crschmidt's Places At Platial</title>
<description></description>
</channel>
<?php foreach($items as $item):?>
  <item >
<!--     <link>http://platial.com/place/90306</link>  i we want the title to be a link-->
    <title><?php echo $item->getCode();?></title>
    <description><![CDATA[
      <div class="map_result_id_<?php echo $item->getId();?>">
        <div class="item_name hidden"><?php echo $item->getTagsWithCode(ESC_RAW);?></div>
        <div class=""><?php echo $item->getName(ESC_RAW);?></div>
        <?php if(! $is_choose):?>
          <?php echo link_to(image_tag('edit.png',array('title' => 'Edit')),'gtu/edit?id='.$item->getId());?>
        <?php echo link_to(image_tag('duplicate.png',array('title' => 'Duplicate')),'gtu/new?duplicate_id='.$item->getId());?>
        <?php else:?>
          <div class="result_choose" onclick="chooseGtuInMap(<?php echo $item->getId();?>);"><?php echo __('Choose');?></div>
        <?php endif;?>
      </div>
    ]]></description>

    <georss:point><?php echo $item->getLatitude();?> <?php echo $item->getLongitude();?></georss:point>
  </item>
<?php endforeach;?>
</rdf:RDF>

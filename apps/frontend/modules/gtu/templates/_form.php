<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<script type="text/javascript">
$(document).ready(function () 
{
  $('body').catalogue({});
});
</script>

<?php echo form_tag('gtu/'.($form->getObject()->isNew() ? 'create' : 'update?id='.$form->getObject()->getId()), array('class'=>'edition'));?>

<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th class="top_aligned"><?php echo $form['code']->renderLabel() ?></th>
        <td>
          <?php echo $form['code']->renderError() ?>
          <?php echo $form['code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_from_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_from_date']->renderError() ?>
          <?php echo $form['gtu_from_date'] ?>
        </td>
      </tr>
      <tr>
        <th class="top_aligned"><?php echo $form['gtu_to_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_to_date']->renderError() ?>
          <?php echo $form['gtu_to_date'] ?>
        </td>
      </tr>
      <tr>
        <th class="top_aligned"><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['parent_ref']->renderError() ?>
          <?php echo $form['parent_ref'] ?>
        </td>
      </tr>
    </tbody>
</table>

<?php
$tag_grouped = array();
$avail_groups = TagGroups::getGroups(); 
foreach($form['TagGroups'] as $group)
{
  $type = $group['group_name']->getValue();
  if(!isset($tag_grouped[$type]))
    $tag_grouped[$type] = array();
  $tag_grouped[$type][] = $group;
}
foreach($form['newVal'] as $group)
{
  $type = $group['group_name']->getValue();
  if(!isset($tag_grouped[$type]))
    $tag_grouped[$type] = array();
  $tag_grouped[$type][] = $group;
}
?>

<div class="tag_parts_screen" alt="<?php echo url_for('gtu/addGroup'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>">
<?php foreach($tag_grouped as  $group_key => $sub_forms):?>
  <fieldset alt="<?php echo $group_key;?>">
    <legend><?php echo __($avail_groups[$group_key]);?></legend>
    <ul>
      <?php foreach($sub_forms as $form_value):?>
	<?php include_partial('taggroups', array('form' => $form_value));?>
      <?php endforeach;?>
    </ul>
    <a class="sub_group"><?php echo __('Add Sub Group');?></a>
  </fieldset>
<?php endforeach;?>
</div>


  <div class="gtu_groups_add">
    <select id="groups_select">
      <option value=""></option>
      <?php foreach(TagGroups::getGroups() as $k => $v):?>
	<option value="<?php echo $k;?>"><?php echo $v;?></option>
      <?php endforeach;?>
    </select>
    <a href="<?php echo url_for('gtu/addGroup'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>" id="add_group"><?php echo __('Add Group');?></a>
  </div>

  <fieldset id="location">
    <legend><?php echo __('Localisation');?></legend>
    <table>
      <tr>
        <th><?php echo $form['latitude']->renderLabel() ;?><?php echo $form['latitude']->renderError() ?></th>
        <th><?php echo $form['longitude']->renderLabel(); ?><?php echo $form['longitude']->renderError() ?></th>
        <th><?php echo $form['lat_long_accuracy']->renderLabel() ;?><?php echo $form['lat_long_accuracy']->renderError() ?></th>
        <th></th>
      </tr>
      <tr>
        <td><?php echo $form['latitude'];?></td>
        <td><?php echo $form['longitude'];?></td>
        <td><?php echo $form['lat_long_accuracy'];?></td>
        <td><strong><?php echo _('m');?></strong> <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?></td>
      </tr>

      <tr>
        <th></th>
        <th><?php echo $form['elevation']->renderLabel(); ?><?php echo $form['elevation']->renderError() ?></th>
        <th><?php echo $form['elevation_accuracy']->renderLabel() ;?><?php echo $form['elevation_accuracy']->renderError() ?></th>
        <th></th>
      </tr>
      <tr>
        <td></td>
        <td><?php echo $form['elevation'];?></td>
        <td><?php echo $form['elevation_accuracy'];?></td>
        <td><strong><?php echo _('m');?></strong> <?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?></td>
      </tr>
      <tr>
        <td colspan="3"><div style="width:100%; height:300px;" id="map"></div></td>
        <td>

<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">

  var epsg4326 = new OpenLayers.Projection("EPSG:4326");
  var markers;
  var marker;
  var center;
  var zoom = 2;
  var pointFeature;
  var point;
  options = {
//     restrictedExtent: extent,
    controls: [
      new OpenLayers.Control.Navigation(), 
      new OpenLayers.Control.PanZoomBar(),
      new OpenLayers.Control.LayerSwitcher()
    ],
    numZoomLevels: 20,
    displayProjection: new OpenLayers.Projection("EPSG:4326"),
    maxExtent: new OpenLayers.Bounds(-180,-90, 180, 90),
    maxResolution: 0.3515625,
    units: "m"
  };
  var style_blue = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);
  style_blue.strokeColor = "blue"; 
  style_blue.fillColor = "blue"; 
// OpenLayers.Layer.XYZ.

  var map = new OpenLayers.Map("map", options);

  var mapnik = new OpenLayers.Layer.OSM();
  mapnik.addOptions({wrapDateLine:true});
  map.addLayer(mapnik);

  // the SATELLITE layer has all 22 zoom level, so we add it first to
    // become the internal base layer that determines the zoom levels of the
    // map.
    var gsat = new OpenLayers.Layer.Google(
        "Google Satellite",
        {sphericalMercator: true,type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
    );
    var gphy = new OpenLayers.Layer.Google(
        "Google Physical",
        {sphericalMercator: true,type: google.maps.MapTypeId.TERRAIN, visibility: false}
    );
    var gmap = new OpenLayers.Layer.Google(
        "Google Streets", // the default
        {sphericalMercator: true,numZoomLevels: 20, visibility: false}
    );
    var ghyb = new OpenLayers.Layer.Google(
        "Google Hybrid",
        {sphericalMercator: true,type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: false}
    );
    var vectorLayer = new OpenLayers.Layer.Vector("Simple Geometry", { displayInLayerSwitcher: false, projection: new OpenLayers.Projection("EPSG:4326")});
    map.addLayers([gsat, gphy, gmap, ghyb,vectorLayer]);


  markers = new OpenLayers.Layer.Markers("Markers", {
    displayInLayerSwitcher: false,
    units: "m",
    projection: "EPSG:900913"
   });
  map.addLayer(markers);

  map.zoomToMaxExtent();
  

  map.events.register("click", map, setPoint);
  map.events.register("zoomend", map, setZoom);
  <?php if($form->getObject()->getLongitude() != ''):?>
    centre = new OpenLayers.LonLat(<?php echo $form->getObject()->getLongitude();?>, <?php echo $form->getObject()->getLatitude();?>);
    zoom = 13;
    setMapCenter(centre, zoom);
    drawLatLong();
  <?php else:?>
        //var bbox = new OpenLayers.Bounds(2.54694366455078, 49.4936027526855, 6.40386152267456, 51.5054512023926);
    setMapCenter(new OpenLayers.LonLat(0,0), 2);
// map.zoomToMaxExtent();

  <?php endif;?>
$('#gtu_lat_long_accuracy').change(drawAccuracy);
$('#gtu_longitude').change(drawLatLong);
$('#gtu_latitude').change(drawLatLong);


function setZoom(e)
{
  if(pointFeature)
  {
    drawAccuracy()
  }
}

function getAccuracySize()
{
  zoom = map.getZoom();
  resolution = map.getResolutionForZoom(zoom);
  return $('#gtu_lat_long_accuracy').val() / resolution;
}

function drawAccuracy()
{
  vectorLayer.removeAllFeatures();
  point = new OpenLayers.Geometry.Point(new_pos.lon, new_pos.lat);
  style_blue.pointRadius = getAccuracySize();
  pointFeature = new OpenLayers.Feature.Vector(point, null, style_blue);
  vectorLayer.addFeatures([pointFeature]);
  vectorLayer.redraw();
}

function drawLatLong()
{
  if (marker) {
    removeMarkerFromMap(marker);
  }
  lonlat = new OpenLayers.LonLat($('#gtu_longitude').val(), $('#gtu_latitude').val())
  marker = addMarkerToMap(lonlat, null);
}

function setPoint( e )
{
  var lonlat = getEventPosition(e).wrapDateLine();
  $('#gtu_latitude').val(lonlat.lat);
  $('#gtu_longitude').val(lonlat.lon);

////GOOGLE ELE
var latlng = new google.maps.LatLng(lonlat.lat,lonlat.lon);
elevator = new google.maps.ElevationService();
var positionalRequest = {'locations': [latlng] };
elevator.getElevationForLocations(positionalRequest, function(results, status) 
{
  if (status == google.maps.ElevationStatus.OK && results[0]) 
  {
    $('#gtu_elevation').val(results[0].elevation.toFixed(3));
  }
});
//// GOOGLE ELE

  drawLatLong();
}

function addMarkerToMap(position, icon)
{
  new_pos = position.clone().transform(epsg4326, map.getProjectionObject());
  var marker = new OpenLayers.Marker(position.clone().transform(epsg4326, map.getProjectionObject()), icon);
  markers.addMarker(marker);

            // create a point feature

  drawAccuracy();
  return marker;
}

function removeMarkerFromMap(marker)
{
   markers.removeMarker(marker);
}

function getEventPosition(event)
{
  return map.getLonLatFromViewPortPx(event.xy).clone().transform(map.getProjectionObject(), epsg4326);
}

function setMapCenter(center, zoom)
{
  zoom = parseInt(zoom);
  var numzoom = map.getNumZoomLevels();
  if (zoom >= numzoom) zoom = numzoom - 1;
  map.setCenter(center.clone().transform(epsg4326, map.getProjectionObject()), zoom);
}
 $('#location .clear_prop').click(function()
  {
    $(this).closest('tr').find('input').val('');
    drawLatLong();

  });
</script>
</td>
      </tr>
    </table>

  </fieldset>

  <table>
    <tfoot>
      <tr>
        <td>
          <?php echo $form->renderHiddenFields(true) ?>

          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New Gtu'), 'gtu/new') ?>
            &nbsp;<?php echo link_to(__('Duplicate Gtu'), 'gtu/new?duplicate_id='.$form->getObject()->getId()) ?>
          <?php endif?>

          &nbsp;<a href="<?php echo url_for('gtu/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'gtu/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>


<script  type="text/javascript">
$(document).ready(function () {
    $('#gtu_parent_ref').change(function()
    {
      $("#gtu_parent_ref_name").html(trim(ref_element_name));
    });


    $('.tag_parts_screen .clear_prop').live('click', function()
    {
      parent = $(this).closest('li');
      nvalue='';
      $(parent).find('input').val(nvalue);
      $(parent).hide();

      sub_groups  = parent.parent();
      if(sub_groups.find("li:visible").length == 0)
      {
	      sub_groups.closest('fieldset').hide();
      	disableUsedGroups();
      }
    });

    
    function disableUsedGroups()
    {
      $('#groups_select option').removeAttr('disabled');
      $('.tag_parts_screen fieldset:visible').each(function()
      {
	      var cur_group = $(this).attr('alt');
	      $("#groups_select option[value='"+cur_group+"']").attr('disabled','disabled');
	      if($("#groups_select option[value='"+cur_group+"']:selected"))
	        $('#groups_select').val("");
      });
    }
    disableUsedGroups();
    $('.purposed_tags li').live('click', function()
    {
      input_el = $(this).parent().closest('li').find('input[id$="_tag_value"]');
      if(input_el.val().match("\;\s*$"))
	input_el.val( input_el.val() + $(this).text() );
      else
	input_el.val( input_el.val() + " ; " +$(this).text() );
      input_el.trigger('click');
    });

    $('input[id$="_tag_value"]').live('keydown click',purposeTags);

   function purposeTags(event)
   {
      if (event.type == 'keydown')
      {
	var code = (event.keyCode ? event.keyCode : event.which);
	if (code != 59 /* ;*/ && code != $.ui.keyCode.SPACE ) return;
      }
      parent_el = $(this).closest('li');
      group_name = parent_el.find('input[name$="\[group_name\]"]').val();
      sub_group_name = parent_el.find('[name$="\[sub_group_name\]"]').val();
      if(sub_group_name == '' || $(this).val() == '') return;
      $('.purposed_tags').hide();
      $.ajax({
	  type: "GET",
	  url: "<?php echo url_for('gtu/purposeTag');?>" + '/group_name/' + group_name + '/sub_group_name/' + sub_group_name + '/value/'+ $(this).val(),
	  success: function(html)
	  {
	    parent_el.find('.purposed_tags').html(html);
	    parent_el.find('.purposed_tags').show();
	  }
      });
    }

    $('#add_group').click(function()
    {
      selected_group = $('#groups_select option:selected').val();
      selected_group_name = $('#groups_select option:selected').text();
      if(selected_group != '')
      {
	$.ajax({
	    type: "GET",
	    url: $('.tag_parts_screen').attr('alt')+'/group/'+ selected_group + '/num/' + (0+$('.tag_parts_screen ul li').length),
	    success: function(html)
	    {
	      if( $('fieldset[alt="'+selected_group+'"]').length != 0)
	      {
		fld_set = $('fieldset[alt="'+selected_group+'"]');
		fld_set.find('> ul').append(html);
		fld_set.show();
	      }
	      else
	      {
		html = '<fieldset alt="'+ selected_group +'"><legend>' + selected_group_name + '</legend><ul>'+html+'</ul><a class="sub_group"><?php echo __('Add Sub Group');?></a></fieldset>';
		$('.tag_parts_screen').append(html);
	      }
	      disableUsedGroups();
	    }
	  });
      }
      return false;
    });

    $('a.sub_group').live('click',function()
    {
      fieldset = $(this).closest('fieldset');
      selected_group = fieldset.attr('alt');
      list =  fieldset.find('> ul');
      $.ajax({
	  type: "GET",
	  url: $('.tag_parts_screen').attr('alt')+'/group/'+ selected_group + '/num/' + (0+$('.tag_parts_screen ul li').length),
	  success: function(html)
	  {
	    list.append(html);
	  }
	});
      return false;
    });

});
</script>

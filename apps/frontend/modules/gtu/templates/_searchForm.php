<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<?php echo form_tag('gtu/search'.( isset($is_choose) && $is_choose  ? '?is_choose='.$is_choose : '') , array('class'=>'search_form','id'=>'gtu_filter'));?>
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>  
        <tr>
          <th><?php echo $form['code']->renderLabel() ?></th>
          <th><?php echo $form['gtu_from_date']->renderLabel(); ?></th>
          <th><?php echo $form['gtu_to_date']->renderLabel(); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['code']->render() ?></td>
          <td><?php echo $form['gtu_from_date']->render() ?></td>
          <td><?php echo $form['gtu_to_date']->render() ?></td>
          <td></td>
        </tr>
        <tr>
          <th colspan="4"><?php echo $form['tags']->renderLabel() ?></th>
        </tr>

        <?php echo include_partial('andSearch',array('form' => $form['Tags'][0], 'row_line' => 0));?>

        <tr class="and_row">
          <td colspan="3"></td>
          <td>
            <?php echo image_tag('add_blue.png');?> <a href="<?php echo url_for('gtu/andSearch');?>" class="and_tag"><?php echo __('And'); ?></a>
          </td>
        </tr>
      </tbody>

      </table>

      <fieldset id="lat_long_set">
        <legend><?php echo __('Show Result as map');?> <input type="checkbox" id="show_as_map"></legend>
          <table>
            <tr>
              <td>
              </td>
              <th>
                <?php echo $form['lat_from']->renderLabel();?>
              </th>
              <th>
                <?php echo $form['lon_from']->renderLabel();?>
              </th>
            </tr>
            <tr>
              <th class="right_aligned"><?php echo __('Between');?></th>
              <td><?php echo $form['lat_from'];?></td>
              <td><?php echo $form['lon_from'];?><?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?></td>
            </tr>
            <tr>
              <th class="right_aligned"><?php echo __('And');?></th>
              <td><?php echo $form['lat_to'];?></td>
              <td><?php echo $form['lon_to'];?><?php echo image_tag('remove.png', 'alt=Delete class=clear_prop'); ?></td>
            </tr>
          </table>

          <div style="width:100%; height:400px;display:none;" id="smap"></div>
    </fieldset>


                <?php echo $form->renderHiddenFields();?>
                <input class="search_submit right_button" type="submit" name="search" value="<?php echo __('Search'); ?>" />
<div class="clear"></div>

    <script  type="text/javascript">
    var results;
    var selectControl=0;
    $(document).ready(function()
    {
       $('#show_as_map').click(function(){
          if($(this).is(':checked'))
          {
            $('#smap').show();
            setMapCenter(new OpenLayers.LonLat(0,0), 2);
            //$(this).closest('form').removeClass('search_form');
            $('#gtu_filter').unbind('submit.sform');
            $('#gtu_filter').bind('submit.map_form',map_submit);
            $('.search_results_content').html('');
            $('#lat_long_set table').hide();
          }
          else
          {
             //$(this).closest('form').addClass('search_form');
            $('#gtu_filter').unbind('submit.map_form');
            $('#gtu_filter').bind('submit.sform',search_form_submit);
            $('#gtu_filters_lat_from').val('');
            $('#gtu_filters_lon_from').val('');

            $('#gtu_filters_lat_to').val('');
            $('#gtu_filters_lon_to').val('');
            $('#lat_long_set table').show();
            $('#smap').hide();
          }
       });
       initMap("smap");
       map.events.register("moveend", map, updateLatLong);
       $('#lat_long_set .clear_prop').click(function()
        {
          $(this).closest('tr').find('input').val('');
        });

    });

    function updateLatLong()
    {
      bounds = map.getExtent();
      p1 = new OpenLayers.LonLat( bounds.right,bounds.bottom);
      p2 = new OpenLayers.LonLat( bounds.left, bounds.top);
    
      p1.transform(map.getProjectionObject(), epsg4326).wrapDateLine();
      p2.transform(map.getProjectionObject(), epsg4326).wrapDateLine();
//       console.log(p1.lat +" , " +p1.lon + '   '+ p2.lat +" , " +p2.lon);
      $('#gtu_filters_lat_from').val(p1.lat);
      $('#gtu_filters_lon_from').val(p1.lon);
      
      $('#gtu_filters_lat_to').val(p2.lat);
      $('#gtu_filters_lon_to').val(p2.lon);
    }

    function map_submit(event)
    {
      event.preventDefault();
      if(results)
      {
        map.removeLayer(results);
        markers.clearMarkers();
      }
      updateLatLong()
      removeAllPopups();
      results = new OpenLayers.Layer.Vector("Results", {
        units: "m", 
        strategies: [new OpenLayers.Strategy.Fixed()],
        protocol: new OpenLayers.Protocol.HTTP({
          url: $('#gtu_filter').attr('action')+'/format/xml?gtu_filters%5Brec_per_page%5D=50&'+ $('#gtu_filter').serialize(),
          format: new OpenLayers.Format.KML()
        })
      });
      map.addLayer(results);
      results.events.register("loadend", results, addMarkersFromFeatures);


      selectControl = new OpenLayers.Control.SelectFeature(results, {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});
      map.addControl(selectControl);

      /**** HACK FOR DRAGGING ON FEATURES ****/
      if (typeof(selectControl.handlers) != "undefined") { // OL 2.7 
                      selectControl.handlers.feature.stopDown = false; 
                  } else if (typeof(selectControl.handler) != "undefined") { // OL < 2.7 
                      selectControl.handler.stopDown = false;  
                      selectControl.handler.stopUp = false;  
      }
      /*** END OF HACK ***/
      selectControl.activate();  
      return false;
    }
function removeAllPopups()
{
  if(selectControl != 0)
    selectControl.unselectAll();
  for( var i = 0; i < map.popups.length; i++ )
  {
    map.removePopup(map.popups[i]);
  }
}

function onFeatureSelect(feature) {
  removeAllPopups()
  selectedFeature = feature;
  popup = new OpenLayers.Popup.FramedCloud("chicken", 
    feature.geometry.getBounds().getCenterLonLat(),
    new OpenLayers.Size(100,100),
      "<div><strong>"+feature.attributes.name+"</strong><br/>"+feature.attributes.description+"</div>",
      null, true, onPopupClose);
  feature.popup = popup;
  map.addPopup(popup);
}

function onPopupClose(evt) {
//  selectControl.unselect(selectedFeature);
  onFeatureUnselect(selectedFeature);
}

function onFeatureUnselect(feature) {
if (feature.popup) {
    map.removePopup(feature.popup);
    feature.popup.destroy();
    feature.popup = null;
  }
}
function addMarkersFromFeatures()
{
  for( var i = 0; i < results.features.length; i++ )
  {
    m = new OpenLayers.Marker(results.features[i].geometry.getBounds().getCenterLonLat());
    markers.addMarker(m,results.features[i].attributes.description);

    m.events.register("mousedown", m, function()
    {
      for( var i = 0; i < results.features.length; i++ )
      {
        if(results.features[i].geometry.getBounds().getCenterLonLat() == this.lonlat)
          onFeatureSelect(results.features[i]);
      }
    });
  }
}


      var num_fld = 1;
      $('.and_tag').click(function()
      {
        $.ajax({
            type: "GET",
            url: $(this).attr('href') + '/num/' + (num_fld++) ,
            success: function(html)
            {
              $('table.search > tbody .and_row').before(html);
            }
        });
        return false;
      });
    </script>
    <div class="search_results">
      <div class="search_results_content"> 
      </div>
    </div>
    <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for('gtu/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form> 

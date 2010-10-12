
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

          <div style="width: 600px; height:400px;display:none;" id="smap"></div>
          <?php if(! (isset($is_choose) && $is_choose) ):?>
              <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
              <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
              <?php echo javascript_include_tag('map.js'); ?>
            <?php endif;?>

    </fieldset>


<script  type="text/javascript">
    var results;
    $(document).ready(function()
    {
       $('#show_as_map').click(function(){
          if($(this).is(':checked'))
          {
            $('#smap').show();
            bounds = new OpenLayers.Bounds();
            p1 = new OpenLayers.LonLat($('#specimen_search_filters_lon_from').val(),$('#specimen_search_filters_lat_from').val()).clone().transform(epsg4326, map.getProjectionObject());
            p2 = new OpenLayers.LonLat($('#specimen_search_filters_lon_to').val(),$('#specimen_search_filters_lat_to').val()).clone().transform(epsg4326, map.getProjectionObject())
            bounds.extend(p1);
            bounds.extend(p2);

            map.zoomToExtent(bounds,true);//setMapCenter(new OpenLayers.LonLat(0,0), 2);
            $('#lat_long_set table').hide();
             $('#smap').css('width','100%');

          }
          else
          {

            /*$('#specimen_search_filters_lat_from').val('');
            $('#specimen_search_filters_lon_from').val('');

            $('#specimen_search_filters_lat_to').val('');
            $('#specimen_search_filters_lon_to').val('');*/
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
      $('#specimen_search_filters_lat_from').val(p1.lat);
      $('#specimen_search_filters_lon_from').val(p1.lon);
      
      $('#specimen_search_filters_lat_to').val(p2.lat);
      $('#specimen_search_filters_lon_to').val(p2.lon);
    }
</script>
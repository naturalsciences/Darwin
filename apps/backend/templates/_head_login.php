<td class="header_menu">
  <div class="menu_top">
    <table>
      <tr>
        <td rowspan="2" style="height:90px">&nbsp;</td>
      </tr>
      <tr>
        <td>
          <ul class="menu_link">
            <li><?php echo link_to(__('Our collections'),$sf_context->getConfiguration()->generatePublicUrl('homepage', array(), $sf_request));?></li>
            <li><?php echo link_to(__('Search'),$sf_context->getConfiguration()->generatePublicUrl('search', array(), $sf_request));?></li>
            <li><?php echo link_to(__('Take a tour'),$sf_context->getConfiguration()->generatePublicUrl('tour', array(), $sf_request));?></li>
            <li><?php echo link_to(__('Contacts'),$sf_context->getConfiguration()->generatePublicUrl('contact', array(), $sf_request));?></li>
            <li><?php echo link_to(__('About'),$sf_context->getConfiguration()->generatePublicUrl('about', array(), $sf_request));?></li>
          </ul>
        </td>
      </tr>
      <tr>
        <td class="lang_picker">
          <ul style="">
            <li><?php echo link_to('En','account/lang?lang=en');?></li>
            <li class="sep">|<li>
            <li><?php echo link_to('Fr','account/lang?lang=fr');?></li>
            <li class="sep">|<li>
            <li><?php echo link_to('Nl','account/lang?lang=nl');?></li>
          </ul>
        </td>
      </tr>
    </table>
    <div class="blue_line"></div>
  </div>
</td>

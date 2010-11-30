<td class="header_menu">
  <div class="menu_top">
    <table>
      <tr>
        <td rowspan="2" style="height:90px">&nbsp;</td>
      </tr>
      <tr>
        <td>
          <ul class="menu_link">
            <li><?php echo link_to(__('Our collections'),$sf_context->getConfiguration()->generatePublicUrl('homepage'));?></li>
            <li><?php echo link_to(__('Search'),$sf_context->getConfiguration()->generatePublicUrl('homepage').'search/search');?></li>
            <li><?php echo link_to(__('Take a tour'),$sf_context->getConfiguration()->generatePublicUrl('homepage'));?></li>
            <li><?php echo link_to(__('Contacts'),$sf_context->getConfiguration()->generatePublicUrl('homepage'));?></li>
            <li><?php echo link_to(__('Links'),$sf_context->getConfiguration()->generatePublicUrl('homepage'));?></li>
          </ul>
        </td>
      </tr>
      <tr>
        <td class="lang_picker">
          <ul style="">
            <li><?php echo link_to('En','board/lang?lang=en');?></li>
            <li class="sep">|<li>
            <li><?php echo link_to('Fr','board/lang?lang=fr');?></li>
            <li class="sep">|<li>
            <li><?php echo link_to('Nl','board/lang?lang=nl');?></li>
          </ul>
        </td>
      </tr>
    </table>
    <div class="blue_line"></div>
  </div>
</td>

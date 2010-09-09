<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('search/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('search/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'search/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['spec_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['spec_ref']->renderError() ?>
          <?php echo $form['spec_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['category']->renderLabel() ?></th>
        <td>
          <?php echo $form['category']->renderError() ?>
          <?php echo $form['category'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_ref']->renderError() ?>
          <?php echo $form['collection_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_type']->renderError() ?>
          <?php echo $form['collection_type'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_code']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_code']->renderError() ?>
          <?php echo $form['collection_code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_name']->renderError() ?>
          <?php echo $form['collection_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_institution_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_institution_ref']->renderError() ?>
          <?php echo $form['collection_institution_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_institution_formated_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_institution_formated_name']->renderError() ?>
          <?php echo $form['collection_institution_formated_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_institution_formated_name_ts']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_institution_formated_name_ts']->renderError() ?>
          <?php echo $form['collection_institution_formated_name_ts'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_institution_formated_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_institution_formated_name_indexed']->renderError() ?>
          <?php echo $form['collection_institution_formated_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_institution_sub_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_institution_sub_type']->renderError() ?>
          <?php echo $form['collection_institution_sub_type'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_main_manager_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_main_manager_ref']->renderError() ?>
          <?php echo $form['collection_main_manager_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_main_manager_formated_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_main_manager_formated_name']->renderError() ?>
          <?php echo $form['collection_main_manager_formated_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_main_manager_formated_name_ts']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_main_manager_formated_name_ts']->renderError() ?>
          <?php echo $form['collection_main_manager_formated_name_ts'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_main_manager_formated_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_main_manager_formated_name_indexed']->renderError() ?>
          <?php echo $form['collection_main_manager_formated_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_parent_ref']->renderError() ?>
          <?php echo $form['collection_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['collection_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['collection_path']->renderError() ?>
          <?php echo $form['collection_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_ref']->renderError() ?>
          <?php echo $form['expedition_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_name']->renderError() ?>
          <?php echo $form['expedition_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_name_ts']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_name_ts']->renderError() ?>
          <?php echo $form['expedition_name_ts'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['expedition_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['expedition_name_indexed']->renderError() ?>
          <?php echo $form['expedition_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['station_visible']->renderLabel() ?></th>
        <td>
          <?php echo $form['station_visible']->renderError() ?>
          <?php echo $form['station_visible'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_ref']->renderError() ?>
          <?php echo $form['gtu_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_code']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_code']->renderError() ?>
          <?php echo $form['gtu_code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_parent_ref']->renderError() ?>
          <?php echo $form['gtu_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_path']->renderError() ?>
          <?php echo $form['gtu_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_from_date_mask']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_from_date_mask']->renderError() ?>
          <?php echo $form['gtu_from_date_mask'] ?>
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
        <th><?php echo $form['gtu_to_date_mask']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_to_date_mask']->renderError() ?>
          <?php echo $form['gtu_to_date_mask'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_to_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_to_date']->renderError() ?>
          <?php echo $form['gtu_to_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_tag_values_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_tag_values_indexed']->renderError() ?>
          <?php echo $form['gtu_tag_values_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_country_tag_value']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_country_tag_value']->renderError() ?>
          <?php echo $form['gtu_country_tag_value'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_ref']->renderError() ?>
          <?php echo $form['taxon_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_name']->renderError() ?>
          <?php echo $form['taxon_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_name_indexed']->renderError() ?>
          <?php echo $form['taxon_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_name_order_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_name_order_by']->renderError() ?>
          <?php echo $form['taxon_name_order_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_level_ref']->renderError() ?>
          <?php echo $form['taxon_level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_level_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_level_name']->renderError() ?>
          <?php echo $form['taxon_level_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_status']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_status']->renderError() ?>
          <?php echo $form['taxon_status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_path']->renderError() ?>
          <?php echo $form['taxon_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_parent_ref']->renderError() ?>
          <?php echo $form['taxon_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['taxon_extinct']->renderLabel() ?></th>
        <td>
          <?php echo $form['taxon_extinct']->renderError() ?>
          <?php echo $form['taxon_extinct'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_ref']->renderError() ?>
          <?php echo $form['litho_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_name']->renderError() ?>
          <?php echo $form['litho_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_name_indexed']->renderError() ?>
          <?php echo $form['litho_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_name_order_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_name_order_by']->renderError() ?>
          <?php echo $form['litho_name_order_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_level_ref']->renderError() ?>
          <?php echo $form['litho_level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_level_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_level_name']->renderError() ?>
          <?php echo $form['litho_level_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_status']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_status']->renderError() ?>
          <?php echo $form['litho_status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_path']->renderError() ?>
          <?php echo $form['litho_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['litho_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['litho_parent_ref']->renderError() ?>
          <?php echo $form['litho_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_ref']->renderError() ?>
          <?php echo $form['chrono_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_name']->renderError() ?>
          <?php echo $form['chrono_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_name_indexed']->renderError() ?>
          <?php echo $form['chrono_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_name_order_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_name_order_by']->renderError() ?>
          <?php echo $form['chrono_name_order_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_level_ref']->renderError() ?>
          <?php echo $form['chrono_level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_level_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_level_name']->renderError() ?>
          <?php echo $form['chrono_level_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_status']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_status']->renderError() ?>
          <?php echo $form['chrono_status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_path']->renderError() ?>
          <?php echo $form['chrono_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['chrono_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['chrono_parent_ref']->renderError() ?>
          <?php echo $form['chrono_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_ref']->renderError() ?>
          <?php echo $form['lithology_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_name']->renderError() ?>
          <?php echo $form['lithology_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_name_indexed']->renderError() ?>
          <?php echo $form['lithology_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_name_order_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_name_order_by']->renderError() ?>
          <?php echo $form['lithology_name_order_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_level_ref']->renderError() ?>
          <?php echo $form['lithology_level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_level_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_level_name']->renderError() ?>
          <?php echo $form['lithology_level_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_status']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_status']->renderError() ?>
          <?php echo $form['lithology_status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_path']->renderError() ?>
          <?php echo $form['lithology_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['lithology_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['lithology_parent_ref']->renderError() ?>
          <?php echo $form['lithology_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_ref']->renderError() ?>
          <?php echo $form['mineral_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_name']->renderError() ?>
          <?php echo $form['mineral_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_name_indexed']->renderError() ?>
          <?php echo $form['mineral_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_name_order_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_name_order_by']->renderError() ?>
          <?php echo $form['mineral_name_order_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_level_ref']->renderError() ?>
          <?php echo $form['mineral_level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_level_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_level_name']->renderError() ?>
          <?php echo $form['mineral_level_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_status']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_status']->renderError() ?>
          <?php echo $form['mineral_status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_path']->renderError() ?>
          <?php echo $form['mineral_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['mineral_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['mineral_parent_ref']->renderError() ?>
          <?php echo $form['mineral_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_ref']->renderError() ?>
          <?php echo $form['host_taxon_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_relationship']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_relationship']->renderError() ?>
          <?php echo $form['host_relationship'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_name']->renderError() ?>
          <?php echo $form['host_taxon_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_name_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_name_indexed']->renderError() ?>
          <?php echo $form['host_taxon_name_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_name_order_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_name_order_by']->renderError() ?>
          <?php echo $form['host_taxon_name_order_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_level_ref']->renderError() ?>
          <?php echo $form['host_taxon_level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_level_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_level_name']->renderError() ?>
          <?php echo $form['host_taxon_level_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_status']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_status']->renderError() ?>
          <?php echo $form['host_taxon_status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_path']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_path']->renderError() ?>
          <?php echo $form['host_taxon_path'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_parent_ref']->renderError() ?>
          <?php echo $form['host_taxon_parent_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['host_taxon_extinct']->renderLabel() ?></th>
        <td>
          <?php echo $form['host_taxon_extinct']->renderError() ?>
          <?php echo $form['host_taxon_extinct'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ig_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['ig_ref']->renderError() ?>
          <?php echo $form['ig_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ig_num']->renderLabel() ?></th>
        <td>
          <?php echo $form['ig_num']->renderError() ?>
          <?php echo $form['ig_num'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ig_num_indexed']->renderLabel() ?></th>
        <td>
          <?php echo $form['ig_num_indexed']->renderError() ?>
          <?php echo $form['ig_num_indexed'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ig_date_mask']->renderLabel() ?></th>
        <td>
          <?php echo $form['ig_date_mask']->renderError() ?>
          <?php echo $form['ig_date_mask'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['ig_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['ig_date']->renderError() ?>
          <?php echo $form['ig_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['acquisition_category']->renderLabel() ?></th>
        <td>
          <?php echo $form['acquisition_category']->renderError() ?>
          <?php echo $form['acquisition_category'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['acquisition_date_mask']->renderLabel() ?></th>
        <td>
          <?php echo $form['acquisition_date_mask']->renderError() ?>
          <?php echo $form['acquisition_date_mask'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['acquisition_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['acquisition_date']->renderError() ?>
          <?php echo $form['acquisition_date'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['specimen_count_min']->renderLabel() ?></th>
        <td>
          <?php echo $form['specimen_count_min']->renderError() ?>
          <?php echo $form['specimen_count_min'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['specimen_count_max']->renderLabel() ?></th>
        <td>
          <?php echo $form['specimen_count_max']->renderError() ?>
          <?php echo $form['specimen_count_max'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['with_types']->renderLabel() ?></th>
        <td>
          <?php echo $form['with_types']->renderError() ?>
          <?php echo $form['with_types'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>

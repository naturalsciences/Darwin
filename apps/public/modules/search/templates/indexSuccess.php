<h1>Specimen searchs List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Spec ref</th>
      <th>Category</th>
      <th>Collection ref</th>
      <th>Collection type</th>
      <th>Collection code</th>
      <th>Collection name</th>
      <th>Collection institution ref</th>
      <th>Collection institution formated name</th>
      <th>Collection institution formated name ts</th>
      <th>Collection institution formated name indexed</th>
      <th>Collection institution sub type</th>
      <th>Collection main manager ref</th>
      <th>Collection main manager formated name</th>
      <th>Collection main manager formated name ts</th>
      <th>Collection main manager formated name indexed</th>
      <th>Collection parent ref</th>
      <th>Collection path</th>
      <th>Expedition ref</th>
      <th>Expedition name</th>
      <th>Expedition name ts</th>
      <th>Expedition name indexed</th>
      <th>Station visible</th>
      <th>Gtu ref</th>
      <th>Gtu code</th>
      <th>Gtu parent ref</th>
      <th>Gtu path</th>
      <th>Gtu from date mask</th>
      <th>Gtu from date</th>
      <th>Gtu to date mask</th>
      <th>Gtu to date</th>
      <th>Gtu tag values indexed</th>
      <th>Gtu country tag value</th>
      <th>Taxon ref</th>
      <th>Taxon name</th>
      <th>Taxon name indexed</th>
      <th>Taxon name order by</th>
      <th>Taxon level ref</th>
      <th>Taxon level name</th>
      <th>Taxon status</th>
      <th>Taxon path</th>
      <th>Taxon parent ref</th>
      <th>Taxon extinct</th>
      <th>Litho ref</th>
      <th>Litho name</th>
      <th>Litho name indexed</th>
      <th>Litho name order by</th>
      <th>Litho level ref</th>
      <th>Litho level name</th>
      <th>Litho status</th>
      <th>Litho path</th>
      <th>Litho parent ref</th>
      <th>Chrono ref</th>
      <th>Chrono name</th>
      <th>Chrono name indexed</th>
      <th>Chrono name order by</th>
      <th>Chrono level ref</th>
      <th>Chrono level name</th>
      <th>Chrono status</th>
      <th>Chrono path</th>
      <th>Chrono parent ref</th>
      <th>Lithology ref</th>
      <th>Lithology name</th>
      <th>Lithology name indexed</th>
      <th>Lithology name order by</th>
      <th>Lithology level ref</th>
      <th>Lithology level name</th>
      <th>Lithology status</th>
      <th>Lithology path</th>
      <th>Lithology parent ref</th>
      <th>Mineral ref</th>
      <th>Mineral name</th>
      <th>Mineral name indexed</th>
      <th>Mineral name order by</th>
      <th>Mineral level ref</th>
      <th>Mineral level name</th>
      <th>Mineral status</th>
      <th>Mineral path</th>
      <th>Mineral parent ref</th>
      <th>Host taxon ref</th>
      <th>Host relationship</th>
      <th>Host taxon name</th>
      <th>Host taxon name indexed</th>
      <th>Host taxon name order by</th>
      <th>Host taxon level ref</th>
      <th>Host taxon level name</th>
      <th>Host taxon status</th>
      <th>Host taxon path</th>
      <th>Host taxon parent ref</th>
      <th>Host taxon extinct</th>
      <th>Ig ref</th>
      <th>Ig num</th>
      <th>Ig num indexed</th>
      <th>Ig date mask</th>
      <th>Ig date</th>
      <th>Acquisition category</th>
      <th>Acquisition date mask</th>
      <th>Acquisition date</th>
      <th>Specimen count min</th>
      <th>Specimen count max</th>
      <th>With types</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($specimen_searchs as $specimen_search): ?>
    <tr>
      <td><a href="<?php echo url_for('search/show?id='.$specimen_search->getId()) ?>"><?php echo $specimen_search->getId() ?></a></td>
      <td><?php echo $specimen_search->getSpecRef() ?></td>
      <td><?php echo $specimen_search->getCategory() ?></td>
      <td><?php echo $specimen_search->getCollectionRef() ?></td>
      <td><?php echo $specimen_search->getCollectionType() ?></td>
      <td><?php echo $specimen_search->getCollectionCode() ?></td>
      <td><?php echo $specimen_search->getCollectionName() ?></td>
      <td><?php echo $specimen_search->getCollectionInstitutionRef() ?></td>
      <td><?php echo $specimen_search->getCollectionInstitutionFormatedName() ?></td>
      <td><?php echo $specimen_search->getCollectionInstitutionFormatedNameTs() ?></td>
      <td><?php echo $specimen_search->getCollectionInstitutionFormatedNameIndexed() ?></td>
      <td><?php echo $specimen_search->getCollectionInstitutionSubType() ?></td>
      <td><?php echo $specimen_search->getCollectionMainManagerRef() ?></td>
      <td><?php echo $specimen_search->getCollectionMainManagerFormatedName() ?></td>
      <td><?php echo $specimen_search->getCollectionMainManagerFormatedNameTs() ?></td>
      <td><?php echo $specimen_search->getCollectionMainManagerFormatedNameIndexed() ?></td>
      <td><?php echo $specimen_search->getCollectionParentRef() ?></td>
      <td><?php echo $specimen_search->getCollectionPath() ?></td>
      <td><?php echo $specimen_search->getExpeditionRef() ?></td>
      <td><?php echo $specimen_search->getExpeditionName() ?></td>
      <td><?php echo $specimen_search->getExpeditionNameTs() ?></td>
      <td><?php echo $specimen_search->getExpeditionNameIndexed() ?></td>
      <td><?php echo $specimen_search->getStationVisible() ?></td>
      <td><?php echo $specimen_search->getGtuRef() ?></td>
      <td><?php echo $specimen_search->getGtuCode() ?></td>
      <td><?php echo $specimen_search->getGtuParentRef() ?></td>
      <td><?php echo $specimen_search->getGtuPath() ?></td>
      <td><?php echo $specimen_search->getGtuFromDateMask() ?></td>
      <td><?php echo $specimen_search->getGtuFromDate() ?></td>
      <td><?php echo $specimen_search->getGtuToDateMask() ?></td>
      <td><?php echo $specimen_search->getGtuToDate() ?></td>
      <td><?php echo $specimen_search->getGtuTagValuesIndexed() ?></td>
      <td><?php echo $specimen_search->getGtuCountryTagValue() ?></td>
      <td><?php echo $specimen_search->getTaxonRef() ?></td>
      <td><?php echo $specimen_search->getTaxonName() ?></td>
      <td><?php echo $specimen_search->getTaxonNameIndexed() ?></td>
      <td><?php echo $specimen_search->getTaxonNameOrderBy() ?></td>
      <td><?php echo $specimen_search->getTaxonLevelRef() ?></td>
      <td><?php echo $specimen_search->getTaxonLevelName() ?></td>
      <td><?php echo $specimen_search->getTaxonStatus() ?></td>
      <td><?php echo $specimen_search->getTaxonPath() ?></td>
      <td><?php echo $specimen_search->getTaxonParentRef() ?></td>
      <td><?php echo $specimen_search->getTaxonExtinct() ?></td>
      <td><?php echo $specimen_search->getLithoRef() ?></td>
      <td><?php echo $specimen_search->getLithoName() ?></td>
      <td><?php echo $specimen_search->getLithoNameIndexed() ?></td>
      <td><?php echo $specimen_search->getLithoNameOrderBy() ?></td>
      <td><?php echo $specimen_search->getLithoLevelRef() ?></td>
      <td><?php echo $specimen_search->getLithoLevelName() ?></td>
      <td><?php echo $specimen_search->getLithoStatus() ?></td>
      <td><?php echo $specimen_search->getLithoPath() ?></td>
      <td><?php echo $specimen_search->getLithoParentRef() ?></td>
      <td><?php echo $specimen_search->getChronoRef() ?></td>
      <td><?php echo $specimen_search->getChronoName() ?></td>
      <td><?php echo $specimen_search->getChronoNameIndexed() ?></td>
      <td><?php echo $specimen_search->getChronoNameOrderBy() ?></td>
      <td><?php echo $specimen_search->getChronoLevelRef() ?></td>
      <td><?php echo $specimen_search->getChronoLevelName() ?></td>
      <td><?php echo $specimen_search->getChronoStatus() ?></td>
      <td><?php echo $specimen_search->getChronoPath() ?></td>
      <td><?php echo $specimen_search->getChronoParentRef() ?></td>
      <td><?php echo $specimen_search->getLithologyRef() ?></td>
      <td><?php echo $specimen_search->getLithologyName() ?></td>
      <td><?php echo $specimen_search->getLithologyNameIndexed() ?></td>
      <td><?php echo $specimen_search->getLithologyNameOrderBy() ?></td>
      <td><?php echo $specimen_search->getLithologyLevelRef() ?></td>
      <td><?php echo $specimen_search->getLithologyLevelName() ?></td>
      <td><?php echo $specimen_search->getLithologyStatus() ?></td>
      <td><?php echo $specimen_search->getLithologyPath() ?></td>
      <td><?php echo $specimen_search->getLithologyParentRef() ?></td>
      <td><?php echo $specimen_search->getMineralRef() ?></td>
      <td><?php echo $specimen_search->getMineralName() ?></td>
      <td><?php echo $specimen_search->getMineralNameIndexed() ?></td>
      <td><?php echo $specimen_search->getMineralNameOrderBy() ?></td>
      <td><?php echo $specimen_search->getMineralLevelRef() ?></td>
      <td><?php echo $specimen_search->getMineralLevelName() ?></td>
      <td><?php echo $specimen_search->getMineralStatus() ?></td>
      <td><?php echo $specimen_search->getMineralPath() ?></td>
      <td><?php echo $specimen_search->getMineralParentRef() ?></td>
      <td><?php echo $specimen_search->getHostTaxonRef() ?></td>
      <td><?php echo $specimen_search->getHostRelationship() ?></td>
      <td><?php echo $specimen_search->getHostTaxonName() ?></td>
      <td><?php echo $specimen_search->getHostTaxonNameIndexed() ?></td>
      <td><?php echo $specimen_search->getHostTaxonNameOrderBy() ?></td>
      <td><?php echo $specimen_search->getHostTaxonLevelRef() ?></td>
      <td><?php echo $specimen_search->getHostTaxonLevelName() ?></td>
      <td><?php echo $specimen_search->getHostTaxonStatus() ?></td>
      <td><?php echo $specimen_search->getHostTaxonPath() ?></td>
      <td><?php echo $specimen_search->getHostTaxonParentRef() ?></td>
      <td><?php echo $specimen_search->getHostTaxonExtinct() ?></td>
      <td><?php echo $specimen_search->getIgRef() ?></td>
      <td><?php echo $specimen_search->getIgNum() ?></td>
      <td><?php echo $specimen_search->getIgNumIndexed() ?></td>
      <td><?php echo $specimen_search->getIgDateMask() ?></td>
      <td><?php echo $specimen_search->getIgDate() ?></td>
      <td><?php echo $specimen_search->getAcquisitionCategory() ?></td>
      <td><?php echo $specimen_search->getAcquisitionDateMask() ?></td>
      <td><?php echo $specimen_search->getAcquisitionDate() ?></td>
      <td><?php echo $specimen_search->getSpecimenCountMin() ?></td>
      <td><?php echo $specimen_search->getSpecimenCountMax() ?></td>
      <td><?php echo $specimen_search->getWithTypes() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('search/new') ?>">New</a>

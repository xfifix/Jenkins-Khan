<?php /** @var Jenkins $jenkins */ ?>
<?php /** @var array   $group_runs */ ?>
<?php /** @var int     $current_group_run_id */ ?>
<?php /** @var string  $sort_type */ ?>
<?php /** @var string  $sort_direction */ ?>
<?php /** @var array   $sort_menu */ ?>
<?php /** @var string  $current_url */ ?>

<div id="dashboard">

  <?php if ($jenkins->isAvailable()): ?>
  <ul>
    <li>
      <div class="alert alert-success"><?php echo link_to('Jenkins', $jenkins->getUrl(), array('title' => 'Open Jenkins', 'class' => 'jenkins')) ?> is running.</div>
    </li>
  </ul>
  <?php endif; ?>
  <div class="sidebar">
    <ul>
      <li class="sidebar-actions">
        <div class="btn-group">
          <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
            <?php echo ($sort_type == 'none' || null === $sort_type) ? 'Sort By' : 'Sorted By ' . ucwords($sort_menu[$sort_type]['label']) ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu dropdown-left">
            <?php foreach ($sort_menu as $sorter): ?>
              <li><?php echo link_to($sorter['label'], $sorter['url']) ?></li>
            <?php endforeach; ?>
          </ul>
          <a class="close" href="<?php echo $current_url; ?>" title="Cancel sorting">&times;</a>
          <div class="buttons-radio" data-toggle="buttons-radio">
            <button class="btn btn-primary <?php echo ($sort_direction == 'desc') ? 'active' : ''; ?>" value="desc">Desc</button>
            <button class="btn btn-primary <?php echo ($sort_direction != 'desc') ? 'active' : ''; ?>" value="asc">Asc</button>
          </div>
        </div>
      </li>
      <li>
        <?php if ($jenkins->isAvailable()): ?>
          <?php echo link_to('Create a build branch', 'jenkins/createGroupRun', array('class' => 'add-run')); ?>
        <?php else: ?>
          <a href="#" class="add-run disabled">Create a build branch</a>
        <?php endif; ?>
      </li>
      <?php foreach ($group_runs as $id => $group_run): ?>
        <li>
          <?php $branchName = get_partial('buildStatus', array('status' => $group_run['result'], 'label' => $group_run['label'])); ?>
          <?php echo link_to($branchName, $group_run['url_view'], array('class' => $id == $current_group_run_id ? 'group-run active' : 'group-run')) ?>
          <?php echo link_to(' ','jenkins/deleteGroupRun?id='.$id, array('class' => 'delete-group-run', 'title' => 'Delete build branch')) ?>
          <?php echo link_to(' ','jenkins/createGroupRun?from_group_run_id='.$id, array('class' => 'duplicate-group-run', 'title' => 'Duplicate build branch')) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  
  <div class="content">
    <?php include_component('jenkins', 'listGroupRun', array('group_run_id' => $current_group_run_id, 'jenkins' => $jenkins)); ?>
  </div>
</div>
<script language="javascript" type="text/javascript">
  $(document).ready(function(){
    $('#dashboard').jenkinsDashboard({
      urlReloadListGroupRun: '<?php echo url_for('jenkins/listGroupRun?group_run_id=' . $current_group_run_id); ?>',
      currentUrl: "<?php echo $current_url; ?>",
      sortType: "<?php echo $sort_type; ?>"
    });
  });
</script>

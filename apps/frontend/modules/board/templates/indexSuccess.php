<?php slot('title', __('Dashboard'));  ?>
<?php include_partial('boardwidget/list') ?>
<div class="board">
	<ul class="board_col">
        <?php include_partial('boardwidget/wlayout',array('widget' => 'savedSearch')) ?>
	</ul>
	<div class="board_spacer">&nbsp;</div>
	<ul class="board_col">
        <?php include_partial('boardwidget/wlayout',array('widget' => 'savedSpecimens')) ?>
    </ul>
</div>
<?php
require_once __DIR__ . '/../required.php';

redirectifnotloggedin();
?>
<div class="btn-group" style="margin-bottom: 10px;">
    <a href="app.php?page=editloc" class="btn btn-success"><i class="fa fa-plus"></i> <?php lang("new location"); ?></a>
</div>
<table id="loctable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th data-priority="0"></th>
            <th data-priority="1"><?php lang('actions'); ?></th>
            <th data-priority="1"><i class="fa fa-map-marker"></i> <?php lang('location'); ?></th>
            <th data-priority="2"><i class="fa fa-barcode"></i> <?php lang('code'); ?></th>
            <th data-priority="3"><i class="fa fa-hashtag"></i> <?php lang('item count'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $locs = $database->select('locations', [
            'locid',
            'locname',
            'loccode'
        ]);
        foreach ($locs as $loc) {
            $itemcount = $database->count('items', ['locid' => $loc['locid']]);
            ?>
            <tr>
                <td></td>
                <td>
                    <a class="btn btn-blue btn-xs" href="app.php?page=editloc&id=<?php echo $loc['locid']; ?>"><i class="fa fa-pencil-square-o"></i> <?php lang("edit"); ?></a>
                </td>
                <td><?php echo $loc['locname']; ?></td>
                <td><?php echo $loc['loccode']; ?></td>
                <td><?php echo $itemcount; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th><?php lang('actions'); ?></th>
            <th><i class="fa fa-map-marker"></i> <?php lang('location'); ?></th>
            <th><i class="fa fa-barcode"></i> <?php lang('code'); ?></th>
            <th><i class="fa fa-hashtag"></i> <?php lang('item count'); ?></th>
        </tr>
    </tfoot>
</table>
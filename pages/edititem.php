<?php
require_once __DIR__ . '/../required.php';
require_once __DIR__ . "/../lib/login.php";
require_once __DIR__ . "/../lib/userinfo.php";

redirectifnotloggedin();

$itemdata = [
    'name' => '',
    'catid' => '',
    'catname' => '',
    'locid' => '',
    'locname' => '',
    'loccode' => '',
    'code1' => '',
    'code2' => '',
    'text1' => '',
    'text2' => '',
    'text3' => '',
    'qty' => 1,
    'userid' => ''];

$editing = false;

if (!is_empty($VARS['id'])) {
    if ($database->has('items', ['itemid' => $VARS['id']])) {
    $editing = true;
    $itemdata = $database->select(
                    'items', [
                '[>]categories' => [
                    'catid' => 'catid'
                ],
                '[>]locations' => [
                    'locid' => 'locid'
                ]
                    ], [
                'name',
                'code1',
                'code2',
                'text1',
                'text2',
                'text3',
                'items.catid',
                'catname',
                'items.locid',
                'locname',
                'loccode',
                'qty',
                'userid'
                    ], [
                'itemid' => $VARS['id']
            ])[0];
    } else {
        // item id is invalid, redirect to a page that won't cause an error when pressing Save
        header('Location: app.php?page=edititem');
    }
}
?>

<form role="form" action="action.php" method="POST">
    <div class="panel panel-blue">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php
                if ($editing) {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang2("editing item", ['item' => "<span id=\"name_title\">" . htmlspecialchars($itemdata['name']) . "</span>"]); ?>
                    <?php
                } else {
                    ?>
                    <i class="fa fa-pencil-square-o"></i> <?php lang("adding item"); ?>
                    <?php
                }
                ?>
            </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="name"><i class="fa fa-cube"></i> Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Foo Bar" required="required" value="<?php echo htmlspecialchars($itemdata['name']); ?>" />
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="cat"><i class="fa fa-archive"></i> Category</label>
                        <input type="text" class="form-control" id="cat" placeholder="Widgets" value="<?php echo htmlspecialchars($itemdata['catname']); ?>" />
                        <input type="hidden" id="realcat" name="cat" value="<?php echo $itemdata['catid']; ?>" required="required" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="loc"><i class="fa fa-map-marker"></i> Location</label>
                        <input type="text" class="form-control" id="loc" placeholder="Over the Hills" value="<?php echo htmlspecialchars($itemdata['locname']); ?>" />
                        <input type="hidden" id="realloc" name="loc" value="<?php echo $itemdata['locid']; ?>" required="required" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="code1"><i class="fa fa-barcode"></i> Code 1</label>
                        <input type="text" class="form-control" id="code1" name="code1" placeholder="123456789" value="<?php echo htmlspecialchars($itemdata['code1']); ?>" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="code2"><i class="fa fa-qrcode"></i> Code 2</label>
                        <input type="text" class="form-control" id="code2" name="code2" placeholder="qwerty123" value="<?php echo htmlspecialchars($itemdata['code2']); ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="qty"><i class="fa fa-hashtag"></i> <?php lang('quantity'); ?></label>
                        <input type="number" class="form-control" id="qty" name="qty" placeholder="1" value="<?php echo $itemdata['qty']; ?>" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label for="assignedto"><i class="fa fa-user"></i> <?php lang('assigned to'); ?></label>
                        <input type="text" class="form-control" id="assignedto" name="assignedto" placeholder="<?php lang('nobody'); ?>" value="<?php
                        if (!is_empty($itemdata['userid']) && uid_exists($itemdata['userid'])) {
                            echo getUserByID($itemdata['userid'])['username'];
                        }
                        ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label for="info1"><i class="fa fa-info"></i> Description</label>
                        <textarea class="form-control" id="info1" name="text1"><?php echo htmlspecialchars($itemdata['text1']); ?></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label for="info2"><i class="fa fa-sticky-note-o"></i> Notes</label>
                        <textarea class="form-control" id="info2" name="text2"><?php echo htmlspecialchars($itemdata['text2']); ?></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label for="info3"><i class="fa fa-comments-o"></i> Comments</label>
                        <textarea class="form-control" id="info3" name="text3"><?php echo htmlspecialchars($itemdata['text3']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="itemid" value="<?php echo htmlspecialchars($VARS['id']); ?>" />
        <input type="hidden" name="action" value="edititem" />
        <input type="hidden" name="source" value="items" />

        <div class="panel-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save</button>
            <?php
            if ($editing) {
                ?>
                <a href="action.php?action=deleteitem&source=items&itemid=<?php echo htmlspecialchars($VARS['id']); ?>" style="margin-top: 8px;" class="btn btn-danger btn-xs pull-right"><i class="fa fa-times"></i> <?php lang('delete'); ?></a>
                <?php
            }
            ?>
        </div>
    </div>
</form>
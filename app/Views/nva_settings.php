<!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php if(session()->getFlashdata('success')):?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif;?>


                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="page-header">
                                NVA batch update
                            </h1>
                        </div>
                    </div>


					<form method="post" action="<?= site_url('nva/settings') ?>" class="form-inline" id="batchUpdateForm">
                        <div class="form-group mb-2 w-100">
                            <label for="date_range" class="mr-2">日期范围：</label>
                            <select name="date_range" id="date_range" class="form-control" required>
                                <option value="">请选择</option>
                                <option value="7">一周前</option>
                                <option value="30">一个月前</option>
                                <option value="90">三个月前</option>
                                <option value="180">半年</option>
                                <option value="365">一年</option>
                                <option value="0">全部</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 w-100">
                            <label for="old_pastor" class="mr-2">原牧者：</label>
                            <select name="old_pastor" id="old_pastor" class="form-control" required>
                                <option value="">请选择</option>
                                <?php foreach($old_pastors as $pastor):?>
                                    <option value="<?=$pastor['id']?>"><?=$pastor['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group mb-2 w-100">
                            <label for="visitor_stage" class="mr-2">访客阶段：</label>
                            <select name="visitor_stage" id="visitor_stage" class="form-control" required>
                                <option value="">请选择</option>
                                <?php foreach($visitor_stage as $key => $stage):?>
                                    <option value="<?=$key?>"><?=$stage?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group mb-2 w-100">
                            <label for="new_pastor" class="mr-2">新牧者：</label>
                            <select name="new_pastor" id="new_pastor" class="form-control" required>
                                <option value="">请选择</option>
                                <?php foreach($new_pastors as $pastor):?>
                                    <option value="<?=$pastor['id']?>"><?=$pastor['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group mb-2 w-100">
                            <input type="hidden" name="action" value="update">
                            <button type="submit" class="btn btn-primary">批量分配</button>
                        </div>
                    </form>
                </div>
                <!-- /.container-fluid -->


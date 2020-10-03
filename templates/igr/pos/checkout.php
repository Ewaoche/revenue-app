<div class="container-fluid">

    <form role="form" action="/pos/checkoutdno/new" method="POST">
        <input type="hidden" name="dnoid" value="<?= $dnoInfo->id ?>" />
        <!-- Small Box (Stat card) -->
        <div class="row">


            <!-- ./col -->
            <div class="col-lg-3 col-12 col-sm-12 col-md-4">
                <div class="box box-light">
                    <div class="box-body row">
                        <div class="col-12 col-md-12 col-sm-12 scrol p-3">
                            <h5><small
                                    class="elevation-1 mb-1">Surname:</small><br /><b><?= $customerInfo->surname ?></b>
                            </h5>
                            <h5><small class="elevation-1 mb-1">Other
                                    Names:</small><br /><b><?= $customerInfo->othernames ?></b></h5>
                            <h5><small class="elevation-1 mb-1">Contact
                                    Address:</small><br /><b><?= $customerInfo->address ?></b></h5>
                            <h5><small class="elevation-1 mb-1">Telephone
                                    Number:</small><br /><b><?= $customerInfo->mobile ?></b></h5>
                            <h5><small class="elevation-1 mb-1">Email
                                    Address:</small><br /><b><?= $customerInfo->email ?></b></h5>

                            <h5><small class="elevation-1 mb-1">Invoice
                                    Items:</small><br /><b><?= $dnoInfo->items_qty ?> Item(s)</b></h5>
                            <h5><small class="elevation-1 mb-1">Invoice
                                    Total:</small><br /><b><?= $Core->ToMoney($dnoInfo->items_total) ?></b></h5>

                            <hr class="bg-danger dandger mt-3 mb-1 p-0" />
                            <h4 class="h6">Past revenue history</h4>

                        </div>

                    </div>
                </div>
            </div>
            <!-- ./col -->


            <div class="col-lg-4 col-12 col-sm-12 col-md-4 alert bg-dark">
                <div class="mt-3">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Demand Notice Information
                                <hr class="mt-2" />
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body row">

                            <div class="col-lg-12 col-12 col-sm-12 col-md-12">
                                <label for="recurrent_year">Is invoice recurrent?</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="set_recurrent_year" />&nbsp;Paid Annually
                                        </div>
                                    </div>
                                    <select class="form-control" name="recurrent_year" id="recurrent_year">
                                        <option value=""> - Select Year -</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 col-12 col-sm-12 col-md-12">
                                <label for="time_bound">Is invoice Time-Bound?</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="set_time_bound" />&nbsp;Pay on/or before
                                        </div>
                                    </div>
                                    <input class="form-control" type="date" name="time_bound" id="time_bound" />
                                </div>
                            </div>

                            <div class="col-lg-12 col-12 col-sm-12 col-md-12">
                                <label for="time_bound">Generate Tax ID (TIM)</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="set_tax_category" disabled />&nbsp;Generate TAX
                                        </div>
                                    </div>
                                    <select class="form-control" disabled name="tax_category" id="tax_category">
                                        <option value=""> - Select Tax Category -</option>
                                        <option value="TIN">State Tax</option>
                                        <option value="JTB">Federal Tax</option>
                                        <option value="TINJTB">Federal & State Tax</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 col-12 col-sm-12 col-md-12">
                                <label for="additional_notes">Additional Notes</label>
                                <div class="form-group mb-3">
                                    <textarea class="form-control h6" name="additional_notes"
                                        id="additional_notes"></textarea>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer mt-3">
                            <button type="submit" class="btn btn-outline-light btn-block">Post DNO Order</button>
                        </div>

                    </div>
                    <!-- /.box -->
                </div>
            </div>



            <!-- ./col -->
            <div class="col-lg-5 col-12 col-sm-12 col-md-4 border-left-1">
                <div class="box box-light">
                    <div class="box-header with-border">
                        <h3 class="box-title h2 mb-2">Checkout Details
                        </h3>
                    </div>
                    <div class="box-body row">
                        <div class="col-12 col-md-12 col-sm-12 border-dark">
                            <table class="table table-striped table-condensed table-responsive" style="width: 100%;">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>CODE</th>
                                        <th>ITEM</th>
                                        <th>COST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($DnoPackages as $package): $Package = $Core->PackageInfo($package); ?>
                                    <tr>
                                        <td scope="row"><?= $package ?></td>
                                        <td><?= $Package->name ?></td>
                                        <td><?= $Core->Monify($Package->price) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <hr class="h2 border-success clearfix" />
                            <h3 class="text-left">Total: <b><?= $Core->ToMoney($dnoInfo->items_total) ?></b></h3>
                        </div>

                    </div>
                </div>
            </div>
            <!-- ./col -->








        </div>

    </form>
</div>
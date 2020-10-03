<div class="container-fluid">

    <form role="form" action="/pos/createdno/new" method="POST">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-lg-6 col-12 col-sm-12 col-md-6 alert alert-primary">
                <div class="mt-3">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">DNO Client Details
                                <hr class="mt-2" />
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body row">
                            <div class="form-group col-12 col-md-12 col-sm-12">
                                <label><b>Enter TAX Number, Phone, Email</b></label>
                                <input type="text" class="form-control xTaxDataSense bg-gray h3" id="xTaxDataSense"
                                    autocomplete="false" name="othernames" class="form-control"
                                    placeholder="Enter TAX Number, Phone, Email">
                                <hr class="mt-2 mb-2" />
                            </div>

                            <div class="form-group col-12 col-md-4 col-sm-12">
                                <label>Title:</label>
                                <select class="form-control" name="title" aria-required="require" required id="title">
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-8 col-sm-12">
                                <label>Surname:</label>
                                <input type="text" name="surname" aria-required="require" required class="form-control"
                                    placeholder="Enter Surname">
                            </div>
                            <div class="form-group col-12 col-md-12 col-sm-12">
                                <label>Other Names:</label>
                                <input type="text" name="othernames" aria-required="require" required
                                    class="form-control" placeholder="Enter Other Names">
                            </div>

                            <div class="form-group col-12 col-md-12 col-sm-12">
                                <label>Contact Address:</label>
                                <textarea type="text" name="address" aria-required="require" required
                                    class="form-control" placeholder="Enter Contact Address"></textarea>
                            </div>

                            <div class="form-group col-12 col-md-5 col-sm-12">
                                <label>Phone Number:</label>
                                <input type="tel" name="mobile" aria-required="require" required class="form-control"
                                    placeholder="Telephone">
                            </div>

                            <div class="form-group col-12 col-md-7 col-sm-12">
                                <label for="exampleInputPassword1">Email Address:</label>
                                <input type="email" class="form-control" name="email" placeholder="Email Address">
                            </div>

                            <div class="col-12 col-md-12 col-sm-12" style="color: #000000;">
                                <ul class="list-group" id="xLivePackageList"></ul>
                            </div>


                            <div class="col-12 col-md-12 col-sm-12">
                                <div class="row clearfix">
                                    <table class="table table-condensed table-borderless h3">
                                        <tbody>
                                            <tr>
                                                <td scope="row" align="left"><b id="xLiveItemCount">0</b> Items</td>
                                                <td align="right">Total: <b id="xLiveItemTotalCost">0.00</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer mt-3">
                            <button type="submit" class="btn btn-outline-light btn-block btn-lg" id="xDemandNoticeButton">Create Demand Notice</button>
                        </div>

                    </div>
                    <!-- /.box -->
                </div>


            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-12 col-sm-12 col-md-6">
                <div class="box box-light">
                    <div class="box-header with-border">
                        <h3 class="box-title">Select Packages
                            <hr class="mt-2" />
                        </h3>
                    </div>
                    <div class="box-body row">
                        <div class="col-12 col-md-12 col-sm-12">
                            <div class="form-group form-group-lg">
                                <input type="search" class="form-control form-control-lg xLiveSearch" id="xLiveSearch"
                                    placeholder="Search" aria-label="Search DNO" width="100%" style="width: 100%">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-sm-12 scrol p-3 border-dark border-1"
                            style="max-height: 400px; overflow-y: scroll;">

                            <ul class="list-group" id="myUL">
                                <?php while($package=mysqli_fetch_object($POSPackages)): ?>
                                <li class="list-group-item volatile">
                                    <a>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input xLivePackage"
                                                    data-price="<?= $package->price ?>"
                                                    data-pricetag="<?= $Core->ToMoney($package->price) ?>"
                                                    data-code="<?= $package->id ?>" data-name="<?= $package->name ?>"
                                                    type="checkbox" name="package[]" id="package"
                                                    value="<?= $package->id ?>">
                                                <b><?= $package->name ?></b><br />
                                                Code: <b><?= $package->id ?></b> | Cost:
                                                <b><?= $Core->ToMoney($package->price) ?></b><br />
                                                MDA: <b class="text-primary"><?= $Core->getMDAname($package->mda) ?></b>
                                            </label>
                                        </div>
                                    </a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>

    </form>
</div>
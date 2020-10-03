<div class="container-fluid">

    <!-- Small Box (Stat card) -->
    <div class="row">
        <div class="col-lg-3 col-6 col-sm-12">
            <!-- small card -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
                    <p>Order Requests</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6 col-sm-12">
            <!-- small card -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Resolved Orders</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6 col-sm-12">
            <!-- small card -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
                    <p>DNO Approvals</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bookmark"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info: See all approvals <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6 col-sm-12">
            <!-- small card -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Disputes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-star"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info: See all disputes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>


    <!-- Small Box (Stat card) -->



    <div class="row">
        <div class="col-lg-12 col-12 col-sm-12 col-md-12">
            <div class="box box-light">
                <div class="box-header with-border">
                    <h3 class="box-title">Pending DNO Orders
                        <hr class="h2 border-success clearfix" />
                    </h3>
                </div>
                <div class="box-body row">
                    <div class="col-12 col-md-12 col-sm-12 scrol p-3">
                        <table class="table table-striped table-inverse table-responsive table-hovered xDataTables"
                            style="width: 100%">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>RUIS</th>
                                    <th>Surname</th>
                                    <th>Other Names</th>
                                    <th>Mobile</th>
                                    <th>Qty</th>
                                    <th>Recurring</th>
                                    <th>Pay before</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php while($pdnos=mysqli_fetch_object($PendingDNOPackages)): ?>
                                <tr class="">
                                    <td scope="row"><?= $pdnos->id ?></td>
                                    <td><?= $pdnos->created ?></td>
                                    <td><?= $pdnos->created ?></td>
                                    <td><?= "{$Core->CustomerInfo($pdnos->customer_id)->surname}"  ?></td>
                                    <td><?= "{$Core->CustomerInfo($pdnos->customer_id)->othernames}"  ?></td>
                                    <td><?= "{$Core->CustomerInfo($pdnos->customer_id)->mobile}"  ?></td>
                                    <td><?= $pdnos->items_qty ?></td>
                                    <td><?= $pdnos->is_recurring ?></td>
                                    <td><?= $pdnos->is_time_bound ?></td>
                                    <td><?= $Core->ToMoney($pdnos->items_total) ?></td>
                                </tr>
                                <?php endwhile; ?>

                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
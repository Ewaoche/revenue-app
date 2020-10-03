        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        
                        <div class="website-logo-inside">
                            <a href="/pos">
                                <div class="logo">
                                    <img class="logo-size" src="<?= $assets ?>login\images\logo-white.png">
                                </div>
                            </a>
                        </div>
                        <h3>Point Of Sale</h3>
                        <p>Issue, manage & verify:<br/>revenues, payments & demand notices</p>
                        <div class="page-links">
                            <a class="active" href="/pos/">POS</a>
                            <a href="/ebsgigr2/dno/">DNO</a>
                            <a href="/ebsgigr2/merchant/">Merchant</a>
                            <a href="/ebsgigr2/admin/">Admin</a>
                            <a href="/ebsgigr2/mda/">MDA</a>
                        </div>
                        <form class="allAjaxForm" action="/ebsgigr2/pos/form/login" method="post" enctype="multipart/form-data">
                            <input class="form-control" type="text" name="username" placeholder="Account I.Ds" pattern="[A-Za-z0-9]+" required>
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Login</button>
                            </div>
                        </form>
                        <div class="other-links text-center">
                            <span><strong>EBSG.IGR</strong> Powered by <strong>Golojan</strong>.</span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

   
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
                        <h3>Administrator Area</h3>
                        <p>Manage all platforms here</p>
                        <div class="page-links">
                            <a href="/ebsgigr2/pos/">POS</a>
                            <a href="/ebsgigr2/dno/">DNO</a>
                            <a href="/ebsgigr2/merchant/">Merchant</a>
                            <a class="active" href="/admin/">Admin</a>
                            <a href="/ebsgigr2/mda/">MDA</a>
                        </div>
                        <form class="allAjaxForm" action="/admin/form/login" method="post" enctype="multipart/form-data">
                            <input class="form-control" type="email" name="username" placeholder="Email Address" required>
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

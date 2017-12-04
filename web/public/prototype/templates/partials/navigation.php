<nav class="navbar navbar-expand-md navbar-dark fixed-top">
    <a class="navbar-brand" href="<?=$config["baseUrl"]?>">
        <?php if($logo): ?>
            <img src="<?=$config["baseUrl"]?><?=$logo?>">
        <?php else: ?>
        <div class="navbar-brand-text">
            YACRS
        </div>
        <?php endif; ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse"
            aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href=#" id="dropdown-sessions"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sessions</a>
                <div class="dropdown-menu" aria-labelledby="dropdown-sessions">
                    <a class="dropdown-item" href="#">Manage Sessions</a>
                    <a class="dropdown-item" href="#">New Session</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Admin</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text d-none d-md-block d-lg-block d-xl-block">
                    David Southgate
                </span>
            </li>
            <li class="nav-item">
                <a href="<?=$config["baseUrl"]?>login/" class="btn btn-light">
                    <i class="fa fa-lock"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
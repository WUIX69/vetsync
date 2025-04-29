<!-- Right Section -->
<div class="right-section">
    <!-- Nav -->
    <div class="rsection-container nav">
        <div class="dark-mode-toggle">
            <span class="material-icons-sharp light active">light_mode</span>
            <span class="material-icons-sharp dark">dark_mode</span>
        </div>
        <div class="profile">
            <div class="info">
                <span>Hey, <b>Admin</b></span>
                <small class="text-muted">Admin</small>
            </div>
            <div class="profile-photo">
                <img class="rounded-circle" width="37" src="<?= asset('img/profiles/user-1.jpg'); ?>" />
            </div>
        </div>
    </div>

    <!-- User Activity Feed -->
    <div class="rsection-container box">
        <h2>Recent Activity</h2>
        <div class="rsection-wrapper">
            <div class="ui feed mt-4" id="userActivityFeed">
                <div class="event">
                    <div class="label">
                        <img src="<?= asset('img/avatars/elliot.jpg'); ?>" class="ui avatar image" />
                    </div>
                    <div class="content">
                        <div class="summary">
                            <a class="user"> Elliot Fu </a>
                            added you as a friend
                            <div class="date">
                                1 Hour Ago
                            </div>
                        </div>
                        <div class="meta">
                            <a class="like">
                                <i class="like icon"></i> 4
                                Likes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="event">
                    <div class="label">
                        <img src="<?= asset('img/avatars/helen.jpg'); ?>" class="ui avatar image" />
                    </div>
                    <div class="content">
                        <div class="summary">
                            <a class="user">Helen Troy</a> added
                            <a>2 new illustrations</a>
                            <div class="date">
                                4 days ago
                            </div>
                        </div>
                        <div class="extra images">
                            <a><img src="<?= asset('img/placeholders/image.png'); ?>" /></a>
                            <a><img src="<?= asset('img/placeholders/image.png'); ?>" /></a>
                        </div>
                        <div class="meta">
                            <a class="like">
                                <i class="like icon"></i> 1
                                Like
                            </a>
                        </div>
                    </div>
                </div>
                <div class="event">
                    <div class="label">
                        <img src="<?= asset('img/avatars/jenny.jpg'); ?>" class="ui avatar image" />
                    </div>
                    <div class="content">
                        <div class="summary">
                            <a class="user"> Jenny Hess </a>
                            added you as a friend
                            <div class="date">
                                2 Days Ago
                            </div>
                        </div>
                        <div class="meta">
                            <a class="like">
                                <i class="like icon"></i> 8
                                Likes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="event">
                    <div class="label">
                        <img src="<?= asset('img/avatars/joe.jpg'); ?>" class="ui avatar image" />
                    </div>
                    <div class="content">
                        <div class="summary">
                            <a class="user">Joe Henderson</a> posted on
                            his page
                            <div class="date">
                                3 days ago
                            </div>
                        </div>
                        <div class="extra text">
                            Ours is a life of constant
                            reruns. We're always circling
                            back to where we'd we started,
                            then starting all over again.
                            Even if we don't run extra laps
                            that day, we surely will come
                            back for more of the same
                            another day soon.
                        </div>
                        <div class="meta">
                            <a class="like">
                                <i class="like icon"></i> 5
                                Likes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="event">
                    <div class="label">
                        <img src="<?= asset('img/avatars/justen.jpg'); ?>" class="ui avatar image" />
                    </div>
                    <div class="content">
                        <div class="summary">
                            <a class="user">Justen Kitsune</a> added
                            <a>2 new photos</a> of you
                            <div class="date">
                                4 days ago
                            </div>
                        </div>
                        <div class="extra images">
                            <a><img src="<?= asset('img/placeholders/image.png'); ?>" /></a>
                            <a><img src="<?= asset('img/placeholders/image.png'); ?>" /></a>
                        </div>
                        <div class="meta">
                            <a class="like">
                                <i class="like icon"></i> 41
                                Likes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
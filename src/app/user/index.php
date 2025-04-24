<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>User Dashboard - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style>
        main section.status {
            padding: 20px 20px 20px;
            background: #031224;
            border-radius: 0 0 30px 30px;
        }

        main section.status .header {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
        }

        main section.status .header h4 {
            color: #f1f3f2;
            font-weight: 500;
            margin: 0;
        }

        main section.status .header h4#big {
            flex: 3;
        }

        main section.status .header h4#small {
            flex: 1;
            padding-left: 90px;
        }

        main section.status .items-list {
            margin: 0;
            padding: 0;
        }

        main section.status .items-list .item {
            background: #e0f2fe;
            width: 100%;
            padding: 20px;
            border-radius: 18px;
        }

        main section.status .items-list .item.item-2 {
            background: #fffbeb;
        }

        main section.status .items-list .item.item-3 {
            background: #bfdbfe;
        }

        main section.status .items-list .item.item-4 {
            background: #031224;
            padding: 0;
            margin: 0;
        }

        main section.status .items-list .item .info {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        main section.status .items-list .item .info h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        main section.status .items-list .item .info p {
            font-size: 12px;
            font-weight: 500;
        }

        main section.status .items-list .item .info>i {
            font-size: 20px;
            padding: 10px;
            background: #031224;
            color: #f1f3f2;
            border-radius: 50%;
        }

        main section.status .items-list .item .progress {
            position: relative;
            height: 10px;
            background: #b7c0cd;
            border-radius: 10px;
        }

        main section.status .items-list .item .progress .bar {
            width: 92%;
            height: 10px;
            background: #031224;
            border-radius: 10px;
        }

        main section.status .items-list .item .progress::before {
            content: "92%";
            position: absolute;
            top: -40px;
            right: 0;
            font-size: 22px;
            font-weight: 600;
        }

        main section.status .items-list .item.item-2 .progress {
            background: #d6d3d1;
        }

        main section.status .items-list .item.item-2 .progress .bar {
            width: 65%;
        }

        main section.status .items-list .item.item-2 .progress::before {
            content: "65%";
        }

        main section.status .items-list .item.item-3 .progress {
            background: #94a3b8;
        }

        main section.status .items-list .item.item-3 .progress .bar {
            width: 80%;
        }

        main section.status .items-list .item.item-3 .progress::before {
            content: "80%";
        }

        main section.overview {
            padding: 20px;
        }

        main section.overview .prog-status,
        main section.overview .popular,
        main section.overview .upcoming {
            background: #fefefe;
            padding: 20px;
            border-radius: 24px;
        }

        main section.overview .prog-status,
        main section.overview .popular,
        main section.overview .upcoming {
            width: 100%;
            height: 100%;
        }

        main section.overview .prog-status .header,
        main section.overview .popular .header,
        main section.overview .upcoming .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        main section.overview .prog-status .header h4,
        main section.overview .popular .header h4,
        main section.overview .upcoming .header h4 {
            font-weight: 600;
        }

        main section.overview .prog-status .header .tabs {
            background: #f3f3f3;
            padding: 4px;
            border-radius: 20px;
            display: flex;
            gap: 5px;
        }

        main section.overview .prog-status .header .tabs a {
            padding: 4px 20px;
            font-size: 12px;
            color: #000;
            border-radius: 20px;
            font-weight: 600;
        }

        main section.overview .prog-status .header .tabs a.active {
            background: #fff;
        }

        main section.overview .prog-status .details {
            display: flex;
            margin-bottom: 30px;
            gap: 20px;
        }

        main section.overview .prog-status .details .item h2 {
            font-size: 30px;
            font-weight: 400;
        }

        main section.overview .prog-status .details .item p {
            font-size: 13px;
            color: #9b9b9b;
        }

        main section.overview .prog-status .details .separator {
            width: 2px;
            height: 70px;
            background: #f3f3f3;
        }

        main section.overview .popular .header,
        main section.overview .upcoming .header {
            margin-bottom: 40px;
        }

        main section.overview .popular .header a,
        main section.overview .upcoming .header a {
            font-size: 12px;
            color: #000;
            font-weight: 600;
            padding: 4px 8px;
            background: #f3f3f3;
            border-radius: 20px;
        }

        main section.overview .popular>img {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        main section.overview .popular .audio {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        main section.overview .popular .audio i {
            font-size: 26px;
            background: #eff6ff;
            padding: 6px;
            border-radius: 50%;
        }

        main section.overview .popular .audio a {
            font-size: 15px;
            font-weight: 600;
            color: #000;
            line-height: 18px;
        }

        main section.overview .popular>p {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 50px;
        }

        main section.overview .popular .listen {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        main section.overview .popular .listen .author {
            padding: 6px 10px;
            background: #f3f3f3;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 10px;
        }

        main section.overview .popular .listen .author img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        main section.overview .popular .listen .author a {
            font-size: 14px;
            color: #000;
        }

        main section.overview .popular .listen .author p {
            font-size: 12px;
            color: #9b9b9b;
        }

        main section.overview .popular .listen button {
            border: none;
            color: #fff;
            background: #031224;
            padding: 8.45px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        main section.overview .popular .listen button i {
            font-size: 16px;
        }

        main section.overview .upcoming .dates {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        main section.overview .upcoming .dates .item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        main section.overview .upcoming .dates .item h5 {
            font-weight: 600;
        }

        main section.overview .upcoming .dates .item a {
            color: #000;
            font-size: 13px;
            padding: 5px 9px;
            border-radius: 50%;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        main section.overview .upcoming .dates .item.active a,
        main section.overview .upcoming .dates .item a:hover {
            color: #fff;
            background: #031224;
        }

        main section.overview .upcoming .events {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        main section.overview .upcoming .events .item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #eff6ff;
            padding: 10px;
            border-radius: 10px;
        }

        main section.overview .upcoming .events .item>i {
            cursor: pointer;
        }

        main section.overview .upcoming .events .item>div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        main section.overview .upcoming .events .item>div i {
            font-size: 30px;
        }

        main section.overview .upcoming .events .item .event-info a {
            font-size: 14px;
            color: #000;
            font-weight: 500;
        }

        main section.overview .upcoming .events .item .event-info p {
            font-size: 13px;
            color: #9b9b9b;
        }
    </style>
</head>

<body>

    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?> <!-- Window Spinner -->
        <?= shared('layouts/top-redirect-btn'); ?> <!-- Top Redirect Button -->
    </div>

    <div class="container-body">
        <!-- Site Header -->
        <?= featured('user/shared/layouts/header'); ?>

        <main class="site-main">
            <section class="status">
                <div class="header">
                    <h4 id="big">Your courses</h4>
                    <h4 id="small">Weekly Activity</h4>
                </div>
                <div class="items-list">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="item item-1">
                                <div class="info">
                                    <div>
                                        <h5>Data Analysis</h5>
                                        <p>- 3 lessons left</p>
                                        <p>- 1 project left</p>
                                    </div>
                                    <i class='bx bx-data'></i>
                                </div>
                                <div class="progress">
                                    <div class="bar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="item item-2">
                                <div class="info">
                                    <div>
                                        <h5>Machine Learn</h5>
                                        <p>- 2 assignments left</p>
                                        <p>- 5 tutorials left</p>
                                    </div>
                                    <i class='bx bx-terminal'></i>
                                </div>
                                <div class="progress">
                                    <div class="bar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="item item-3">
                                <div class="info">
                                    <div>
                                        <h5>Python</h5>
                                        <p>- 4 chapters left</p>
                                        <p>- 8 quizzes left</p>
                                    </div>
                                    <i class='bx bxl-python'></i>
                                </div>
                                <div class="progress">
                                    <div class="bar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="item item-4">
                                <canvas class="activity-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="overview">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="prog-status">
                            <div class="header">
                                <h4>learning Progress</h4>
                                <div class="tabs">
                                    <a href="#" class="active">1Y</a>
                                    <a href="#">6M</a>
                                    <a href="#">3M</a>
                                </div>
                            </div>
                            <div class="details">
                                <div class="item">
                                    <h2>3.45</h2>
                                    <p>Current GPA</p>
                                </div>
                                <div class="separator"></div>
                                <div class="item">
                                    <h2>4.78</h2>
                                    <p>Class Average GPA</p>
                                </div>
                            </div>
                            <canvas class="prog-chart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="popular">
                            <div class="header">
                                <h4>Popular</h4>
                                <a href="#"># Data</a>
                            </div>
                            <img src="<?= asset('img/placeholders/image.png'); ?>">
                            <div class="audio">
                                <i class='bx bx-podcast'></i>
                                <a href="#">Podcast: Mastering Data Visualization</a>
                            </div>
                            <p>Learn to create compelling visualizations with data.</p>
                            <div class="listen">
                                <div class="author">
                                    <img src="<?= asset('img/profiles/profile.jpg'); ?>">
                                    <div>
                                        <a href="#">Alex Costa</a>
                                        <p>Data Analyst</p>
                                    </div>
                                </div>
                                <button>Listen<i class='bx bx-right-arrow-alt'></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="upcoming">

                            <div class="header">
                                <h4>You may like it</h4>
                                <a href="#">July <i class='bx bx-chevron-down'></i></a>
                            </div>

                            <div class="dates">
                                <div class="item">
                                    <h5>Mo</h5>
                                    <a href="#">12</a>
                                </div>
                                <div class="item active">
                                    <h5>Tu</h5>
                                    <a href="#">13</a>
                                </div>
                                <div class="item">
                                    <h5>We</h5>
                                    <a href="#">14</a>
                                </div>
                                <div class="item">
                                    <h5>Th</h5>
                                    <a href="#">15</a>
                                </div>
                                <div class="item">
                                    <h5>Fr</h5>
                                    <a href="#">16</a>
                                </div>
                                <div class="item">
                                    <h5>Sa</h5>
                                    <a href="#">17</a>
                                </div>
                                <div class="item">
                                    <h5>Su</h5>
                                    <a href="#">18</a>
                                </div>
                            </div>

                            <div class="events">
                                <div class="item">
                                    <div>
                                        <i class='bx bx-time'></i>
                                        <div class="event-info">
                                            <a href="#">Data Science</a>
                                            <p>10:00-11:30</p>
                                        </div>
                                    </div>
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                                <div class="item">
                                    <div>
                                        <i class='bx bx-time'></i>
                                        <div class="event-info">
                                            <a href="#">Machine Learning</a>
                                            <p>13:30-15:00</p>
                                        </div>
                                    </div>
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                                <div class="item">
                                    <div>
                                        <i class='bx bx-time'></i>
                                        <div class="event-info">
                                            <a href="#">Beginner Python</a>
                                            <p>11:30-13:00</p>
                                        </div>
                                    </div>
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                                <div class="item">
                                    <div>
                                        <i class='bx bx-time'></i>
                                        <div class="event-info">
                                            <a href="#">Introduction to SQL</a>
                                            <p>10:00-11:30</p>
                                        </div>
                                    </div>
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>


    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>
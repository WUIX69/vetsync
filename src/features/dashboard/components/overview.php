<style>
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
<section class="overview">
    <div class="row">
        <div class="col-lg-6">
            <div class="prog-status">
                <div class="header">
                    <h4>Service Appointments</h4>
                    <div class="tabs">
                        <a href="#" class="active">1Y</a>
                        <a href="#">6M</a>
                        <a href="#">3M</a>
                    </div>
                </div>
                <div class="details">
                    <div class="item">
                        <h2>12</h2>
                        <p>Total Appointments</p>
                    </div>
                    <div class="separator"></div>
                    <div class="item">
                        <h2>3</h2>
                        <p>Upcoming Appointments</p>
                    </div>
                </div>
                <canvas class="prog-chart"></canvas>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="popular">
                <div class="header">
                    <h4>Popular</h4>
                    <a href="#"></a>
                </div>
                <img src="<?= asset('img/contents/services/grooming.jpg'); ?>">
                <div class="audio">
                    <i class='bx bx-podcast'></i>
                    <a href="#">Perfect: Freshly groomed and looking paw-some!</a>
                </div>
                <p>All cleaned up and ready to conquer the world.</p>
                <div class="listen">
                    <div class="author">
                        <img src="<?= asset('img/profiles/user-1.jpg'); ?>">
                        <div>
                            <a href="#">Alex Costa</a>
                            <p>Data Analyst</p>
                        </div>
                    </div>
                    <button>book<i class='bx bx-right-arrow-alt'></i></button>
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
                                <a href="#">Vaccination</a>
                                <p>10:00-11:30</p>
                            </div>
                        </div>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <div class="item">
                        <div>
                            <i class='bx bx-time'></i>
                            <div class="event-info">
                                <a href="#">Grooming</a>
                                <p>13:30-15:00</p>
                            </div>
                        </div>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <div class="item">
                        <div>
                            <i class='bx bx-time'></i>
                            <div class="event-info">
                                <a href="#">boarding</a>
                                <p>11:30-13:00</p>
                            </div>
                        </div>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <div class="item">
                        <div>
                            <i class='bx bx-time'></i>
                            <div class="event-info">
                                <a href="#">Pet Accessories</a>
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
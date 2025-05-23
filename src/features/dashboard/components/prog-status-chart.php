<style>
    main section.prog-status-chart .header .tabs {
        background: #f3f3f3;
        padding: 4px;
        border-radius: 20px;
        display: flex;
        gap: 5px;
    }

    main section.prog-status-chart .header .tabs a {
        padding: 4px 20px;
        font-size: 12px;
        color: #000;
        border-radius: 20px;
        font-weight: 600;
    }

    main section.prog-status-chart .header .tabs a.active {
        background: #fff;
    }

    main section.prog-status-chart .details {
        display: flex;
        margin-bottom: 30px;
        gap: 20px;
    }

    main section.prog-status-chart .details .item h2 {
        font-size: 30px;
        font-weight: 400;
    }

    main section.prog-status-chart .details .item p {
        font-size: 13px;
        color: #9b9b9b;
    }

    main section.prog-status-chart .details .separator {
        width: 2px;
        height: 70px;
        background: #f3f3f3;
    }
</style>
<section class="prog-status-chart">
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
</section>
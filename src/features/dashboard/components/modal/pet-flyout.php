<style>
    .pet-flyout .pet-flyout-tabs {
        display: flex;
        gap: 1rem;
        flex-direction: row;
        margin-bottom: 18px;
        font-size: 22px;
        font-family: Arial, sans-serif;
        font-weight: 400;
    }

    .pet-flyout .pet-flyout-tab {
        cursor: pointer;
        color: #222;
        text-shadow: none;
        transition: text-shadow 0.2s, font-weight 0.2s;
    }

    .pet-flyout .pet-flyout-tab.active {
        font-weight: bold;
        text-shadow: 2px 2px 2px #888;
    }

    .pet-flyout .pet-flyout-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .pet-flyout .pet-flyout-panel {
        width: 100%;
    }

    .pet-flyout .rate-us-btn {
        background: #1976d2;
        color: #fff;
        border: none;
        border-radius: 22px;
        padding: 10px 32px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: background 0.2s;
    }

    .pet-flyout .rate-us-btn:hover {
        background: #1256a3;
    }

    .pet-flyout .pet-profile-row {
        display: flex;
        align-items: center;
        gap: 32px;
    }

    .pet-flyout .pet-profile-img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        background: #fff;

        display: flex !important;
        justify-content: center !important;
        align-items: center;
    }

    .pet-flyout .pet-profile-info {
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #222;
    }

    .pet-flyout .pet-profile-info .pet-profile-label {
        color: #888;
        font-size: 1.05rem;
        font-weight: 500;
    }

    .pet-flyout .pet-profile-info .pet-profile-name {
        font-size: 1.4rem;
        font-weight: 700;
        margin-left: 6px;
    }

    .pet-flyout .pet-profile-info .pet-profile-value {
        font-size: 1.1rem;
        font-weight: 600;
        margin-left: 6px;
    }

    .pet-flyout .pet-profile-info .pet-profile-row {
        display: flex;
        gap: 18px;
        margin-bottom: 8px;
    }

    .pet-flyout .pet-profile-info .pet-profile-section {
        margin-bottom: 8px;
    }

    .pet-flyout .pet-profile-info .pet-profile-section.breed {
        margin-bottom: 14px;
    }

    .pet-flyout #rateUsflyout textarea {
        resize: vertical;
    }

    .pet-flyout .rate-stars {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-bottom: 16px;
    }

    .pet-flyout .rate-star {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

    .pet-flyout .rate-star.selected,
    .pet-flyout .rate-star.hovered {
        color: #f7b731;
    }
</style>
<!-- Pet Details flyout (refactored to match category-flyout.php) -->
<div class="ui wide flyout pet-flyout" id="petFlyout">
    <i class="close icon"></i>
    <div class="header">
        <i class="paw icon"></i> Pet Details
    </div>
    <div class="scrolling content">
        <div class="pet-flyout-body">

            <ul class="nav nav-tabs pet-flyout-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="pet-flyout-tab nav-link active" id="profile-tab" data-bs-toggle="tab"
                        data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                        aria-selected="true">Profile</button>
                </li>
                <li class="nav-item " role="presentation">
                    <button class="pet-flyout-tab nav-link" id="service-tab" data-bs-toggle="tab"
                        data-bs-target="#service" type="button" role="tab" aria-controls="service"
                        aria-selected="false">Service</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- Profile Tab -->
                    <div class="pet-flyout-panel">
                        <div class="pet-profile-row">
                            <img src="https://placehold.co/110x110?text=Pet" alt="Pet"
                                class="pet-profile-img rounded-circle ui image">
                            <div class="pet-profile-info">
                                <div class="pet-profile-section">
                                    <label>Name:</label>
                                    <span class="pet-profile-name"></span>
                                </div>
                                <div class="pet-profile-section">
                                    <label>Species:</label>
                                    <span class="pet-profile-species pet-profile-value"></span>
                                </div>
                                <div class="pet-profile-section breed">
                                    <label>Breed:</label>
                                    <span class="pet-profile-breed pet-profile-value"></span>
                                </div>
                                <div class="pet-profile-section">
                                    <label>Date of Birth:</label>
                                    <span class="pet-profile-dob pet-profile-value"></span>
                                </div>
                                <div class="pet-profile-section">
                                    <label>Created:</label>
                                    <span class="pet-profile-created_at pet-profile-value"></span>
                                </div>
                            </div>
                        </div>
                        <div class="actions" style="margin-top: 20px;">
                            <button id="updatePetBtn" class="ui green button" type="button" style="margin-right:10px;">
                                <i class="edit icon"></i> Update
                            </button>
                            <button id="deletePetBtn" class="ui red button" type="button">
                                <i class="trash alternate outline icon"></i> Delete
                            </button>
                        </div>
                        <div class="archive-actions" style="margin-top: 10px;">
                            <button class="ui orange button" id="archivePetBtn" style="display: none;">
                                <i class="archive icon"></i> Archive Pet
                            </button>
                            <button class="ui red button" id="deceasedPetBtn">
                                <i class="heart broken icon"></i> Mark as Deceased
                            </button>
                            <button class="ui green button" id="unarchivePetBtn" style="display: none;">
                                <i class="undo icon"></i> Restore Pet
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">
                    <!-- Service Tab -->
                    <div class="pet-flyout-panel">
                        <div style="font-size:17px; margin-top:30px;">
                            <p>Pet services and appointment history will be shown here.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="actions" style="display:flex; justify-content:center; margin-top:8px;">
        <button id="rateUsBtn" class="ui positive right labeled icon rate-us-btn" style="display:block;">
            Rate Us
            <i class="star icon"></i>
        </button>
    </div>
</div>
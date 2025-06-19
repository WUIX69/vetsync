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

    /* New styles for pet avatars */
    main section.prog-status-chart .pet-avatars {
        display: flex;
        gap: 40px;
        margin: 30px 0 40px 0;
        align-items: flex-start;
    }

    .pet-avatar {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 120px;
    }

    .pet-avatar .avatar-img,
    .pet-avatar .add-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #fff;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #bbb;
        border: 2px solid #e0e0e0;
        transition: box-shadow 0.2s;
    }

    .pet-avatar .add-avatar {
        background: #f3f3f3;
        cursor: pointer;
        font-size: 48px;
        color: #888;
    }

    .pet-avatar .avatar-name {
        font-size: 15px;
        color: #222;
        text-align: right;
    }

    .pet-modal {
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pet-modal-content {
        background: #ddd;
        padding: 32px 24px 24px 24px;
        border-radius: 8px;
        min-width: 340px;
        min-height: 320px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .pet-modal-close {
        position: absolute;
        right: 16px;
        top: 12px;
        font-size: 28px;
        color: #888;
        cursor: pointer;
    }

    .pet-modal-tabs {
        display: flex;
        gap: 32px;
        margin-bottom: 18px;
        font-size: 22px;
        font-family: Arial, sans-serif;
        font-weight: 400;
    }

    .pet-modal-tab {
        cursor: pointer;
        color: #222;
        text-shadow: none;
        transition: text-shadow 0.2s, font-weight 0.2s;
    }

    .pet-modal-tab.active {
        font-weight: bold;
        text-shadow: 2px 2px 2px #888;
    }

    .pet-modal-body {
        flex: 1;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .pet-modal-panel {
        width: 100%;
    }

    .rate-us-btn {
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

    .rate-us-btn:hover {
        background: #1256a3;
    }

    /* Force Semantic UI modal to be centered */
    .ui.modal {
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        margin: 0 !important;
    }

    .pet-profile-row {
        display: flex;
        align-items: center;
        gap: 32px;
    }

    .pet-profile-img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        background: #fff;
    }

    .pet-profile-info {
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #222;
    }

    .pet-profile-info .pet-profile-label {
        color: #888;
        font-size: 1.05rem;
        font-weight: 500;
    }

    .pet-profile-info .pet-profile-name {
        font-size: 1.4rem;
        font-weight: 700;
        margin-left: 6px;
    }

    .pet-profile-info .pet-profile-breed {
        font-size: 1.1rem;
        font-weight: 600;
        margin-left: 6px;
    }

    .pet-profile-info .pet-profile-row {
        display: flex;
        gap: 18px;
        margin-bottom: 8px;
    }

    .pet-profile-info .pet-profile-section {
        margin-bottom: 8px;
    }

    .pet-profile-info .pet-profile-section.breed {
        margin-bottom: 14px;
    }

    #rateUsModal textarea {
        resize: vertical;
    }

    .rate-stars {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-bottom: 16px;
    }

    .rate-star {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rate-star.selected,
    .rate-star.hovered {
        color: #f7b731;
    }
</style>
<section class="prog-status-chart">
    <div class="header">
        <h4>My pets</h4>
        <!-- <div class="tabs">
            <a href="#" class="active">1Y</a>
            <a href="#">6M</a>
            <a href="#">3M</a>
        </div> -->
    </div>
    <div class="pet-avatars">
        <div class="pet-avatar" data-name="Daniel" data-breed="West Highland White Terrier" data-age="3 years"
            data-notes="Loves to wear ties.">
            <img class="avatar-img" src="/public/img/avatars/chris.jpg" alt="Daniel" />
            <div class="avatar-name">Daniel</div>
        </div>
        <div class="pet-avatar" data-name="Zeus" data-breed="Golden Retriever" data-age="5 years"
            data-notes="Enjoys long walks.">
            <img class="avatar-img" src="/public/img/avatars/zeus.jpg" alt="Zeus" />
            <div class="avatar-name">Zeus</div>
        </div>
        <div class="pet-avatar">
            <div class="add-avatar">+</div>
            <div class="avatar-name">Add Pet</div>
        </div>
    </div>
    <!-- <canvas class="prog-chart"></canvas> -->
</section>

<!-- Pet Details Modal (refactored to match category-modal.php) -->
<div class="ui tiny modal pet-modal" id="petModal">
    <i class="close icon pet-modal-close"></i>
    <div class="header">
        <i class="paw icon"></i> Pet Details
    </div>
    <div class="content">
        <div class="pet-modal-tabs" style="margin-bottom:18px;">
            <span class="pet-modal-tab active" data-tab="profile"><b>Profile</b></span>
            <span class="pet-modal-tab" data-tab="service"><b>Service</b></span>
        </div>
        <div class="pet-modal-body">
            <!-- Profile Tab -->
            <div class="pet-modal-panel" id="profileTab">
                <div class="pet-profile-row">
                    <img id="modalPetImg" src="" alt="Pet" class="pet-profile-img">
                    <div class="pet-profile-info">
                        <div class="pet-profile-section">
                            <span class="pet-profile-label">Name:</span>
                            <span class="pet-profile-name" id="modalPetName"></span>
                        </div>
                        <div class="pet-profile-section breed">
                            <span class="pet-profile-label">Breed:</span>
                            <span class="pet-profile-breed" id="modalPetBreed"></span>
                        </div>
                        <div class="pet-profile-row">
                            <div>
                                <span class="pet-profile-label">Weight:</span>
                                <span style="font-weight:500;" id="modalPetWeight"></span>
                            </div>
                            <div>
                                <span class="pet-profile-label">Height:</span>
                                <span style="font-weight:500;" id="modalPetHeight"></span>
                            </div>
                        </div>
                        <div>
                            <span class="pet-profile-label">Vaccination take:</span>
                            <span style="font-weight:500;" id="modalPetVacc"></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Service Tab -->
            <div class="pet-modal-panel" id="serviceTab" style="display:none;">
                <div style="font-size:17px; margin-top:30px;">
                    Grooming : <span id="modalPetGrooming"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="actions" style="display:flex; justify-content:center; margin-top:8px;">
        <button id="rateUsBtn" class="ui positive right labeled icon rate-us-btn">
            Rate Us
            <i class="star icon"></i>
        </button>
    </div>
</div>

<!-- Add Pet Modal -->
<div class="ui tiny modal add-pet-modal" id="addPetModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="paw icon"></i> Add New Pet
    </div>
    <div class="content">
        <form class="ui form" id="addPetForm">
            <div class="field">
                <label>Pet Name</label>
                <input type="text" name="pet_name" placeholder="Enter pet name" required>
            </div>
            <div class="field">
                <label>Breed</label>
                <input type="text" name="pet_breed" placeholder="Enter breed" required>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Age</label>
                    <input type="text" name="pet_age" placeholder="e.g. 3 years" required>
                </div>
                <div class="field">
                    <label>Notes</label>
                    <input type="text" name="pet_notes" placeholder="e.g. Loves to wear ties.">
                </div>
            </div>
            <div class="field">
                <label>Avatar</label>
                <input type="file" name="pet_avatar" accept="image/*">
            </div>
            <div class="actions" style="margin-top: 18px;">
                <button class="ui black deny clear button" type="reset">
                    Cancel
                </button>
                <button class="ui positive right labeled icon submit button" type="submit">
                    Save
                    <i class="checkmark icon"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Rate Us Modal -->
<div class="ui tiny modal" id="rateUsModal">
    <div class="header">
        <i class="star icon"></i> Rate Us
    </div>
    <div class="content" style="text-align:center;">
        <p>We'd love to hear your feedback!</p>
        <div class="rate-stars" id="rateStars">
            <i class="star icon rate-star" data-value="1"></i>
            <i class="star icon rate-star" data-value="2"></i>
            <i class="star icon rate-star" data-value="3"></i>
            <i class="star icon rate-star" data-value="4"></i>
            <i class="star icon rate-star" data-value="5"></i>
        </div>
        <form class="ui form" id="rateUsForm">
            <div class="field">
                <textarea id="rateUsMessage" rows="3" placeholder="Write your feedback here..."></textarea>
            </div>
            <div class="actions" style="margin-top: 16px;">
                <button type="button" class="ui button" id="rateUsCancelBtn">Cancel</button>
                <button type="submit" class="ui blue button">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Sample data for pets
    const petData = {
        Daniel: {
            img: "/public/img/avatars/chris.jpg",
            name: "Daniel",
            breed: "Bullshit",
            weight: "32kg",
            height: "99cm",
            vacc: "5",
            grooming: "complete"
        },
        Zeus: {
            img: "/public/img/avatars/zeus.jpg",
            name: "Zeus",
            breed: "Golden Retriever",
            weight: "28kg",
            height: "85cm",
            vacc: "3",
            grooming: "pending"
        }
    };

    // Modal logic
    document.querySelectorAll('.pet-avatar .avatar-img').forEach(function (img) {
        img.addEventListener('click', function () {
            var parent = img.closest('.pet-avatar');
            var petName = parent.dataset.name;
            if (petName && petData[petName]) {
                // Fill modal with pet data
                document.getElementById('modalPetImg').src = petData[petName].img;
                document.getElementById('modalPetName').textContent = petData[petName].name;
                document.getElementById('modalPetBreed').textContent = petData[petName].breed;
                document.getElementById('modalPetWeight').textContent = petData[petName].weight;
                document.getElementById('modalPetHeight').textContent = petData[petName].height;
                document.getElementById('modalPetVacc').textContent = petData[petName].vacc;
                document.getElementById('modalPetGrooming').textContent = petData[petName].grooming;

                // Show profile tab by default
                document.querySelector('.pet-modal-tab[data-tab="profile"]').classList.add('active');
                document.querySelector('.pet-modal-tab[data-tab="service"]').classList.remove('active');
                document.getElementById('profileTab').style.display = '';
                document.getElementById('serviceTab').style.display = 'none';

                // Show modal using Semantic UI
                $('#petModal').modal('show');
            }
        });
    });

    // Tab switching
    document.querySelectorAll('.pet-modal-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.pet-modal-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            if (tab.dataset.tab === "profile") {
                document.getElementById('profileTab').style.display = '';
                document.getElementById('serviceTab').style.display = 'none';
            } else {
                document.getElementById('profileTab').style.display = 'none';
                document.getElementById('serviceTab').style.display = '';
            }
        });
    });

    // Close icon uses Semantic UI modal close
    // No need for manual display = 'none' since Semantic UI handles it

    document.getElementById('rateUsBtn').onclick = function () {
        $('#rateUsModal').modal('show');
    };

    // Show Add Pet Modal on Add Pet button click
    document.querySelector('.add-avatar').addEventListener('click', function () {
        $('#addPetModal').modal('show');
    });

    // Optionally, handle form submission here
    document.getElementById('addPetForm').onsubmit = function (e) {
        e.preventDefault();
        // Handle form data, validation, and AJAX here
        $('#addPetModal').modal('hide');
        alert('Pet added! (Implement actual logic)');
    };

    // Handle Rate Us form submission
    document.getElementById('rateUsForm').onsubmit = function (e) {
        e.preventDefault();
        // You can handle the feedback here (e.g., send to server)
        $('#rateUsModal').modal('hide');
        alert('Thank you for your feedback!');
        document.getElementById('rateUsMessage').value = '';
    };

    // Handle Cancel button
    document.getElementById('rateUsCancelBtn').onclick = function () {
        $('#rateUsModal').modal('hide');
    };

    // Star rating logic
    let selectedRating = 0;
    const stars = document.querySelectorAll('#rateStars .rate-star');

    stars.forEach(star => {
        star.addEventListener('mouseenter', function () {
            const val = parseInt(this.getAttribute('data-value'));
            stars.forEach((s, i) => {
                s.classList.toggle('hovered', i < val);
            });
        });
        star.addEventListener('mouseleave', function () {
            stars.forEach((s, i) => {
                s.classList.remove('hovered');
            });
        });
        star.addEventListener('click', function () {
            selectedRating = parseInt(this.getAttribute('data-value'));
            stars.forEach((s, i) => {
                s.classList.toggle('selected', i < selectedRating);
            });
        });
    });

    // Reset stars on modal open/close
    $('#rateUsModal').on('show.bs.modal show', function () {
        selectedRating = 0;
        stars.forEach(s => {
            s.classList.remove('selected', 'hovered');
        });
        document.getElementById('rateUsMessage').value = '';
    });
</script>
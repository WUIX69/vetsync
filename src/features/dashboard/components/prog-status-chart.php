<!-- Modal HTML -->
<div id="petModal" class="pet-modal" style="display:none;">
    <div class="pet-modal-content">
        <span class="pet-modal-close">&times;</span>
        <div class="pet-modal-tabs">
            <span class="pet-modal-tab active" data-tab="profile"><b>Profile</b></span>
            <span class="pet-modal-tab" data-tab="service"><b>Service</b></span>
        </div>
        <div class="pet-modal-body">
            <!-- Profile Tab -->
            <div class="pet-modal-panel" id="profileTab">
                <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10px;">
                    <img id="modalPetImg" src="" alt="Pet"
                        style="width:90px; height:90px; border-radius:50%; object-fit:cover; margin-bottom:10px;">
                </div>
                <div style="font-size:17px; margin-bottom:8px;">
                    Name: <span id="modalPetName"></span><br>
                    Breed: <span id="modalPetBreed"></span>
                </div>
                <div style="font-size:16px;">
                    Weight: <span id="modalPetWeight"></span>
                    &nbsp;&nbsp; Height: <span id="modalPetHeight"></span>
                </div>
                <div style="font-size:16px;">
                    Vaccination take: <span id="modalPetVacc"></span>
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
</div>

<style>
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
</style>
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

                document.getElementById('petModal').style.display = 'flex';
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

    document.querySelector('.pet-modal-close').onclick = function () {
        document.getElementById('petModal').style.display = 'none';
    };
    document.getElementById('petModal').onclick = function (e) {
        if (e.target === this) this.style.display = 'none';
    };
</script>
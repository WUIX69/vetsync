<style>
    main section.new-users .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    main section.new-users .user {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    main section.new-users .user img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: var(--img-border) !important;
    }

    main section.new-users .user label {
        font-weight: 600;
    }

    main section.new-users .loading {
        text-align: center;
        padding: 20px;
    }

    main section.new-users .error {
        text-align: center;
        padding: 20px;
        color: #999;
    }
</style>
<section class="new-users">
    <h2 class="title">New Users</h2>
    <div class="container box" id="newUsersContainer">
        <div class="loading">
            <div class="ui active inline loader"></div>
            <p>Loading recent users...</p>
        </div>
    </div>
</section>

<script>
    // Wait for jQuery to be available, then load users
    function initNewUsers() {
        console.log("🔍 Checking for jQuery...");

        if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined') {
            console.log("✅ jQuery found, loading users...");
            loadNewUsers();
        } else {
            console.log("⏳ jQuery not ready, retrying in 200ms...");
            setTimeout(initNewUsers, 200);
        }
    }

    function loadNewUsers() {
        console.log("📡 Loading new users...");

        // Use direct relative path from current page
        var apiUrl = "../../features/dashboard/api/recent-users-admin.php";
        console.log("🌐 API URL:", apiUrl);

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function (response) {
                console.log('✅ API Response:', response);

                if (response && response.success && response.data && response.data.length > 0) {
                    renderNewUsers(response.data);
                } else {
                    console.log("ℹ️ No users found or empty response");
                    document.getElementById('newUsersContainer').innerHTML = `
                    <div class="error">
                        <i class="users icon"></i>
                        <p>No recent users found</p>
                    </div>
                `;
                }
            },
            error: function (xhr, status, error) {
                console.error('❌ API Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    httpStatus: xhr.status,
                    url: apiUrl
                });

                document.getElementById('newUsersContainer').innerHTML = `
                <div class="error">
                    <i class="exclamation triangle icon"></i>
                    <p>Error loading users</p>
                    <small>Status: ${xhr.status} - ${error}</small>
                </div>
            `;
            }
        });
    }

    function renderNewUsers(users) {
        console.log("🎨 Rendering", users.length, "users with data:", users);

        var html = '';

        users.forEach(function (user) {
            // Use the avatar URL from API, fallback to generated one
            var avatarUrl = user.avatar_url;

            // If no avatar URL, create a nice one with initials
            if (!avatarUrl || avatarUrl.includes('user-')) {
                var initials = user.full_name.split(' ').map(n => n[0]).join('').substr(0, 2);
                avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.full_name)}&size=90&background=random&color=fff&font-size=0.6`;
            }

            html += `
            <div class="user">
                <img src="${avatarUrl}" 
                     alt="${user.full_name} avatar"
                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(user.full_name)}&size=90&background=6c757d&color=fff&font-size=0.6'" />
                <label class="mt-2 name">${user.full_name}</label>
                <small class="text-muted time-ago">${user.time_ago}</small>
            </div>
        `;
        });

        document.getElementById('newUsersContainer').innerHTML = html;
        console.log("✅ Users rendered successfully");
    }

    // Start the initialization when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNewUsers);
    } else {
        initNewUsers();
    }

    // Export for global access
    window.loadNewUsers = loadNewUsers;
    window.initNewUsers = initNewUsers;
</script>
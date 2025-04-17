<!-- Add/Edit User Modal -->
<div class="ui tiny modal" id="userModal">
    <div class="header">Add New User</div>
    <div class="content">
        <form class="ui form" id="userForm">
            <div class="field">
                <label>Name</label>
                <input type="text" name="name" placeholder="Full Name" />
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" />
            </div>
            <div class="field">
                <label>Role</label>
                <div class="ui fluid floating selection search dropdown">
                    <input type="hidden" name="role" />
                    <div class="default text">Select role</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item" data-value="admin">Admin</div>
                        <div class="item" data-value="user">User</div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <button class="ui cancel button" type="button">Cancel</button>
                <button class="ui positive button" type="submit" id="userFormSubmitBtn">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
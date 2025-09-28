<!-- Add/Edit User Modal -->
<div class="ui tiny modal" id="userModal">
    <div class="header" data-default-header="Add New User">Add New User</div>
    <div class="content">
        <form class="ui form" id="userForm">
            <input type="hidden" name="uuid" />
            <div class="two fields">
                <div class="field">
                    <label>First Name</label>
                    <input type="text" name="firstname" placeholder="First Name" />
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <input type="text" name="lastname" placeholder="Last Name" />
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" />
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" />
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Telephone</label>
                    <input type="text" name="telephone" placeholder="Telephone" />
                </div>
                <div class="field">
                    <label>Role</label>
                    <div class="ui fluid selection dropdown">
                        <input type="hidden" name="role" />
                        <div class="default text">Select role</div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" data-value="user">User</div>
                            <div class="item" data-value="admin">Admin</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <button class="ui cancel button" type="button">Cancel</button>
                <button class="ui positive submit button" type="submit" id="userFormSubmitBtn">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
<div class="ui tiny modal category-form-modal" id="productCategoryModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="plus circle icon"></i> Add New Product Category
    </div>
    <div class="content">
        <form class="ui form">
            <div class="field">
                <label>Category Name</label>
                <input type="text" name="name" placeholder="Enter category name">
            </div>
            <div class="field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Enter category description"></textarea>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Icon</label>
                    <div class="ui selection dropdown">
                        <input type="hidden" name="icon">
                        <i class="dropdown icon"></i>
                        <div class="default text">Select Icon</div>
                        <div class="menu">
                            <div class="item" data-value="stethoscope"><i class="stethoscope icon"></i>Stethoscope</div>
                            <div class="item" data-value="syringe"><i class="syringe icon"></i>Syringe</div>
                            <div class="item" data-value="cut"><i class="cut icon"></i>Scalpel</div>
                            <div class="item" data-value="paw"><i class="paw icon"></i>Paw</div>
                            <div class="item" data-value="tooth"><i class="tooth icon"></i>Tooth</div>
                            <div class="item" data-value="x-ray"><i class="x-ray icon"></i>X-Ray</div>
                            <div class="item" data-value="shower"><i class="shower icon"></i>Shower</div>
                            <div class="item" data-value="medkit"><i class="medkit icon"></i>Medkit</div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Status</label>
                    <div class="ui selection dropdown">
                        <input type="hidden" name="status">
                        <i class="dropdown icon"></i>
                        <div class="default text">Select Status</div>
                        <div class="menu">
                            <div class="item" data-value="active"><i class="check circle green icon"></i>Active</div>
                            <div class="item" data-value="inactive"><i class="times circle red icon"></i>Inactive</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui black deny button">
                    Cancel
                </div>
                <div class="ui positive right labeled icon button">
                    Save
                    <i class="checkmark icon"></i>
                </div>
            </div>
        </form>
    </div>
</div>
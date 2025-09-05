<?php
// Remove the pets import since we don't need it anymore
?>

<div class="ui tiny modal reservation-modal" id="reservationModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="calendar check icon"></i> Make Product Reservation
    </div>
    <div class="content">
        <form class="ui form" id="reservationForm">
            <div class="field">
                <label>Selected Products</label>
                <div class="products-list" id="selectedProducts"></div>
            </div>

            <div class="two fields">
                <div class="field required">
                    <label>Preferred Date <span style="color: red;">*</span></label>
                    <input type="date" name="preferred_date" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="field required">
                    <label>Preferred Time <span style="color: red;">*</span></label>
                    <input type="time" name="preferred_time" required value="09:00">
                </div>
            </div>

            <div class="field">
                <label>Pickup Method</label>
                <div class="ui selection dropdown disabled">
                    <input type="hidden" name="delivery_method" value="pickup">
                    <i class="dropdown icon"></i>
                    <div class="text">
                        <i class="warehouse icon"></i>Clinic Pickup
                    </div>
                    <div class="menu">
                        <div class="item active selected" data-value="pickup">
                            <i class="warehouse icon"></i>Clinic Pickup
                        </div>
                    </div>
                </div>
            </div>

            <div class="field">
                <label>Special Instructions</label>
                <textarea name="notes" rows="3" placeholder="Any special instructions..."></textarea>
            </div>

            <div class="ui segment">
                <h5>Reservation Summary</h5>
                <div>Total Items: <span id="totalItems">0</span></div>
                <div>Total Amount: <span id="totalAmount">₱0.00</span></div>
            </div>

            <div class="actions">
                <button class="ui black deny button" type="button">Cancel</button>
                <button class="ui positive right labeled icon submit button" type="submit" id="confirmReservationBtn">
                    Confirm Reservation <i class="checkmark icon"></i>
                </button>
            </div>
        </form>
    </div>
</div>
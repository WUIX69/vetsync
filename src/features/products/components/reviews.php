<?php
// Get product data from global scope
$product = $GLOBALS['product'] ?? null;
if (!$product) {
    return;
}

$reference_uuid = $product['uuid'];
$reference_model = 'products';
$item_name = $product['name'];

// Get current user data from session
global $session;
$current_user = null;
$is_logged_in = false;

if ($session && $session->has()) {
    $current_user = $session->get();
    $is_logged_in = true;
}
?>

<style>
    /* Reviews Section Styles */
    section.reviews {
        background: var(--color-background);
    }

    section.reviews .header {
        font-size: 2rem;
        font-weight: 800;
        text-align: left;
        border-bottom: 1px solid #eee !important;
        margin-bottom: 2rem;
        padding-bottom: 1.6rem;
    }

    section.reviews .reviews-count {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: 1.5rem;
    }

    section.reviews .review-list {
        position: relative;
    }

    /* Review Item */
    section.reviews .review-list .review-item {
        border-bottom: 1px solid var(--bs-border-color) !important;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: start;
        justify-content: center;
    }

    section.reviews .review-list .review-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    section.reviews .review-list .review-item .reviewer-img img {
        width: 86px;
        height: 86px;
        border: 1px solid var(--color-dark-variant) !important;
        object-fit: cover;
        object-position: center;
    }

    section.reviews .review-list .review-item .reviewer-name {
        display: flex;
        align-items: center;
        justify-content: start;
    }

    section.reviews .review-list .review-item .reviewer-name h5 {
        font-weight: 600;
        color: var(--color-dark);
    }

    section.reviews .review-list .review-item .reviewer-name .review-date {
        font-size: 0.83rem;
        flex: 1;
    }

    section.reviews .review-list .review-item .reviewer-name .rating {
        font-size: 0.83rem;
    }

    section.reviews .review-list .review-item .review-content p {
        font-size: 0.95rem;
        color: var(--color-dark);
    }

    /* Add Review Form */
    section.reviews .add-review {
        position: relative;
        background: var(--color-background-variant);
        border-radius: 0.5rem;
        padding: 1.6rem;
        height: 100%;
    }

    section.reviews .add-review .form-header p {
        font-size: 0.95rem;
        color: var(--color-dark);
    }

    section.reviews .add-review h4 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--color-dark);
    }

    section.reviews .add-review form label {
        font-size: 0.86rem !important;
        font-weight: 600;
        color: var(--color-dark);
    }

    section.reviews .add-review form .field label[for="rating"],
    section.reviews .add-review form .field .ui.rating {
        font-size: 1rem !important;
    }

    .reviews-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .average-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--color-dark);
    }

    .rating-stars {
        display: flex;
        gap: 2px;
    }

    .rating-stars .star {
        color: #ffc107;
        font-size: 1rem;
    }

    .rating-stars .star.empty {
        color: #e4e5e9;
    }

    .empty-reviews {
        text-align: center;
        padding: 3rem 0;
        color: #6c757d;
    }

    .review-form-login {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .review-form-login a {
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
    }

    .review-form-login a:hover {
        text-decoration: underline;
    }

    .review-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .review-filters .filter-btn {
        border-radius: 20px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .review-filters .filter-btn.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .review-filters .filter-btn:hover {
        background-color: #f8f9fa;
    }

    .review-filters .filter-btn.active:hover {
        background-color: #0056b3;
    }
</style>

<!-- Product Reviews Section -->
<section class="reviews py-5" id="reviews-section">
    <div class="container-xl">
        <div class="header">
            Product Reviews
        </div>

        <div class="row">
            <!-- Left Column - Reviews List -->
            <div class="col-md-7">
                <!-- Reviews Stats -->
                <div class="reviews-stats" id="reviews-stats">
                    <span class="average-rating">
                        <span id="average-rating">0.0</span>
                        <div class="rating-stars" id="average-stars"></div>
                    </span>
                    <span id="total-reviews">0 reviews</span>
                </div>

                <!-- Rating Filters -->
                <div class="review-filters mb-3">
                    <button class="btn btn-outline-primary btn-sm filter-btn active" data-rating="all">
                        All Reviews
                    </button>
                    <button class="btn btn-outline-primary btn-sm filter-btn" data-rating="5">
                        5 ⭐
                    </button>
                    <button class="btn btn-outline-primary btn-sm filter-btn" data-rating="4">
                        4 ⭐
                    </button>
                    <button class="btn btn-outline-primary btn-sm filter-btn" data-rating="3">
                        3 ⭐
                    </button>
                    <button class="btn btn-outline-primary btn-sm filter-btn" data-rating="2">
                        2 ⭐
                    </button>
                    <button class="btn btn-outline-primary btn-sm filter-btn" data-rating="1">
                        1 ⭐
                    </button>
                </div>

                <!-- Review List -->
                <div class="review-list" id="reviews-list">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading reviews...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Review Form -->
            <div class="col-md-5">
                <?php if (!$is_logged_in): ?>
                    <!-- Login Required Notice -->
                    <div class="review-form-login">
                        <h5>Login Required</h5>
                        <p>You need to be logged in to leave a review.</p>
                        <a href="/src/app/auth/" class="btn btn-primary">Login / Register</a>
                    </div>
                <?php else: ?>
                    <!-- Add Review Form -->
                    <div class="add-review" id="review-form">
                        <div class="form-header mb-4">
                            <h4>Add Your Review</h4>
                            <p class="text-muted">Share your experience with this product</p>
                        </div>

                        <form id="submit-review-form" class="ui form">
                            <div class="required field">
                                <label for="rating">YOUR RATING</label>
                                <div class="ui yellow large star rating" data-max-rating="5" id="user-rating"></div>
                                <input type="hidden" name="rating" id="rating-value" required>
                            </div>

                            <div class="required field">
                                <label for="review-text">YOUR REVIEW</label>
                                <textarea name="message" id="review-text" rows="5"
                                    placeholder="Tell us about your experience with this product..." required></textarea>
                            </div>

                            <div class="actions mt-4">
                                <button type="submit" class="ui blue submit button" id="submit-review-btn">
                                    <i class="star icon"></i> Submit Review
                                </button>
                                <button type="reset" class="ui clear basic button">Reset</button>
                            </div>
                        </form>
                    </div>

                    <!-- Messages (hidden by default) -->
                    <div class="review-form-login" id="not-eligible" style="display: none;">
                        <h5>Purchase the Product First</h5>
                        <p>You can only review products you have purchased and picked up.</p>
                    </div>

                    <div class="review-form-login" id="already-reviewed" style="display: none;">
                        <h5>Thank You!</h5>
                        <p>You have already reviewed this product.</p>
                        <button type="button" class="ui button primary" onclick="enableEditMode()">
                            <i class="edit icon"></i>
                            Edit My Review
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Global variables and functions for product reviews
    let productReviewsGlobal = {
        referenceUuid: '<?= $reference_uuid ?>',
        referenceModel: '<?= $reference_model ?>',
        isLoggedIn: <?= $is_logged_in ? 'true' : 'false' ?>,
        currentUserUuid: '<?= $current_user['uuid'] ?? '' ?>',
        allReviews: [],
        currentFilter: 'all'
    };

    // Global function for edit mode (accessible from onclick)
    function enableEditMode() {
        console.log('Enabling edit mode for user product review...');

        // Get the user's existing review
        $.ajax({
            url: '/src/features/reviews/api/reviews.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                action: 'get_user_review',
                user_uuid: productReviewsGlobal.currentUserUuid,
                reference_uuid: productReviewsGlobal.referenceUuid,
                reference_model: productReviewsGlobal.referenceModel
            }),
            success: function (response) {
                console.log('Edit mode - user review data:', response);
                if (response.success && response.data) {
                    const review = response.data;

                    // Show the form
                    $('#review-form').show();
                    $('#already-reviewed').hide();

                    // Pre-fill the form with existing data
                    $('#user-rating').rating('set rating', review.rating);
                    $('#rating-value').val(review.rating);
                    $('#review-text').val(review.message);

                    // Change submit button text and add edit mode attributes
                    $('#submit-review-btn').text('Update Review');
                    $('#submit-review-btn').attr('data-mode', 'edit');
                    $('#submit-review-btn').attr('data-review-id', review.id);

                    // Add a cancel button if it doesn't exist
                    if (!$('#cancel-edit-btn').length) {
                        $('#submit-review-btn').after(`
                        <button type="button" id="cancel-edit-btn" class="ui button" style="margin-left: 10px;">
                            <i class="times icon"></i>
                            Cancel Edit
                        </button>
                    `);
                    }

                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $('#reviews-section').offset().top - 100
                    }, 500);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error getting user review for edit:', error);
            }
        });
    }

    // Wait for jQuery to be available
    (function checkJQuery() {
        if (typeof jQuery !== 'undefined') {
            initializeProductReviews();
        } else {
            setTimeout(checkJQuery, 100);
        }
    })();

    function initializeProductReviews() {
        $(document).ready(function () {
            console.log('Product Reviews script starting...');

            // Move all the existing variables to use productReviewsGlobal
            const referenceUuid = productReviewsGlobal.referenceUuid;
            const referenceModel = productReviewsGlobal.referenceModel;
            const isLoggedIn = productReviewsGlobal.isLoggedIn;
            const currentUserUuid = productReviewsGlobal.currentUserUuid;

            console.log('Reference UUID:', referenceUuid);
            console.log('Reference Model:', referenceModel);
            console.log('Is Logged In:', isLoggedIn);
            console.log('Current User UUID:', currentUserUuid);

            // Store all reviews for filtering
            let allReviews = [];
            let currentFilter = 'all';

            // Check if user came from "Rate Us" button
            const urlParams = new URLSearchParams(window.location.search);
            const shouldShowReview = urlParams.get('review') === 'true';

            console.log('Should show review form:', shouldShowReview);

            // Initialize rating widget
            if ($('#user-rating').length > 0) {
                $('#user-rating').rating({
                    maxRating: 5,
                    onRate: function (value) {
                        $('#rating-value').val(value);
                        console.log('Rating selected:', value);
                    }
                });
                console.log('Rating widget initialized');
            }

            // Load reviews
            loadReviews();

            // Check user eligibility if logged in
            if (isLoggedIn && currentUserUuid) {
                checkUserEligibility();
            }

            // Handle form submission
            $('#submit-review-form').on('submit', function (e) {
                e.preventDefault();
                console.log('Form submitted');
                submitReview();
            });

            // Auto-focus review section if coming from "Rate Us" button
            if (shouldShowReview) {
                console.log('Auto-focusing reviews section...');
                setTimeout(function () {
                    if ($('#reviews-section').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('#reviews-section').offset().top - 100
                        }, 1000);
                        console.log('Scrolled to reviews section');
                    }
                }, 500);
            }

            // Filter button click handler
            $(document).on('click', '.filter-btn', function () {
                const rating = $(this).data('rating');
                filterAndDisplayReviews(rating);
            });

            // Cancel edit button handler
            $(document).on('click', '#cancel-edit-btn', function () {
                console.log('Canceling edit mode for product review');

                // Reset form
                $('#submit-review-form')[0].reset();
                $('#user-rating').rating('set rating', 0);
                $('#rating-value').val('');

                // Reset button state
                $('#submit-review-btn').text('Submit Review').attr('data-mode', '').attr('data-review-id', '');
                $('#cancel-edit-btn').remove();

                // Hide form and show already reviewed message
                $('#review-form').hide();
                $('#already-reviewed').show();
            });

            function loadReviews() {
                console.log('Loading product reviews...');
                $.ajax({
                    url: '/src/features/reviews/api/reviews.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        action: 'get_reviews',
                        reference_uuid: referenceUuid,
                        reference_model: referenceModel
                    }),
                    success: function (response) {
                        console.log('Reviews response:', response);
                        if (response.success && response.data && response.data.length > 0) {
                            allReviews = response.data;
                            updateReviewsStats(response.stats);
                            filterAndDisplayReviews(currentFilter);
                            updateFilterCounts();
                        } else {
                            allReviews = [];
                            $('#reviews-list').html('<div class="empty-reviews"><h5>No Reviews Yet</h5><p>Be the first to review this product!</p></div>');
                            updateReviewsStats({
                                average_rating: 0,
                                total_reviews: 0
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error loading reviews:', error);
                        allReviews = [];
                        $('#reviews-list').html('<div class="empty-reviews"><h5>Error Loading Reviews</h5><p>Please try again later.</p></div>');
                        updateReviewsStats({
                            average_rating: 0,
                            total_reviews: 0
                        });
                    }
                });
            }

            function checkUserEligibility() {
                console.log('Checking user eligibility for product review...');

                $.ajax({
                    url: '/src/features/reviews/api/reviews.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        action: 'check_can_review',
                        user_uuid: currentUserUuid,
                        reference_uuid: referenceUuid,
                        reference_model: referenceModel
                    }),
                    success: function (response) {
                        console.log('Eligibility response:', response);

                        if (response.success && response.data) {
                            if (!response.data.can_review) {
                                const reason = response.data.reason;
                                if (reason === 'already_reviewed') {
                                    $('#review-form').hide();
                                    $('#already-reviewed').show();
                                } else {
                                    $('#review-form').hide();
                                    $('#not-eligible').show();
                                }
                                console.log('User cannot review product:', reason);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error checking eligibility:', error);
                    }
                });
            }

            function submitReview() {
                const rating = $('#rating-value').val();
                const message = $('#review-text').val();
                const isEditMode = $('#submit-review-btn').attr('data-mode') === 'edit';
                const reviewId = $('#submit-review-btn').attr('data-review-id');

                console.log('Submitting product review - Rating:', rating, 'Message:', message, 'Edit mode:', isEditMode);

                if (!rating || !message.trim()) {
                    alert('Please provide both a rating and review message.');
                    return;
                }

                if (!isLoggedIn || !currentUserUuid) {
                    alert('You must be logged in to submit a review.');
                    return;
                }

                const formData = {
                    action: isEditMode ? 'update_review' : 'submit_review',
                    user_uuid: currentUserUuid,
                    rating: parseInt(rating),
                    message: message.trim()
                };

                if (isEditMode) {
                    formData.review_id = reviewId;
                } else {
                    formData.reference_uuid = referenceUuid;
                    formData.reference_model = referenceModel;
                }

                console.log('Submitting product review data:', formData);
                $('#submit-review-btn').addClass('loading').prop('disabled', true).text(isEditMode ? 'Updating...' : 'Submitting...');

                $.ajax({
                    url: '/src/features/reviews/api/reviews.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function (response) {
                        console.log('Submit/Update response:', response);
                        if (response.success) {
                            // Show success message briefly
                            const successMessage = isEditMode ? 'Product review updated successfully!' : 'Product review submitted successfully!';

                            // Reset form
                            $('#submit-review-form')[0].reset();
                            $('#user-rating').rating('set rating', 0);
                            $('#rating-value').val('');

                            // Reset button state
                            $('#submit-review-btn').text('Submit Review').attr('data-mode', '').attr('data-review-id', '');
                            $('#cancel-edit-btn').remove();

                            // Show temporary success message with loading indicator
                            $('#review-form').hide();
                            $('#already-reviewed').show().html(`
                            <div class="ui positive message">
                                <div class="header">Thank You!</div>
                                <p>${successMessage}</p>
                                <p><i class="spinner loading icon"></i> Refreshing page...</p>
                            </div>
                        `);

                            // Auto-refresh after 2 seconds
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        } else {
                            alert(response.message || 'Failed to submit review');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error submitting/updating review:', error);
                        alert('An error occurred while processing your review');
                    },
                    complete: function () {
                        $('#submit-review-btn').removeClass('loading').prop('disabled', false);
                    }
                });
            }

            function filterAndDisplayReviews(rating) {
                let filteredReviews = allReviews;

                if (rating !== 'all') {
                    filteredReviews = allReviews.filter(review => parseInt(review.rating) === parseInt(rating));
                }

                displayReviews(filteredReviews);

                // Update active filter button
                $('.filter-btn').removeClass('active');
                $(`.filter-btn[data-rating="${rating}"]`).addClass('active');

                currentFilter = rating;
            }

            function updateFilterCounts() {
                const counts = {
                    all: allReviews.length,
                    5: allReviews.filter(r => parseInt(r.rating) === 5).length,
                    4: allReviews.filter(r => parseInt(r.rating) === 4).length,
                    3: allReviews.filter(r => parseInt(r.rating) === 3).length,
                    2: allReviews.filter(r => parseInt(r.rating) === 2).length,
                    1: allReviews.filter(r => parseInt(r.rating) === 1).length
                };

                // Update button text with counts
                $('.filter-btn[data-rating="all"]').text(`All Reviews (${counts.all})`);
                $('.filter-btn[data-rating="5"]').text(`5 ⭐ (${counts[5]})`);
                $('.filter-btn[data-rating="4"]').text(`4 ⭐ (${counts[4]})`);
                $('.filter-btn[data-rating="3"]').text(`3 ⭐ (${counts[3]})`);
                $('.filter-btn[data-rating="2"]').text(`2 ⭐ (${counts[2]})`);
                $('.filter-btn[data-rating="1"]').text(`1 ⭐ (${counts[1]})`);

                // Disable buttons with 0 reviews
                $('.filter-btn').each(function () {
                    const rating = $(this).data('rating');
                    const count = counts[rating] || 0;
                    $(this).prop('disabled', count === 0 && rating !== 'all');
                });
            }

            function displayReviews(reviews) {
                if (!reviews || reviews.length === 0) {
                    $('#reviews-list').html('<div class="empty-reviews"><h5>No Reviews Yet</h5><p>Be the first to review this product!</p></div>');
                    return;
                }

                let html = '';
                reviews.forEach(review => {
                    const stars = generateStars(review.rating);

                    html += `
                    <div class="review-item">
                        <div class="reviewer-img">
                            <img src="<?= asset('img/placeholders/image.png') ?>" alt="${review.user_name}" class="rounded-circle">
                        </div>
                        <div class="review-content ms-3">
                            <div class="reviewer-name">
                                <h5 class="mb-0">${review.user_name}</h5>
                                <span class="review-date ms-2 text-muted">- ${review.formatted_date}</span>
                                <div class="rating-stars ms-3">${stars}</div>
                            </div>
                            <p class="mt-2">${review.message}</p>
                        </div>
                    </div>
                `;
                });

                $('#reviews-list').html(html);
            }

            function updateReviewsStats(stats) {
                // Use weighted average if available, otherwise fall back to regular average
                const avgRating = stats ? (stats.weighted_average || stats.average_rating || 0) : 0;
                const originalRating = stats ? (stats.average_rating || 0) : 0;
                const totalReviews = stats ? (stats.total_reviews || 0) : 0;

                $('#average-rating').text(avgRating.toFixed(1));
                $('#average-stars').html(generateStars(avgRating));
                $('#total-reviews').text(`${totalReviews} review${totalReviews !== 1 ? 's' : ''}`);

                // Show transparency note if there's a difference (HIDDEN)
                // if (stats && Math.abs(originalRating - avgRating) > 0.1) {
                //     $('#total-reviews').append(` <small class="text-muted" title="Original: ${originalRating.toFixed(1)}, Adjusted for sentiment">(Quality-adjusted)</small>`);
                // }
            }

            function generateStars(rating) {
                let stars = '';
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 >= 0.5;

                for (let i = 1; i <= 5; i++) {
                    if (i <= fullStars) {
                        stars += '<span class="star">★</span>';
                    } else if (i === fullStars + 1 && hasHalfStar) {
                        stars += '<span class="star">★</span>';
                    } else {
                        stars += '<span class="star empty">☆</span>';
                    }
                }
                return stars;
            }
        });
    }
</script>
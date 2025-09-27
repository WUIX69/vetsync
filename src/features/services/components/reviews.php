<?php
// Get service data from global scope
$service = $GLOBALS['service'] ?? null;
if (!$service) {
    return;
}

$reference_uuid = $service['uuid'];
$reference_model = 'services';
$item_name = $service['name'];

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

    .sentiment-indicator {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .sentiment-positive {
        background-color: #d4edda;
        color: #155724;
    }

    .sentiment-negative {
        background-color: #f8d7da;
        color: #721c24;
    }

    .sentiment-neutral {
        background-color: #fff3cd;
        color: #856404;
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
        position: relative;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
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

    @media (min-width: 768px) {
        .col-md-7 {
            padding-right: 2rem;
        }

        .col-md-5 {
            padding-left: 2rem;
        }
    }

    .ui.positive.message {
        word-wrap: break-word;
        max-width: 100%;
        box-sizing: border-box;
    }
</style>

<!-- Service Reviews Section -->
<section class="reviews py-5" id="reviews-section">
    <div class="container-xl">
        <div class="header">
            Service Reviews
        </div>

        <div class="row">
            <!-- Left Column - Reviews List -->
            <div class="col-md-7 pe-md-4">
                <!-- Reviews Stats -->
                <div class="reviews-stats" id="reviews-stats">
                    <span class="average-rating">
                        <span id="average-rating">0.0</span>
                        <div class="rating-stars" id="average-stars"></div>
                    </span>
                    <span id="total-reviews">0 reviews</span>
                </div>

                <!-- NEW: Add rating filters -->
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
            <div class="col-md-5 ps-md-4">
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
                            <p class="text-muted">Share your experience with this service</p>
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
                                    placeholder="Tell us about your experience..." required></textarea>
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
                        <h5>Complete the Service First</h5>
                        <p>You can only review services you have completed.</p>
                    </div>

                    <div id="already-reviewed" class="ui positive message" style="display: none;">
                        <div class="header">Thank You!</div>
                        <p>You have already reviewed this service.</p>
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
    // Global variables and functions for services reviews
    let serviceReviewsGlobal = {
        referenceUuid: '<?= $reference_uuid ?>',
        referenceModel: '<?= $reference_model ?>',
        isLoggedIn: <?= $is_logged_in ? 'true' : 'false' ?>,
        currentUserUuid: '<?= $current_user['uuid'] ?? '' ?>',
        allReviews: [],
        currentFilter: 'all'
    };

    // Global function for edit mode (accessible from onclick)
    function enableEditMode() {
        console.log('Enabling edit mode for user service review...');

        // Get the user's existing review
        $.ajax({
            url: '/src/features/reviews/api/reviews.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                action: 'get_user_review',
                user_uuid: serviceReviewsGlobal.currentUserUuid,
                reference_uuid: serviceReviewsGlobal.referenceUuid,
                reference_model: serviceReviewsGlobal.referenceModel
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
                    $('#review-text').val(review.message); // Changed from review-message to review-text

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
            initializeServiceReviews();
        } else {
            setTimeout(checkJQuery, 100);
        }
    })();

    function initializeServiceReviews() {
        $(document).ready(function () {
            console.log('Service Reviews script starting...');
            console.log('Reference UUID:', serviceReviewsGlobal.referenceUuid);
            console.log('Reference Model:', serviceReviewsGlobal.referenceModel);
            console.log('Is Logged In:', serviceReviewsGlobal.isLoggedIn);
            console.log('Current User UUID:', serviceReviewsGlobal.currentUserUuid);

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
            if (serviceReviewsGlobal.isLoggedIn && serviceReviewsGlobal.currentUserUuid) {
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
                        $('#review-form').show();
                        $('#already-reviewed').hide();
                        $('html, body').animate({
                            scrollTop: $('#reviews-section').offset().top - 100
                        }, 1000);
                        console.log('Scrolled to reviews section');
                        console.log('Review form forced to show');
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
                console.log('Canceling edit mode for service review');

                // Reset form
                $('#submit-review-form')[0].reset();
                $('#user-rating').rating('clear rating');
                $('#rating-value').val('');

                // Reset button state
                $('#submit-review-btn').text('Submit Review').attr('data-mode', '').attr('data-review-id', '');
                $('#cancel-edit-btn').remove();

                // Hide form and show already reviewed message
                $('#review-form').hide();
                $('#already-reviewed').show();
            });

            // Rest of the functions remain the same inside the ready function...
            function loadReviews() {
                console.log('Loading service reviews...');
                $.ajax({
                    url: '/src/features/reviews/api/reviews.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        action: 'get_reviews',
                        reference_uuid: serviceReviewsGlobal.referenceUuid,
                        reference_model: serviceReviewsGlobal.referenceModel
                    }),
                    success: function (response) {
                        console.log('Reviews response:', response);
                        if (response.success && response.data && response.data.length > 0) {
                            serviceReviewsGlobal.allReviews = response.data;
                            updateReviewsStats(response.stats);
                            filterAndDisplayReviews(serviceReviewsGlobal.currentFilter);
                            updateFilterCounts();
                        } else {
                            serviceReviewsGlobal.allReviews = [];
                            $('#reviews-list').html('<div class="empty-reviews"><h5>No Reviews Yet</h5><p>Be the first to review this service!</p></div>');
                            updateReviewsStats({ average_rating: 0, total_reviews: 0 });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error loading reviews:', error);
                        const rawResponse = xhr.responseText;
                        console.log('Raw response:', rawResponse);
                        serviceReviewsGlobal.allReviews = [];
                        $('#reviews-list').html('<div class="empty-reviews"><h5>Error Loading Reviews</h5><p>Please try again later.</p></div>');
                        updateReviewsStats({ average_rating: 0, total_reviews: 0 });
                    }
                });
            }

            function checkUserEligibility() {
                console.log('Checking user eligibility for service review...');

                $.ajax({
                    url: '/src/features/reviews/api/reviews.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        action: 'check_can_review',
                        user_uuid: serviceReviewsGlobal.currentUserUuid,
                        reference_uuid: serviceReviewsGlobal.referenceUuid,
                        reference_model: serviceReviewsGlobal.referenceModel
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
                                console.log('User cannot review service:', reason);
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
                const message = $('#review-text').val().trim(); // Changed from review-message to review-text
                const isEditMode = $('#submit-review-btn').attr('data-mode') === 'edit';
                const reviewId = $('#submit-review-btn').attr('data-review-id');

                console.log('Submitting service review - Rating:', rating, 'Message:', message, 'Edit mode:', isEditMode);

                if (!rating || rating === 0) {
                    alert('Please select a rating');
                    return;
                }

                if (!message) {
                    alert('Please write a review message');
                    return;
                }

                $('#submit-review-btn').prop('disabled', true).text(isEditMode ? 'Updating...' : 'Submitting...');

                const requestData = {
                    action: isEditMode ? 'update_review' : 'submit_review',
                    user_uuid: serviceReviewsGlobal.currentUserUuid,
                    rating: rating,
                    message: message
                };

                if (isEditMode) {
                    requestData.review_id = reviewId;
                } else {
                    requestData.reference_uuid = serviceReviewsGlobal.referenceUuid;
                    requestData.reference_model = serviceReviewsGlobal.referenceModel;
                }

                $.ajax({
                    url: '/src/features/reviews/api/reviews.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(requestData),
                    success: function (response) {
                        if (response.success) {
                            // Show success message briefly
                            const successMessage = isEditMode ? 'Review updated successfully!' : 'Review submitted successfully!';

                            // Reset form
                            $('#submit-review-form')[0].reset();
                            $('#user-rating').rating('clear rating');
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
                        console.error('Error submitting review:', error);
                        alert('An error occurred while submitting your review');
                    },
                    complete: function () {
                        $('#submit-review-btn').prop('disabled', false);
                    }
                });
            }

            // Add other helper functions here (same as before)...
            function filterAndDisplayReviews(rating) {
                let filteredReviews = serviceReviewsGlobal.allReviews;

                if (rating !== 'all') {
                    filteredReviews = serviceReviewsGlobal.allReviews.filter(review => parseInt(review.rating) === parseInt(rating));
                }

                displayReviews(filteredReviews);

                // Update active filter button
                $('.filter-btn').removeClass('active');
                $(`.filter-btn[data-rating="${rating}"]`).addClass('active');

                serviceReviewsGlobal.currentFilter = rating;
            }

            function updateFilterCounts() {
                const counts = {
                    all: serviceReviewsGlobal.allReviews.length,
                    5: serviceReviewsGlobal.allReviews.filter(r => parseInt(r.rating) === 5).length,
                    4: serviceReviewsGlobal.allReviews.filter(r => parseInt(r.rating) === 4).length,
                    3: serviceReviewsGlobal.allReviews.filter(r => parseInt(r.rating) === 3).length,
                    2: serviceReviewsGlobal.allReviews.filter(r => parseInt(r.rating) === 2).length,
                    1: serviceReviewsGlobal.allReviews.filter(r => parseInt(r.rating) === 1).length
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
                    $('#reviews-list').html('<div class="empty-reviews"><h5>No Reviews Yet</h5><p>Be the first to review this service!</p></div>');
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

                // Show transparency note if there's a difference
                if (stats && Math.abs(originalRating - avgRating) > 0.1) {
                    $('#total-reviews').append(` <small class="text-muted" title="Original: ${originalRating.toFixed(1)}, Adjusted for sentiment">(Quality-adjusted)</small>`);
                }
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
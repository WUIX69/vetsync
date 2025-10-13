<?php
// This component will be included in service and product single views
$reference_uuid = $GLOBALS['service']['uuid'] ?? $GLOBALS['product']['uuid'] ?? '';
$reference_model = isset($GLOBALS['service']) ? 'services' : 'products';
$item_name = $GLOBALS['service']['name'] ?? $GLOBALS['product']['name'] ?? '';

if (!$reference_uuid) {
    return;
}
?>

<style>
    .reviews-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #eee;
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .reviews-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: var(--color-dark);
    }

    .reviews-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
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
        color: #e9ecef;
    }

    .review-form {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .review-form h4 {
        margin-bottom: 1rem;
        color: var(--color-dark);
    }

    .rating-input {
        display: flex;
        gap: 5px;
        margin-bottom: 1rem;
    }

    .rating-input .star-input {
        font-size: 1.5rem;
        color: #e9ecef;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-input .star-input:hover,
    .rating-input .star-input.active {
        color: #ffc107;
    }

    .review-textarea {
        width: 100%;
        min-height: 100px;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        resize: vertical;
        font-family: inherit;
    }

    .review-submit-btn {
        background: var(--color-primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .review-submit-btn:hover {
        background: var(--color-primary-dark);
    }

    .review-submit-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .review-item {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .reviewer-details h5 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--color-dark);
    }

    .review-date {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 0;
    }

    .review-rating {
        display: flex;
        gap: 2px;
    }

    .review-content {
        color: #495057;
        line-height: 1.6;
        margin: 0;
    }

    .sentiment-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sentiment-positive {
        background: #d4edda;
        color: #155724;
    }

    .sentiment-neutral {
        background: #fff3cd;
        color: #856404;
    }

    .sentiment-negative {
        background: #f8d7da;
        color: #721c24;
    }

    .no-reviews {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .login-prompt {
        text-align: center;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        color: #6c757d;
    }

    .login-prompt a {
        color: var(--color-primary);
        text-decoration: none;
        font-weight: 500;
    }

    .login-prompt a:hover {
        text-decoration: underline;
    }

    .eligibility-message {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .eligibility-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .eligibility-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
</style>

<section class="reviews-section" id="reviews-section">
    <div class="reviews-header">
        <div class="reviews-title">
            <h3>Reviews & Ratings</h3>
        </div>
        <div class="reviews-stats">
            <div class="average-rating">
                <span id="average-rating-value">0.0</span>
                <div class="rating-stars" id="average-rating-stars">
                    <!-- Stars will be populated by JavaScript -->
                </div>
            </div>
            <span id="total-reviews-count">(0 reviews)</span>
        </div>
    </div>

    <!-- Review Form (will be shown/hidden based on eligibility) -->
    <div class="review-form" id="review-form" style="display: none;">
        <h4>Share Your Experience</h4>
        <div class="eligibility-message" id="eligibility-message"></div>

        <form id="reviewForm">
            <input type="hidden" id="reference-uuid" value="<?= htmlspecialchars($reference_uuid) ?>">
            <input type="hidden" id="reference-model" value="<?= htmlspecialchars($reference_model) ?>">

            <div class="form-group">
                <label>Your Rating:</label>
                <div class="rating-input" id="rating-input">
                    <span class="star-input" data-rating="1">★</span>
                    <span class="star-input" data-rating="2">★</span>
                    <span class="star-input" data-rating="3">★</span>
                    <span class="star-input" data-rating="4">★</span>
                    <span class="star-input" data-rating="5">★</span>
                </div>
                <input type="hidden" id="rating-value" required>
            </div>

            <div class="form-group">
                <label for="review-message">Your Review:</label>
                <textarea id="review-message" class="review-textarea"
                    placeholder="Tell us about your experience with <?= htmlspecialchars($item_name) ?>..."
                    required></textarea>
            </div>

            <button type="submit" class="review-submit-btn" id="submit-review-btn">
                Submit Review
            </button>
        </form>
    </div>

    <!-- Login Prompt (for non-logged users) -->
    <div class="login-prompt" id="login-prompt" style="display: none;">
        <p>Please <a href="/src/app/auth/">login</a> to leave a review.</p>
    </div>

    <!-- Reviews List -->
    <div class="reviews-list" id="reviews-list">
        <!-- Reviews will be loaded here -->
    </div>

    <div class="no-reviews" id="no-reviews" style="display: none;">
        <p>No reviews yet. Be the first to share your experience!</p>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initializeReviews();
    });

    let userRating = 0;

    function initializeReviews() {
        setupRatingInput();
        checkUserEligibility();
        loadReviews();
        setupReviewForm();
    }

    function setupRatingInput() {
        const stars = document.querySelectorAll('.star-input');

        stars.forEach((star, index) => {
            star.addEventListener('click', function () {
                userRating = parseInt(this.dataset.rating);
                document.getElementById('rating-value').value = userRating;
                updateStarDisplay();
            });

            star.addEventListener('mouseenter', function () {
                const hoverRating = parseInt(this.dataset.rating);
                highlightStars(hoverRating);
            });
        });

        document.getElementById('rating-input').addEventListener('mouseleave', function () {
            updateStarDisplay();
        });
    }

    function highlightStars(rating) {
        const stars = document.querySelectorAll('.star-input');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function updateStarDisplay() {
        highlightStars(userRating);
    }

    function checkUserEligibility() {
        const referenceUuid = document.getElementById('reference-uuid').value;
        const referenceModel = document.getElementById('reference-model').value;

        // Check if user is logged in first
        fetch('/src/features/auth/api/check-session.php')
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.logged_in) {
                    document.getElementById('login-prompt').style.display = 'block';
                    return;
                }

                // Check if user can review this item
                fetch('/src/features/reviews/api/reviews.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'check_can_review',
                        user_uuid: data.user.uuid,
                        reference_uuid: referenceUuid,
                        reference_model: referenceModel
                    })
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success && result.data.can_review) {
                            document.getElementById('review-form').style.display = 'block';
                            showEligibilityMessage('info', 'You can leave a review for this ' + referenceModel.slice(0, -1) + '.');
                        } else {
                            const messages = {
                                'already_reviewed': 'You have already reviewed this item.',
                                'service_not_completed': 'You can only review services after completion.',
                                'product_not_picked_up': 'You can only review products after pickup.'
                            };
                            showEligibilityMessage('warning', messages[result.data.reason] || 'You cannot review this item at the moment.');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking review eligibility:', error);
                    });
            })
            .catch(error => {
                console.error('Error checking session:', error);
                document.getElementById('login-prompt').style.display = 'block';
            });
    }

    function showEligibilityMessage(type, message) {
        const messageEl = document.getElementById('eligibility-message');
        messageEl.className = 'eligibility-message eligibility-' + type;
        messageEl.textContent = message;
        messageEl.style.display = 'block';
    }

    function loadReviews() {
        const referenceUuid = document.getElementById('reference-uuid').value;
        const referenceModel = document.getElementById('reference-model').value;

        fetch('/src/features/reviews/api/reviews.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'get_reviews',
                reference_uuid: referenceUuid,
                reference_model: referenceModel
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayReviews(data.data, data.stats);
                } else {
                    console.error('Failed to load reviews:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading reviews:', error);
            });
    }

    function displayReviews(reviews, stats) {
        // Update stats
        document.getElementById('average-rating-value').textContent = stats.average_rating.toFixed(1);
        document.getElementById('total-reviews-count').textContent = `(${stats.total_reviews} review${stats.total_reviews !== 1 ? 's' : ''})`;

        // Update average rating stars
        const avgStarsContainer = document.getElementById('average-rating-stars');
        avgStarsContainer.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('span');
            star.className = 'star';
            star.textContent = '★';
            if (i <= Math.round(stats.average_rating)) {
                star.classList.add('filled');
            } else {
                star.classList.add('empty');
            }
            avgStarsContainer.appendChild(star);
        }

        // Display reviews
        const reviewsList = document.getElementById('reviews-list');
        const noReviews = document.getElementById('no-reviews');

        if (reviews.length === 0) {
            reviewsList.style.display = 'none';
            noReviews.style.display = 'block';
            return;
        }

        noReviews.style.display = 'none';
        reviewsList.style.display = 'flex';
        reviewsList.innerHTML = '';

        reviews.forEach(review => {
            const reviewElement = createReviewElement(review);
            reviewsList.appendChild(reviewElement);
        });
    }

    function createReviewElement(review) {
        const reviewDiv = document.createElement('div');
        reviewDiv.className = 'review-item';

        const userInitial = (review.user_name || 'Anonymous').charAt(0).toUpperCase();
        const reviewDate = new Date(review.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // ✅ Use user image if available, otherwise show initial
        const userAvatar = review.user_image
            ? `<img src="${review.user_image}" alt="${review.user_name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">`
            : `<div class="reviewer-avatar">${userInitial}</div>`;

        // Create rating stars
        let ratingStars = '';
        for (let i = 1; i <= 5; i++) {
            ratingStars += `<span class="star ${i <= review.rating ? '' : 'empty'}">★</span>`;
        }

        // Sentiment badge
        const sentimentBadge = review.sentiment_label ?
            `<span class="sentiment-badge sentiment-${review.sentiment_label}">${review.sentiment_label}</span>` : '';

        reviewDiv.innerHTML = `
        <div class="review-header">
            <div class="reviewer-info">
                ${userAvatar}
                <div class="reviewer-details">
                    <h5>${review.user_name || 'Anonymous'}</h5>
                    <p class="review-date">${reviewDate}</p>
                </div>
            </div>
            <div class="review-rating">
                ${ratingStars}
                ${sentimentBadge}
            </div>
        </div>
        <p class="review-content">${review.message}</p>
    `;

        return reviewDiv;
    }

    function setupReviewForm() {
        document.getElementById('reviewForm').addEventListener('submit', function (e) {
            e.preventDefault();
            submitReview();
        });
    }

    function submitReview() {
        const submitBtn = document.getElementById('submit-review-btn');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        // Get user session first
        fetch('/src/features/auth/api/check-session.php')
            .then(response => response.json())
            .then(sessionData => {
                if (!sessionData.success || !sessionData.logged_in) {
                    throw new Error('Please login to submit a review');
                }

                const formData = {
                    action: 'submit_review',
                    user_uuid: sessionData.user.uuid,
                    reference_uuid: document.getElementById('reference-uuid').value,
                    reference_model: document.getElementById('reference-model').value,
                    rating: parseInt(document.getElementById('rating-value').value),
                    message: document.getElementById('review-message').value.trim()
                };

                return fetch('/src/features/reviews/api/reviews.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset form
                    document.getElementById('reviewForm').reset();
                    userRating = 0;
                    updateStarDisplay();

                    // Reload reviews
                    loadReviews();

                    // Hide form and show success message
                    document.getElementById('review-form').style.display = 'none';
                    showEligibilityMessage('info', 'Thank you for your review! It has been submitted successfully.');

                    alert('Review submitted successfully!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error submitting review:', error);
                alert('Failed to submit review. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
    }
</script>
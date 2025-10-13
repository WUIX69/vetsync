<?php

namespace VetSync\Models;

use PDO;
use PDOException;
use Exception;

class Reviews
{
    private static $conn;

    private static function conn()
    {
        if (!isset(self::$conn)) {
            global $conn;
            self::$conn = $conn;
        }
        return self::$conn;
    }

    public static function store($data = [])
    {
        try {
            // Validate required fields
            if (
                empty($data['user_uuid']) || empty($data['reference_uuid']) ||
                empty($data['reference_model']) || empty($data['rating']) || empty($data['message'])
            ) {
                throw new Exception('Missing required review data');
            }

            // Perform sentiment analysis
            $sentimentResult = self::analyzeSentiment($data['message']);

            $stmt = self::conn()->prepare("
                INSERT INTO reviews (
                    user_uuid, 
                    reference_uuid, 
                    reference_model, 
                    rating, 
                    message,
                    sentiment_score,
                    sentiment_label,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $data['user_uuid'],
                $data['reference_uuid'],
                $data['reference_model'],
                $data['rating'],
                $data['message'],
                $sentimentResult['score'],
                $sentimentResult['label']
            ]);

            return [
                'success' => true,
                'message' => 'Review submitted successfully! Thank you for your feedback.',
                'sentiment' => $sentimentResult
            ];
        } catch (PDOException $e) {
            error_log("Review submission error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to submit review. Please try again.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function getByReference($reference_uuid, $reference_model)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT 
                    r.*,
                    CONCAT(u.firstname, " ", u.lastname) AS user_name,
                    a.filename AS user_image_filename,
                    a.folder AS user_image_folder,
                    DATE_FORMAT(r.created_at, "%M %d, %Y") AS formatted_date
                FROM reviews r
                LEFT JOIN users u ON r.user_uuid = u.uuid
                LEFT JOIN attachments a ON u.uuid = a.reference_uuid 
                    AND a.reference_model = "profiles"
                WHERE r.reference_uuid = ? AND r.reference_model = ?
                ORDER BY r.created_at DESC
            ');
            $stmt->execute([$reference_uuid, $reference_model]);
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Build full image paths for each review
            foreach ($reviews as &$review) {
                if (!empty($review['user_image_filename']) && !empty($review['user_image_folder'])) {
                    // Build the media path (similar to how media() helper works)
                    $review['user_image'] = '/src/uploads/profiles/' . $review['user_image_folder'] . '/' . $review['user_image_filename'];
                } else {
                    $review['user_image'] = null;
                }
                // Clean up temporary fields
                unset($review['user_image_filename'], $review['user_image_folder']);
            }

            // Calculate average rating and sentiment distribution
            $stats = self::calculateReviewStats($reviews);

            return [
                'success' => true,
                'data' => $reviews,
                'stats' => $stats
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch reviews: ' . $e->getMessage(),
            ];
        }
    }

    public static function checkUserCanReview($user_uuid, $reference_uuid, $reference_model)
    {
        try {
            // Check if user has already reviewed this item
            $stmt = self::conn()->prepare('
                SELECT COUNT(*) as review_count 
                FROM reviews 
                WHERE user_uuid = ? AND reference_uuid = ? AND reference_model = ?
            ');
            $stmt->execute([$user_uuid, $reference_uuid, $reference_model]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['review_count'] > 0) {
                return [
                    'can_review' => false,
                    'reason' => 'already_reviewed'
                ];
            }

            // Check if user has completed service/picked up product
            if ($reference_model === 'services') {
                // For services, check if user has completed appointments for this service
                $stmt = self::conn()->prepare('
                    SELECT COUNT(*) as completed_count
                    FROM appointments 
                    WHERE service_uuid = ? AND user_uuid = ? AND status = "completed"
                ');
                $stmt->execute([$reference_uuid, $user_uuid]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['completed_count'] == 0) {
                    return [
                        'can_review' => false,
                        'reason' => 'service_not_completed'
                    ];
                }
            } elseif ($reference_model === 'products') {
                // For products, check if user has picked up reservations for this product
                // The products JSON uses "product_uuid" not "uuid"
                $stmt = self::conn()->prepare('
                    SELECT COUNT(*) as picked_up_count
                    FROM reservations r
                    WHERE (
                        JSON_CONTAINS(r.products, JSON_OBJECT("product_uuid", ?)) 
                        OR r.products LIKE CONCAT("%\"product_uuid\":\"", ?, "\"%")
                    )
                    AND r.user_uuid = ? AND r.status = "picked_up"
                ');
                $stmt->execute([$reference_uuid, $reference_uuid, $user_uuid]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['picked_up_count'] == 0) {
                    return [
                        'can_review' => false,
                        'reason' => 'product_not_picked_up'
                    ];
                }
            }

            return [
                'can_review' => true,
                'reason' => 'eligible'
            ];
        } catch (PDOException $e) {
            return [
                'can_review' => false,
                'reason' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private static function analyzeSentiment($text)
    {
        // Simple sentiment analysis using keyword matching
        $text = strtolower(trim($text));

        // Define sentiment keywords
        $positive_keywords = [
            'excellent',
            'amazing',
            'wonderful',
            'fantastic',
            'great',
            'good',
            'awesome',
            'outstanding',
            'perfect',
            'love',
            'loved',
            'best',
            'brilliant',
            'superb',
            'magnificent',
            'incredible',
            'phenomenal',
            'terrific',
            'marvelous',
            'satisfied',
            'happy',
            'pleased',
            'delighted',
            'thrilled',
            'recommend'
        ];

        $negative_keywords = [
            'terrible',
            'awful',
            'horrible',
            'bad',
            'worst',
            'hate',
            'hated',
            'disappointing',
            'poor',
            'useless',
            'pathetic',
            'disgusting',
            'annoying',
            'frustrating',
            'unsatisfied',
            'unhappy',
            'angry',
            'mad',
            'furious',
            'regret',
            'waste',
            'money',
            'time',
            'never',
            'again',
            'complaint'
        ];

        $positive_score = 0;
        $negative_score = 0;

        // Count positive keywords
        foreach ($positive_keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $positive_score++;
            }
        }

        // Count negative keywords
        foreach ($negative_keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $negative_score++;
            }
        }

        // Calculate final sentiment
        if ($positive_score > $negative_score) {
            $label = 'positive';
            $score = min(($positive_score - $negative_score) / 10.0, 1.0);
        } elseif ($negative_score > $positive_score) {
            $label = 'negative';
            $score = -min(($negative_score - $positive_score) / 10.0, 1.0);
        } else {
            $label = 'neutral';
            $score = 0.0;
        }

        return [
            'score' => $score,
            'label' => $label,
            'positive_matches' => $positive_score,
            'negative_matches' => $negative_score
        ];
    }

    private static function calculateReviewStats($reviews)
    {
        if (empty($reviews)) {
            return [
                'average_rating' => 0,
                'total_reviews' => 0,
                'rating_distribution' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0],
                'sentiment_distribution' => ['positive' => 0, 'neutral' => 0, 'negative' => 0],
                'weighted_average' => 0
            ];
        }

        $total_rating = 0;
        $weighted_total_rating = 0;
        $rating_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        $sentiment_counts = ['positive' => 0, 'neutral' => 0, 'negative' => 0];

        // MODERATE weighting strategy - RECOMMENDED
        $weights = [
            'negative' => -0.3,  // Reduce by 0.3 stars
            'neutral' => 0,      // No change
            'positive' => 0.2    // Boost by 0.2 stars
        ];

        foreach ($reviews as $review) {
            $rating = (int) $review['rating'];
            $sentiment = $review['sentiment_label'] ?? 'neutral';

            // Regular average calculation
            $total_rating += $rating;

            // Make sure rating is between 1 and 5
            if ($rating >= 1 && $rating <= 5) {
                $rating_counts[$rating]++;
            }

            // Count sentiment labels
            if (isset($sentiment_counts[$sentiment])) {
                $sentiment_counts[$sentiment]++;
            }

            // Calculate weighted rating
            $adjustment = $weights[$sentiment] ?? 0;
            $weighted_rating = max(1, min(5, $rating + $adjustment));
            $weighted_total_rating += $weighted_rating;
        }

        $average_rating = $total_rating / count($reviews);
        $weighted_average_rating = $weighted_total_rating / count($reviews);

        return [
            'average_rating' => round($average_rating, 1),
            'weighted_average' => round($weighted_average_rating, 1),
            'total_reviews' => count($reviews),
            'rating_distribution' => $rating_counts,
            'sentiment_distribution' => $sentiment_counts
        ];
    }

    private static function calculateSentimentWeight($sentiment, $rating)
    {
        // Define different weighting strategies
        $weights = [
            'aggressive' => [
                'negative' => -0.5,
                'neutral' => 0,
                'positive' => 0.3
            ],
            'moderate' => [
                'negative' => -0.3,
                'neutral' => 0,
                'positive' => 0.2
            ],
            'conservative' => [
                'negative' => -0.2,
                'neutral' => 0,
                'positive' => 0.1
            ]
        ];

        // Use moderate weighting by default
        $strategy = 'moderate';
        $adjustment = $weights[$strategy][$sentiment] ?? 0;

        // Apply the adjustment and keep within bounds
        $weighted_rating = $rating + $adjustment;
        return max(1, min(5, $weighted_rating));
    }

    private static function calculateAdvancedWeight($sentiment, $rating, $review_text)
    {
        $base_adjustment = 0;

        switch ($sentiment) {
            case 'negative':
                // Stronger penalty for very negative language
                $harsh_words = ['terrible', 'awful', 'horrible', 'worst', 'hate'];
                $harsh_count = 0;
                foreach ($harsh_words as $word) {
                    if (stripos($review_text, $word) !== false) {
                        $harsh_count++;
                    }
                }
                // More harsh words = bigger penalty
                $base_adjustment = -0.3 - ($harsh_count * 0.1);
                break;

            case 'positive':
                // Bonus for enthusiastic language
                $enthusiasm_words = ['amazing', 'excellent', 'fantastic', 'love', 'perfect'];
                $enthusiasm_count = 0;
                foreach ($enthusiasm_words as $word) {
                    if (stripos($review_text, $word) !== false) {
                        $enthusiasm_count++;
                    }
                }
                $base_adjustment = 0.2 + ($enthusiasm_count * 0.1);
                break;

            default:
                $base_adjustment = 0;
        }

        // Apply adjustment and keep within bounds
        $weighted_rating = $rating + $base_adjustment;
        return max(1, min(5, $weighted_rating));
    }

    public static function updateReview($review_id, $user_uuid, $data = [])
    {
        try {
            // First, verify the review belongs to the user
            $stmt = self::conn()->prepare('SELECT * FROM reviews WHERE id = ? AND user_uuid = ?');
            $stmt->execute([$review_id, $user_uuid]);
            $existing_review = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing_review) {
                return [
                    'success' => false,
                    'message' => 'Review not found or you are not authorized to edit it'
                ];
            }

            // Analyze sentiment for the updated message
            $sentiment_analysis = self::analyzeSentiment($data['message']);

            // Update the review
            $stmt = self::conn()->prepare('
                UPDATE reviews 
                SET rating = ?, message = ?, sentiment_label = ?, sentiment_score = ?, updated_at = NOW()
                WHERE id = ? AND user_uuid = ?
            ');

            $result = $stmt->execute([
                $data['rating'],
                $data['message'],
                $sentiment_analysis['label'],
                $sentiment_analysis['score'],
                $review_id,
                $user_uuid
            ]);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Review updated successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update review'
                ];
            }
        } catch (PDOException $e) {
            error_log("Review update error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error occurred'
            ];
        }
    }

    public static function getUserReview($user_uuid, $reference_uuid, $reference_model)
    {
        try {
            $stmt = self::conn()->prepare('
                SELECT * FROM reviews 
                WHERE user_uuid = ? AND reference_uuid = ? AND reference_model = ?
            ');
            $stmt->execute([$user_uuid, $reference_uuid, $reference_model]);
            $review = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($review) {
                return [
                    'success' => true,
                    'data' => $review
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No review found'
                ];
            }
        } catch (PDOException $e) {
            error_log("Get user review error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error occurred'
            ];
        }
    }
}

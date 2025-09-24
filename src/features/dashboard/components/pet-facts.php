<style>
    main section.pet-facts {
        position: relative;
        height: 100%;
    }

    main section.pet-facts .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    main section.pet-facts .header h4 {
        color: #031224;
        font-weight: 600;
        margin: 0;
    }

    main section.pet-facts .pet-facts-swiper {
        width: 100%;
        height: 350px;
        border-radius: 15px;
        overflow: hidden;
    }

    main section.pet-facts .fact-slide {
        color: white;
        padding: 25px;
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    main section.pet-facts .fact-slide::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
        transform: rotate(45deg);
        opacity: 0.2;
    }

    /* Much softer, muted color schemes for better readability */
    main section.pet-facts .fact-slide.slide-1 {
        background: linear-gradient(135deg, #5a6c7d 0%, #4a5568 100%);
    }

    main section.pet-facts .fact-slide.slide-2 {
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
    }

    main section.pet-facts .fact-slide.slide-3 {
        background: linear-gradient(135deg, #2c5aa0 0%, #2a4a6b 100%);
    }

    main section.pet-facts .fact-slide.slide-4 {
        background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%);
    }

    main section.pet-facts .fact-slide.slide-5 {
        background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    }

    main section.pet-facts .fact-slide.slide-6 {
        background: linear-gradient(135deg, #319795 0%, #2c7a7b 100%);
    }

    main section.pet-facts .fact-slide.slide-7 {
        background: linear-gradient(135deg, #d53f8c 0%, #b83280 100%);
    }

    main section.pet-facts .fact-slide.slide-8 {
        background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);
    }

    main section.pet-facts .fact-slide.slide-9 {
        background: linear-gradient(135deg, #7c3aed 0%, #6b46c1 100%);
    }

    main section.pet-facts .fact-slide.slide-10 {
        background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
    }

    main section.pet-facts .fact-content {
        position: relative;
        z-index: 2;
    }

    main section.pet-facts .fact-icon {
        font-size: 40px;
        margin-bottom: 15px;
        display: block;
        opacity: 0.9;
    }

    main section.pet-facts .fact-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.3;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    main section.pet-facts .fact-description {
        font-size: 14px;
        line-height: 1.6;
        opacity: 0.95;
        margin-bottom: 20px;
        color: #f7fafc;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    main section.pet-facts .fact-category {
        font-size: 12px;
        background: rgba(255, 255, 255, 0.15);
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    main section.pet-facts .swiper-pagination {
        bottom: 15px !important;
    }

    main section.pet-facts .swiper-pagination-bullet {
        background: rgba(255, 255, 255, 0.4) !important;
        opacity: 1 !important;
        width: 8px !important;
        height: 8px !important;
    }

    main section.pet-facts .swiper-pagination-bullet-active {
        background: rgba(255, 255, 255, 0.9) !important;
    }

    /* Loading state */
    main section.pet-facts .loading-slide {
        background: linear-gradient(135deg, #5a6c7d 0%, #4a5568 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        text-align: center;
    }

    main section.pet-facts .loading-slide .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-top: 4px solid rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Auto-play indicator */
    main section.pet-facts .auto-indicator {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.15);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 11px;
        z-index: 3;
        color: white;
        display: flex;
        align-items: center;
        gap: 5px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    main section.pet-facts .auto-indicator i {
        font-size: 12px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }
    }
</style>

<section class="pet-facts">
    <div class="header">
        <h4>🐾 Pet Facts</h4>
        <div class="auto-indicator">
            <i class='bx bx-play-circle'></i>
            Live
        </div>
    </div>

    <div class="swiper pet-facts-swiper">
        <div class="swiper-wrapper" id="pet-facts-wrapper">
            <!-- Loading slide -->
            <div class="swiper-slide">
                <div class="loading-slide">
                    <div class="spinner"></div>
                    <div class="fact-title">Loading Pet Facts...</div>
                    <div class="fact-description">Fetching interesting facts about pets from around the web!</div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let petFactsSwiper;
        let petFacts = [];
        let currentFactIndex = 0;

        // Fallback facts with improved readability
        const fallbackFacts = [
            {
                fact: "Dogs have an amazing sense of smell that is 10,000 to 100,000 times stronger than humans! They have about 300 million scent receptors.",
                type: 'dog',
                icon: '🐕',
                category: 'Dog Science'
            },
            {
                fact: "Cats purr at frequencies between 20-50 Hz, which can help heal bones, reduce pain, and lower blood pressure in both cats and humans.",
                type: 'cat',
                icon: '🐱',
                category: 'Cat Health'
            },
            {
                fact: "Pet owners have lower blood pressure, reduced stress levels, and stronger immune systems compared to people without pets.",
                type: 'general',
                icon: '🐾',
                category: 'Health Benefits'
            },
            {
                fact: "A hamster's heart beats up to 450 times per minute - that's about 7 times faster than a human heart at rest!",
                type: 'small',
                icon: '🐹',
                category: 'Small Animals'
            },
            {
                fact: "Fish can be trained to respond to colors and sounds. Goldfish can actually remember things for months, not just seconds!",
                type: 'fish',
                icon: '🐠',
                category: 'Fish Intelligence'
            },
            {
                fact: "Regular vet checkups can extend your pet's life by 2-3 years by catching health issues early. Prevention is always better than cure!",
                type: 'health',
                icon: '🏥',
                category: 'Veterinary Care'
            },
            {
                fact: "Cats sleep 12-16 hours per day to conserve energy. This behavior comes from their wild ancestors who needed energy for hunting.",
                type: 'cat',
                icon: '😴',
                category: 'Cat Behavior'
            },
            {
                fact: "80% of dogs and cats show signs of dental disease by age 3. Regular dental care can prevent serious health problems.",
                type: 'health',
                icon: '🦷',
                category: 'Dental Health'
            },
            {
                fact: "Birds like African Grey Parrots can learn over 100 words and use them in context. They have the intelligence of a 5-year-old child!",
                type: 'bird',
                icon: '🐦',
                category: 'Bird Intelligence'
            },
            {
                fact: "Rabbits are social animals that live in groups called warrens in the wild. They need companionship to stay happy and healthy.",
                type: 'rabbit',
                icon: '🐰',
                category: 'Rabbit Behavior'
            }
        ];

        // Fetch facts from APIs with fallback
        async function fetchPetFacts() {
            const facts = [];

            try {
                // Try to fetch from Cat Facts API
                const response = await fetch('https://catfact.ninja/fact');
                if (response.ok) {
                    const data = await response.json();
                    facts.push({
                        fact: data.fact,
                        type: 'cat',
                        icon: '🐱',
                        category: 'Cat Facts'
                    });
                }
            } catch (error) {
                console.log('API fetch failed, using fallback facts');
            }

            // Add fallback facts
            const shuffledFallbacks = fallbackFacts.sort(() => 0.5 - Math.random());
            facts.push(...shuffledFallbacks);

            return facts.slice(0, 8); // Return 8 facts
        }

        // Create slide HTML
        function createFactSlide(fact, index) {
            const slideClass = `slide-${(index % 10) + 1}`;
            return `
            <div class="swiper-slide">
                <div class="fact-slide ${slideClass}">
                    <div class="fact-content">
                        <span class="fact-icon">${fact.icon}</span>
                        <div class="fact-title">Did You Know?</div>
                        <div class="fact-description">${fact.fact}</div>
                        <div class="fact-category">${fact.category}</div>
                    </div>
                </div>
            </div>
        `;
        }

        // Initialize swiper with facts
        function initializeSwiper(facts) {
            const wrapper = document.getElementById('pet-facts-wrapper');

            // Clear loading slide and add fact slides
            wrapper.innerHTML = facts.map((fact, index) => createFactSlide(fact, index)).join('');

            // Initialize or update swiper
            if (petFactsSwiper) {
                petFactsSwiper.destroy(true, true);
            }

            petFactsSwiper = new Swiper('.pet-facts-swiper', {
                autoplay: {
                    delay: 6000, // 6 seconds per slide
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                loop: true,
                effect: 'slide',
                speed: 1000,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true
                },
                touchRatio: 1,
                a11y: {
                    prevSlideMessage: 'Previous pet fact',
                    nextSlideMessage: 'Next pet fact'
                }
            });
        }

        // Load and display facts
        async function loadPetFacts() {
            try {
                const facts = await fetchPetFacts();
                petFacts = facts;
                initializeSwiper(facts);

                // Refresh facts every 15 minutes
                setTimeout(loadPetFacts, 15 * 60 * 1000);

            } catch (error) {
                console.error('Failed to load pet facts:', error);
                // Use fallback facts
                initializeSwiper(fallbackFacts);
            }
        }

        // Start loading facts
        loadPetFacts();

        // Pause autoplay when not visible (performance optimization)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (petFactsSwiper) {
                    if (entry.isIntersecting) {
                        petFactsSwiper.autoplay.start();
                    } else {
                        petFactsSwiper.autoplay.stop();
                    }
                }
            });
        }, {
            threshold: 0.3
        });

        observer.observe(document.querySelector('.pet-facts'));
    });
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Change the title to "Bella Vista Oasis Spa" -->
    <title>Bella Vista Oasis Spa</title> 
    
    <!-- This line adds the logo to the browser tab -->
    <link rel="icon" type="image/png" href="logo.png">
    
    <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style.css">
    <!-- Add this line for the 360 Tour -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
</head>
<body>
           
    <nav>
        <div class="logo">
            <a href="index.php" style="text-decoration:none; color:inherit; display:flex; align-items:center;">
                <img src="logo.png" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px;"> 
                Bella Vista Oasis Spa
            </a>
        </div>
                <div class="nav-links">
            <a href="#about">About</a>
            <a href="#services">Services</a>
            <a href="#tour">360° Tour</a>
            <?php session_start(); if(isset($_SESSION['customer_id'])): ?>
                <a href="my_account.php" style="color: #A9744F; font-weight: bold;">My Account</a>
            <?php else: ?>
                <a href="customer_auth.php">Log in</a>
            <?php endif; ?>
            <a href="booking.php" class="btn">Book Appointment</a>
        </div>
    </nav>

        <!-- Replace your old <header> with this -->
    <header class="hero">
        <!-- The Slideshow Images -->
        <div class="hero-slider">
            <div class="slide active" style="background-image: url('hero1.png');"></div>
            <div class="slide" style="background-image: url('hero2.png');"></div>
            <div class="slide" style="background-image: url('hero3.png');"></div>
        </div>

        <!-- The Dark Overlay (so text is readable) -->
        <div class="hero-overlay"></div>

        

        <!-- The Text Content -->
        <div class="hero-content">
            <h1>A quiet sanctuary to<br>restore body and mind</h1>
            <p>Premium massage therapies and private lounge experiences, open daily from 10:00 AM to 11:00 PM.</p>
            <a href="booking.php" class="btn">Book Your Session</a>
            <a href="#services" class="btn btn-outline">View Services</a>
        </div>
    </header>

        <section id="about" class="section grid-2" style="padding: 100px 50px;">
        <div>
            <h4 style="color: #A9744F; letter-spacing: 2px; font-size: 35px; margin-bottom: 10px;">ABOUT US</h4>
            <h2 style="font-size: 25px; margin-bottom: 25px; line-height: 1.2;">Healing, surrounded by nature</h2>
            <p style="font-size: 18px; line-height: 1.8; color: #3B2A20;">
                Bella Vista Oasis Spa is a peaceful sanctuary dedicated to holistic wellness, physical rejuvenation, and deep relaxation, offering premium massage therapies, therapeutic treatments, and private lounge experiences.
            </p>
        </div>
        <div>
            <h4 style="color: #A9744F; letter-spacing: 2px; font-size: 35px; margin-bottom: 10px;">OUR HISTORY</h4>
            <h2 style="font-size: 25px; margin-bottom: 25px; line-height: 1.2;">From estate retreat to wellness center</h2>
            <p style="font-size: 18px; line-height: 1.8; color: #3B2A20;">
                Established in 2021 as an extension of the Bella Vista estate, the spa grew into a premier standalone wellness center trusted by both local residents and tourists seeking professional relaxation therapies.
            </p>
        </div>
    </section>

        <!-- NEW: Explore Experiences Section -->
    <section class="section" style="background: #FBF7F0; padding: 80px 50px;">
        <div style="max-width: 1100px; margin: 0 auto;">
            <h2 style="font-size: 36px; margin-bottom: 40px;">Explore Unparalleled Experiences</h2>

            <div class="experience-grid">
                
                <!-- Card 1 -->
                <div class="experience-card">
                    <img src="card1.png" alt="Relaxation Massages" class="exp-img">
                    <div class="exp-content">
                        <h3>Relaxation Massages</h3>
                        <p>Swedish, Aromatherapy, Foot Reflexology. Melt away stress and restore your peace of mind.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="experience-card">
                    <img src="card2.png" alt="Therapeutic Massages" class="exp-img">
                    <div class="exp-content">
                        <h3>Therapeutic Massages</h3>
                        <p>Deep Tissue, Shiatsu, Hilot. Targeted pressure to release deep-seated tension and pain.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="experience-card">
                    <img src="card3.png" alt="Warm Therapies" class="exp-img">
                    <div class="exp-content">
                        <h3>Warm Therapies</h3>
                        <p>Hot Stone, plus complimentary herbal tea in the Couples Suite. Deep warmth for ultimate relaxation.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

                    <section id="services" class="section" style="background: #F3E9DA; padding: 80px 50px;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <h4 style="color: #A9744F; letter-spacing: 1px;">SERVICES AND RATES</h4>
            <h2 style="font-size: 36px; margin-bottom: 40px;">Choose your massage</h2>

            <div class="accordion-container">
                
                <!-- 1. Swedish -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Swedish</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>Long, flowing strokes and gentle kneading across the full body to ease tension and improve circulation.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱800</span>
                            <span class="price-pill">2 hours · ₱1,500</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Foot Reflexology -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Foot Reflexology</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>Focused pressure on points of the feet and lower legs to relieve tired legs and help you unwind.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱800</span>
                            <span class="price-pill">2 hours · ₱1,500</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Shiatsu -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Shiatsu</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>A Japanese-style technique using rhythmic finger and palm pressure to release tightness and restore balance.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱900</span>
                            <span class="price-pill">2 hours · ₱1,700</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Hilot -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Hilot (Filipino)</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>A traditional Filipino healing massage using warm oil and herbal heat to soothe aching muscles and joints.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱1,000</span>
                            <span class="price-pill">2 hours · ₱1,900</span>
                        </div>
                    </div>
                </div>

                <!-- 5. Aromatherapy -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Aromatherapy</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>Light to medium strokes with essential oils chosen for calm, helping quiet the mind and ease stress.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱1,000</span>
                            <span class="price-pill">2 hours · ₱1,900</span>
                        </div>
                    </div>
                </div>

                <!-- 6. Deep Tissue -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Deep Tissue</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>Slow, firm pressure on the deeper muscle layers to work out knots and long-held tightness.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱1,100</span>
                            <span class="price-pill">2 hours · ₱2,100</span>
                        </div>
                    </div>
                </div>

                <!-- 7. Hot Stone -->
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Hot Stone</span>
                        <span class="accordion-icon">&#9660;</span>
                    </button>
                    <div class="accordion-content">
                        <p>Smooth heated stones placed on and glided over the body to melt tension and deepen relaxation.</p>
                        <div class="price-pills">
                            <span class="price-pill">1 hour · ₱1,300</span>
                            <span class="price-pill">2 hours · ₱2,500</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

            </div>
        </div>
    </section>

            </div>
        </div>
    </section>
            <!-- 360 VIRTUAL TOUR SECTION -->
    <section id="tour" class="section" style="background: #F3E9DA; padding: 80px 50px;">
        <div style="max-width: 1100px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h4 style="color: #A9744F; letter-spacing: 1px;">VIRTUAL TOUR</h4>
                <h2 style="font-size: 36px; margin-bottom: 15px;">Step inside Bella Vista</h2>
                <p style="color: #6F4E37; font-size: 18px;">Click and drag to look around. Use the buttons below to explore different rooms.</p>
            </div>

                       <!-- Tour Buttons -->
            <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                <button class="btn tour-btn active-tour" onclick="switchTour('reception', this)" style="background: #6F4E37; color: #fff !important;">Reception Area</button>
                <button class="btn tour-btn" onclick="switchTour('single', this)" style="background: transparent; border: 1px solid #6F4E37; color: #6F4E37 !important;">Single Room</button>
                <button class="btn tour-btn" onclick="switchTour('couple', this)" style="background: transparent; border: 1px solid #6F4E37; color: #6F4E37 !important;">Couples Suite</button>
                <button class="btn tour-btn" onclick="switchTour('family', this)" style="background: transparent; border: 1px solid #6F4E37; color: #6F4E37 !important;">Family Lounge</button>
            </div>
            
            <!-- The 360 Viewer Container -->
            <div id="panorama" style="width: 100%; height: 500px; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(59, 42, 32, 0.15);"></div>
        </div>
    </section>

    <!-- EXISTING POLICIES SECTION -->
    <section id="policies" class="section">
        <!-- ... your existing policies code ... -->
    <section id="policies" class="section">
        <div class="grid-3">
            <div>
                <h3>Arrival</h3>
                <p>Arrive 15 minutes early. Late arrivals get a shortened session.</p>
            </div>
            <div>
                <h3>Cancellation</h3>
                <p>At least 6 hours notice. Late cancellations forfeit the downpayment.</p>
            </div>
            <div>
                <h3>Health Declaration</h3>
                <p>Disclose conditions, injuries or pregnancy when booking.</p>
            </div>
        </div>
    </section>

        <!-- Add this script at the bottom of index.php -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            // Remove 'active' class from all slides
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            
            // Add 'active' class to the new slide
            slides[index].classList.add('active');
        }

        // Function to change slide (used by the arrows)
        function changeSlide(direction) {
            currentSlide += direction;
            
            // Loop around if we go past the first or last image
            if (currentSlide >= totalSlides) {
                currentSlide = 0;
            } else if (currentSlide < 0) {
                currentSlide = totalSlides - 1;
            }
            
            showSlide(currentSlide);
        }

        // Optional: Auto-slide every 5 seconds
        setInterval(() => {
            changeSlide(1);
        }, 5000);
    </script>

        <script>
        // Accordion Logic
        const accordions = document.querySelectorAll('.accordion-header');

        accordions.forEach(acc => {
            acc.addEventListener('click', function() {
                // Toggle active class for the arrow rotation
                this.parentElement.classList.toggle('active');

                // Get the content panel next to the clicked header
                const panel = this.nextElementSibling;

                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null; // Close it
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px"; // Open it based on content height
                }
            });
        });
    </script>
            <!-- Load Pannellum JS -->
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    
    <script>
        // Initialize the viewer
        const viewer = pannellum.viewer('panorama', {
            "default": {
                "firstScene": "reception",
                "autoLoad": true,
                "autoRotate": -1.5,
                "showControls": true,
                "compass": false
            },
            "scenes": {
                "reception": {
                    // REPLACE THIS URL WITH YOUR RECEPTION IMAGE (e.g., "reception_360.jpg")
                    "panorama": "reception_360.png", 
                    "type": "equirectangular"
                },
                "single": {
                    // REPLACE THIS URL WITH YOUR SINGLE ROOM IMAGE
                    "panorama": "single_360.png", 
                    "type": "equirectangular"
                },
                "couple": {
                    // REPLACE THIS URL WITH YOUR COUPLES SUITE IMAGE
                    "panorama": "couple_360.png", 
                    "type": "equirectangular"
                },
                "family": {
                    // REPLACE THIS URL WITH YOUR FAMILY LOUNGE IMAGE
                    "panorama": "family_360.png", 
                    "type": "equirectangular"
                }
            }
        });

        // Function to switch rooms and update button styles
               function switchTour(sceneId, btnElement) {
            viewer.loadScene(sceneId);
            
            let buttons = document.querySelectorAll('.tour-btn');
            buttons.forEach(btn => {
                // Reset to unselected style (with !important)
                btn.style.setProperty('background', 'transparent', 'important');
                btn.style.setProperty('color', '#6F4E37', 'important');
                btn.style.setProperty('border', '1px solid #6F4E37', 'important');
            });
            
            // Set clicked button to selected style (with !important)
            btnElement.style.setProperty('background', '#6F4E37', 'important');
            btnElement.style.setProperty('color', '#fff', 'important');
        }
    </script>
</body>
</html>
</body>
</html>
</body>
</html>
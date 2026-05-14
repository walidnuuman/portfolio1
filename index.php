<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walid | Full-Stack Developer Portfolio</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <div class="nav-container container">
            <a href="#" class="logo">Walid<span>.dev</span></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#about">About</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><button id="theme-toggle" class="btn-icon"><i class="fas fa-moon"></i></button></li>
                </ul>
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="container hero-content">
            <h1>Hi, I'm Walid 👋</h1>
            <h2>Full-Stack Web Developer</h2>
            <p>I build dynamic, responsive, and user-centric web applications integrating modern frontend technologies and robust backend architectures.</p>
            <div class="hero-cta">
                <a href="#projects" class="btn btn-primary">View My Work</a>
                <a href="#contact" class="btn btn-secondary">Get In Touch</a>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="section bg-light">
        <div class="container">
            <h2 class="section-title">My Projects</h2>
            <p class="section-subtitle">A selection of my recent full-stack work.</p>
            <!-- Dynamic Content Container -->
            <div id="projects-grid" class="grid-projects">
                <!-- Projects will be injected here via AJAX -->
                <p>Loading projects...</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Contact Me</h2>
            <p class="section-subtitle">Have a project in mind? Let's talk.</p>
            
            <div class="contact-wrapper">
                <form id="contact-form" class="contact-form">
                    <div id="form-message" class="alert hidden"></div>
                    
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="John Doe">
                        <small class="error-msg">Name is required</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="john@example.com">
                        <small class="error-msg">Valid email is required</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Project Inquiry">
                        <small class="error-msg">Subject is required</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="How can I help you?"></textarea>
                        <small class="error-msg">Message is required</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <p><i class="fas fa-envelope"></i> walid@example.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> New York, NY</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2026 Walid. All Rights Reserved. | <a href="login.php">Admin Login</a></p>
        </div>
    </footer>

    <!-- Main JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>
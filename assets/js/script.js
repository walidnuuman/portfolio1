document.addEventListener('DOMContentLoaded', () => {
    
    /* ==============================================
       1. Mobile Menu Toggle
       ============================================== */
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if(hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    /* ==============================================
       2. Dark Mode Toggle
       ============================================== */
    const themeToggleBtn = document.getElementById('theme-toggle');
    const body = document.documentElement;
    const themeIcon = themeToggleBtn.querySelector('i');

    // Check localStorage for saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.setAttribute('data-theme', 'dark');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
    }

    themeToggleBtn.addEventListener('click', () => {
        if (body.hasAttribute('data-theme')) {
            body.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
        } else {
            body.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        }
    });

    /* ==============================================
       3. Fetch Projects dynamically via AJAX (Fetch API)
       ============================================== */
    const projectsGrid = document.getElementById('projects-grid');
    
    if(projectsGrid) {
        fetchProjects();
    }

    async function fetchProjects() {
        try {
            // Call PHP API
            const response = await fetch('api/get_projects.php');
            if(!response.ok) throw new Error('Network response was not ok');
            
            const projects = await response.json();
            
            // Clear loading text
            projectsGrid.innerHTML = '';
            
            if(projects.length === 0) {
                projectsGrid.innerHTML = '<p>No projects found.</p>';
                return;
            }

            // Generate HTML for each project
            projects.forEach(project => {
                const projectCard = document.createElement('div');
                projectCard.classList.add('project-card');
                projectCard.innerHTML = `
                    <img src="${project.image_url}" alt="${project.title}" class="project-img">
                    <div class="project-content">
                        <h3 class="project-title">${project.title}</h3>
                        <p class="project-desc">${project.description}</p>
                        <a href="${project.project_url}" target="_blank" class="btn btn-secondary">View Project</a>
                    </div>
                `;
                projectsGrid.appendChild(projectCard);
            });
        } catch (error) {
            console.error('Error fetching projects:', error);
            projectsGrid.innerHTML = '<p>Failed to load projects. Please try again later.</p>';
        }
    }

    /* ==============================================
       4. Form Validation & AJAX Submission
       ============================================== */
    const contactForm = document.getElementById('contact-form');
    
    if(contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent default submission
            
            // Validate inputs
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            let isValid = true;
            
            // Check Name
            if(name.value.trim() === '') {
                setError(name);
                isValid = false;
            } else {
                setSuccess(name);
            }
            
            // Check Email
            if(email.value.trim() === '' || !isValidEmail(email.value.trim())) {
                setError(email);
                isValid = false;
            } else {
                setSuccess(email);
            }
            
            // Check Subject
            if(subject.value.trim() === '') {
                setError(subject);
                isValid = false;
            } else {
                setSuccess(subject);
            }
            
            // Check Message
            if(message.value.trim() === '') {
                setError(message);
                isValid = false;
            } else {
                setSuccess(message);
            }
            
            // If validation passes, submit via AJAX
            if(isValid) {
                const formData = new FormData(contactForm);
                const formMessage = document.getElementById('form-message');
                
                try {
                    const response = await fetch('api/submit_contact.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    formMessage.classList.remove('hidden', 'alert-error', 'alert-success');
                    
                    if(result.status === 'success') {
                        formMessage.classList.add('alert-success');
                        formMessage.textContent = result.message;
                        contactForm.reset();
                    } else {
                        formMessage.classList.add('alert-error');
                        formMessage.textContent = result.message;
                    }
                } catch (error) {
                    formMessage.classList.remove('hidden', 'alert-success');
                    formMessage.classList.add('alert-error');
                    formMessage.textContent = 'An error occurred while sending your message.';
                }
            }
        });
    }

    // Helper functions for Form Validation
    function setError(input) {
        input.parentElement.classList.add('error');
    }
    
    function setSuccess(input) {
        input.parentElement.classList.remove('error');
    }
    
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

});
// Merged doctor-dashboard.js - Combines all dashboard functionality

// Track if we're in edit mode
let currentEditingBlogId = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeBlogSystem();
    updateClock();
    setInterval(updateClock, 1000);
});

// ============================================
// SIDEBAR & SECTION NAVIGATION
// ============================================

function initializeSidebar() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionName = this.getAttribute('data-section');
            
            if (!sectionName) return;
            
            // Remove active from all sections and links
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            
            // Add active to current
            this.classList.add('active');
            const section = document.getElementById(sectionName);
            
            if (section) {
                section.classList.add('active');
                
                // Update page title
                const titles = {
                    'dashboard': 'Dashboard',
                    'consultations': 'All Consultations',
                    'upcoming': 'Upcoming Consultations',
                    'doctors': 'All Doctors',
                    'patients': 'All Patients',
                    'services': 'Our Services',
                    'blogs': 'My Blog Posts',
                    'profile': 'My Profile'
                };
                
                document.getElementById('pageTitle').textContent = titles[sectionName] || 'Dashboard';
                
                // Load blogs if blogs section clicked
                if (sectionName === 'blogs') {
                    loadBlogs();
                }
            }
        });
    });
}

// ============================================
// BLOG SYSTEM
// ============================================

function initializeBlogSystem() {
    const createBlogBtn = document.getElementById('createBlogBtn');
    const blogFormModal = document.getElementById('blogFormModal');
    const cancelBlogBtn = document.getElementById('cancelBlogBtn');
    const blogForm = document.getElementById('blogForm');
    const closeModal = document.querySelector('.close-modal');
    
    if (createBlogBtn) {
        createBlogBtn.addEventListener('click', function() {
            openBlogForm(null); // null = create mode
        });
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', closeBlogForm);
    }
    
    if (cancelBlogBtn) {
        cancelBlogBtn.addEventListener('click', closeBlogForm);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === blogFormModal) {
            closeBlogForm();
        }
    });
    
    if (blogForm) {
        blogForm.addEventListener('submit', submitBlogForm);
    }
}

function openBlogForm(blogId = null) {
    const modal = document.getElementById('blogFormModal');
    const form = document.getElementById('blogForm');
    const heading = document.querySelector('.modal-header h2');
    const messageDiv = document.getElementById('blogFormMessage');
    
    // Reset form and hide messages
    form.reset();
    messageDiv.style.display = 'none';
    currentEditingBlogId = blogId;
    
    if (blogId) {
        // EDIT MODE
        heading.textContent = 'Edit Blog Post';
        fetchBlogForEditing(blogId);
    } else {
        // CREATE MODE
        heading.textContent = 'Create Blog Post';
        if (modal) {
            modal.style.display = 'block';
        }
    }
}

function closeBlogForm() {
    const modal = document.getElementById('blogFormModal');
    const form = document.getElementById('blogForm');
    
    if (modal) {
        modal.style.display = 'none';
    }
    
    // Reset form and clear edit mode
    if (form) {
        form.reset();
    }
    currentEditingBlogId = null;
}

function fetchBlogForEditing(blogId) {
    fetch(`get-single-blog.php?blog_id=${blogId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate form with existing data
                document.getElementById('blogTitle').value = data.blog.title;
                document.getElementById('blogContent').value = data.blog.content;
                document.getElementById('blogExcerpt').value = data.blog.excerpt || '';
                document.getElementById('blogStatus').value = data.blog.status;
                
                // Show modal
                const modal = document.getElementById('blogFormModal');
                if (modal) {
                    modal.style.display = 'block';
                }
            } else {
                alert('Error loading blog: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading blog post');
        });
}

function submitBlogForm(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('blogFormMessage');
    const submitBtn = document.querySelector('#blogForm button[type="submit"]');
    
    // Add blog_id if editing
    if (currentEditingBlogId) {
        formData.append('blog_id', currentEditingBlogId);
    }
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.textContent = currentEditingBlogId ? 'Updating...' : 'Publishing...';
    
    console.log('Submitting blog form...', currentEditingBlogId ? 'EDIT MODE' : 'CREATE MODE');
    
    // Choose endpoint based on mode
    const endpoint = currentEditingBlogId ? 'process-blog-edit.php' : 'process-blog-post.php';
    
    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        messageDiv.style.display = 'block';
        
        if (data.success) {
            messageDiv.className = 'alert alert-success';
            messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
            
            // Reset form and edit mode
            document.getElementById('blogForm').reset();
            currentEditingBlogId = null;
            
            setTimeout(() => {
                closeBlogForm();
                loadBlogs();
            }, 2000);
        } else {
            messageDiv.className = 'alert alert-error';
            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.error || 'Error processing blog');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-error';
        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error: ' + error.message;
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = currentEditingBlogId ? 'Update Post' : 'Publish Post';
    });
}

function loadBlogs() {
    console.log('Loading blogs...');
    const blogsList = document.getElementById('blogsList');
    
    if (!blogsList) {
        console.error('blogsList element not found');
        return;
    }
    
    blogsList.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    
    fetch('get-doctor-blogs.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Blogs data:', data);
            
            if (data.success) {
                blogsList.innerHTML = data.html;
            } else {
                blogsList.innerHTML = '<p style="color: red;"><i class="fas fa-exclamation-circle"></i> Error: ' + (data.error || 'Unknown error') + '</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            blogsList.innerHTML = '<p style="color: red;"><i class="fas fa-exclamation-circle"></i> Error loading blogs: ' + error.message + '</p>';
        });
}

function editBlog(blogId) {
    openBlogForm(blogId);
}

function deleteBlog(blogId) {
    if (confirm('Are you sure you want to delete this blog post? This action cannot be undone.')) {
        fetch('delete-blog-post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'blog_id=' + blogId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Blog post deleted successfully');
                loadBlogs();
            } else {
                alert('Error deleting blog: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting blog post');
        });
    }
}

// ============================================
// UTILITIES
// ============================================

function updateClock() {
    const now = new Date();
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');
    
    if (dateElement) {
        dateElement.textContent = now.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }
    
    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString();
    }
}
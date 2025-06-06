document.addEventListener('DOMContentLoaded', function() {
    console.log('Blog.js loaded'); // Debug log

    // Function to load blog posts
    function loadBlogPosts() {
        console.log('Fetching blog posts...'); // Debug log
        fetch('/api/get_posts.php')  // Using absolute path
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                console.log('Response received:', response); // Debug log
                return response.json();
            })
            .then(posts => {
                console.log('Posts loaded:', posts); // Debug log
                const blogGrid = document.querySelector('.col-lg-8 .row');
                if (!blogGrid) {
                    console.error('Blog grid element not found!'); // Debug log
                    return;
                }
                blogGrid.innerHTML = ''; // Clear existing content

                posts.forEach(post => {
                    const date = new Date(post.created_at);
                    const month = date.toLocaleString('default', { month: 'short' });
                    const day = date.getDate();

                    const postElement = document.createElement('div');
                    postElement.className = 'col-md-12 mb-3';
                    postElement.innerHTML = `
                        <div class="position-relative">
                            <img class="img-fluid w-100" src="${post.featured_image}" alt="${post.title}">
                            <div class="position-absolute bg-primary d-flex flex-column align-items-center justify-content-center"
                                style="width: 80px; height: 80px; bottom: 0; left: 0;">
                                <h6 class="text-uppercase mt-2 mb-n2">${month}</h6>
                                <h1 class="m-0">${day}</h1>
                            </div>
                        </div>
                        <div class="border border-top-0 mb-3" style="padding: 30px;">
                            <div class="d-flex mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" style="width: 40px; height: 40px;" src="img/user.jpg" alt="">
                                    <a class="text-muted ml-2" href="">${post.author}</a>
                                </div>
                                <div class="d-flex align-items-center ml-4">
                                    <i class="far fa-bookmark text-primary"></i>
                                    <a class="text-muted ml-2" href="">${post.status}</a>
                                </div>
                            </div>
                            <a class="h4 font-weight-bold" href="single.html?slug=${post.slug}">${post.title}</a>
                            <p class="mt-3">${post.content.substring(0, 200)}...</p>
                            <a href="single.html?slug=${post.slug}" class="btn btn-primary">Read More</a>
                        </div>
                    `;
                    blogGrid.appendChild(postElement);
                });
            })
            .catch(error => {
                console.error('Error loading blog posts:', error);
                const blogGrid = document.querySelector('.col-lg-8 .row');
                if (blogGrid) {
                    blogGrid.innerHTML = '<div class="alert alert-danger">Error loading blog posts. Please check the console for details.</div>';
                }
            });
    }

    // Load posts when page loads
    loadBlogPosts();
}); 
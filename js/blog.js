document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    const postsPerPage = 3;
    let currentSearch = '';

    // Function to load blog posts
    function loadBlogPosts(page = 1, search = '') {
        const url = search 
            ? `/api/search_posts.php?page=${page}&per_page=${postsPerPage}&search=${encodeURIComponent(search)}`
            : `/api/get_posts.php?page=${page}&per_page=${postsPerPage}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const { posts, total_posts } = data;
                const blogGrid = document.getElementById('blog-posts');
                if (!blogGrid) {
                    console.error('Blog grid element not found!');
                    return;
                }
                blogGrid.innerHTML = ''; // Clear existing content

                if (posts.length === 0) {
                    blogGrid.innerHTML = '<div class="col-12 text-center"><p>No posts found.</p></div>';
                    return;
                }

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

                // Update pagination
                updatePagination(page, Math.ceil(total_posts / postsPerPage));
            })
            .catch(error => {
                console.error('Error loading blog posts:', error);
                const blogGrid = document.getElementById('blog-posts');
                if (blogGrid) {
                    blogGrid.innerHTML = '<div class="alert alert-danger">Error loading blog posts. Please try again later.</div>';
                }
            });
    }

    // Function to load recent posts
    function loadRecentPosts() {
        fetch('/api/get_recent_posts.php?limit=5')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.posts) {
                    const recentPostsContainer = document.querySelector('.mb-5 .d-flex.flex-column');
                    if (recentPostsContainer) {
                        recentPostsContainer.innerHTML = ''; // Clear existing content
                        data.posts.forEach(post => {
                            const date = new Date(post.created_at);
                            const month = date.toLocaleString('default', { month: 'short' });
                            const day = date.getDate();
                            
                            const postElement = document.createElement('div');
                            postElement.className = 'd-flex mb-3';
                            postElement.innerHTML = `
                                <img class="img-fluid" src="${post.featured_image}" style="width: 80px; height: 80px;" alt="${post.title}">
                                <div class="d-flex align-items-center border border-left-0 px-3" style="height: 80px;">
                                    <a class="text-secondary font-weight-semi-bold" href="single.html?slug=${post.slug}">${post.title}</a>
                                </div>
                            `;
                            recentPostsContainer.appendChild(postElement);
                        });
                    }
                }
            })
            .catch(error => console.error('Error loading recent posts:', error));
    }

    // Function to update pagination
    function updatePagination(currentPage, totalPages) {
        const pagination = document.getElementById('pagination');
        if (!pagination) return;

        let paginationHTML = `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
        `;

        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        paginationHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        `;

        pagination.innerHTML = paginationHTML;

        // Add click event listeners to pagination links
        pagination.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.target.closest('.page-link').dataset.page);
                if (page && page !== currentPage) {
                    currentPage = page;
                    loadBlogPosts(page, currentSearch);
                }
            });
        });
    }

    // Set up search functionality
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const searchInput = searchForm.querySelector('input[type="text"]');
            currentSearch = searchInput.value.trim();
            currentPage = 1;
            loadBlogPosts(currentPage, currentSearch);
        });
    }

    // Load initial posts and recent posts
    loadBlogPosts(currentPage);
    loadRecentPosts();
}); 
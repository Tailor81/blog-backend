document.addEventListener('DOMContentLoaded', function() {
    console.log('Single.js loaded'); // Debug log

    // Function to get URL parameters
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Function to load single blog post
    function loadBlogPost() {
        const slug = getUrlParameter('slug');
        console.log('Loading post with slug:', slug); // Debug log

        if (!slug) {
            console.error('No slug provided'); // Debug log
            const postContent = document.querySelector('.col-lg-8');
            if (postContent) {
                postContent.innerHTML = '<div class="alert alert-danger">No post specified</div>';
            }
            return;
        }

        console.log('Fetching post data...'); // Debug log
        fetch(`/api/get_post.php?slug=${slug}`)  // Using absolute path
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                console.log('Response received:', response); // Debug log
                return response.json();
            })
            .then(post => {
                console.log('Post data loaded:', post); // Debug log
                if (post.error) {
                    console.error('Post error:', post.error); // Debug log
                    const postContent = document.querySelector('.col-lg-8');
                    if (postContent) {
                        postContent.innerHTML = `<div class="alert alert-danger">${post.error}</div>`;
                    }
                    return;
                }

                const date = new Date(post.created_at);
                const month = date.toLocaleString('default', { month: 'short' });
                const day = date.getDate();

                // Update page title
                document.title = `PYXIASINC - ${post.title}`;

                // Update post content
                const postContent = document.querySelector('.col-lg-8');
                if (!postContent) {
                    console.error('Post content element not found!'); // Debug log
                    return;
                }

                const postElement = document.createElement('div');
                postElement.innerHTML = `
                    <div class="position-relative">
                        <img class="img-fluid w-100" src="${post.featured_image}" alt="${post.title}">
                        <div class="position-absolute bg-primary d-flex flex-column align-items-center justify-content-center"
                            style="width: 80px; height: 80px; bottom: 0; left: 0;">
                            <h6 class="text-uppercase mt-2 mb-n2">${month}</h6>
                            <h1 class="m-0">${day}</h1>
                        </div>
                    </div>
                    <div class="pt-4 pb-2">
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
                        <h2 class="font-weight-bold">${post.title}</h2>
                    </div>
                    <div class="mb-5">
                        ${post.content}
                    </div>
                `;
                postContent.innerHTML = '';
                postContent.appendChild(postElement);
            })
            .catch(error => {
                console.error('Error loading blog post:', error);
                const postContent = document.querySelector('.col-lg-8');
                if (postContent) {
                    postContent.innerHTML = '<div class="alert alert-danger">Error loading post. Please check the console for details.</div>';
                }
            });
    }

    // Load post when page loads
    loadBlogPost();
}); 
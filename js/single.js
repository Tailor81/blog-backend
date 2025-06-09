document.addEventListener('DOMContentLoaded', function() {
    // Get the slug from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get('slug');

    if (!slug) {
        document.getElementById('blog-content').innerHTML = '<div class="alert alert-danger">No post specified.</div>';
        return;
    }

    // Fetch the blog post
    fetch(`/api/get_post.php?slug=${slug}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Check if we have a valid post object
            if (!data || !data.post) {
                throw new Error('Invalid post data received from server');
            }

            const post = data.post;
            const date = new Date(post.created_at);
            const month = date.toLocaleString('default', { month: 'short' });
            const day = date.getDate();

            const blogContent = document.getElementById('blog-content');
            blogContent.innerHTML = `
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
                    ${post.content ? post.content.split('\n\n').map(paragraph => `<p>${paragraph}</p>`).join('') : ''}
                </div>
            `;

            // Update page title
            document.title = `PYXIASINC - ${post.title}`;
        })
        .catch(error => {
            console.error('Error loading blog post:', error);
            const errorMessage = error.message || 'Unknown error occurred';
            document.getElementById('blog-content').innerHTML = `
                <div class="alert alert-danger">
                    <h4 class="alert-heading">Error Loading Blog Post</h4>
                    <p>${errorMessage}</p>
                    <hr>
                    <p class="mb-0">Please try again later or contact support if the problem persists.</p>
                </div>
            `;
        });
}); 
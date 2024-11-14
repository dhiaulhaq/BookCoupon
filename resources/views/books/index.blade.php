<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookCoupon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar {
            min-height: 100vh;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }

        #sidebar-wrapper {
            min-height: 100vh;
            /* margin-left: -15rem; */
            transition: margin .25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        body.sb-sidenav-toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            /* body.sb-sidenav-toggled #sidebar-wrapper {
                margin-left: -15rem;
            } */
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <a href="/books">BookCoupon</a>
            </div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light" data-bs-toggle="collapse" data-bs-target="#booksSubmenu" aria-expanded="false">
                    <i class="bi bi-book me-2"></i>Books
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="booksSubmenu">
                    <a href="#" class="list-group-item list-group-item-action bg-light ps-4" onclick="showContent('books-list')">List</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light ps-4" onclick="showContent('books-add')">Add New</a>
                </div>
                <a href="#" class="list-group-item list-group-item-action bg-light" data-bs-toggle="collapse" data-bs-target="#couponsSubmenu" aria-expanded="false">
                    <i class="bi bi-tag me-2"></i>Coupons
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="couponsSubmenu">
                    <a href="#" class="list-group-item list-group-item-action bg-light ps-4" onclick="showContent('coupons-list')">List</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light ps-4" onclick="showContent('coupons-add')">Add New</a>
                </div>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <span class="navbar-toggler-icon" id="menu-toggle" style="margin-left: 5%;"></span>
            </nav> -->

            <div class="container-fluid" id="content-area">
                <!-- Content will be dynamically loaded here -->
            </div>
        </div>
    </div>
    <!-- /#wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const apiUrl = '/api/books';

        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.body.classList.toggle("sb-sidenav-toggled");
        });

        function showContent(contentType) {
            const contentArea = document.getElementById('content-area');
            let content = '';

            if (contentType === 'books-list') {
                content = `
                    <h1 class="mt-4">Books List</h1>
                    <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Search books...">
                        </div>
                    <div id="bookList" class="row"></div>
                `;
                fetchBooks();
            } else if (contentType === 'books-add') {
                content = `
                    <h1 class="mt-4">Add New Book</h1>
                    <form id="bookForm">
                        <input type="text" class="form-control mb-2" id="title" placeholder="Title" required>
                        <input type="text" class="form-control mb-2" id="author" placeholder="Author" required>
                        <textarea class="form-control mb-2" id="description" placeholder="Description" required></textarea>
                        <input type="number" class="form-control mb-2" id="price" placeholder="Price" required>
                        <input type="number" class="form-control mb-2" id="stock" placeholder="Stock" required>
                        <input type="file" class="form-control mb-2" id="cover_image" required>
                        <button type="submit" class="btn btn-primary">Add Book</button>
                    </form>
                `;
            }

            contentArea.innerHTML = content;

            if (contentType === 'books-add') {
                document.getElementById('bookForm').addEventListener('submit', handleFormSubmit);
            }
        }

        async function fetchBooks() {
            const response = await fetch(apiUrl);
            const data = await response.json();
            displayBooks(data.data.data);
        }

        function displayBooks(books) {
            const bookList = document.getElementById('bookList');
            bookList.innerHTML = '';
            books.forEach(book => {
                const bookCard = document.createElement('div');
                bookCard.classList.add('col-md-4');
                bookCard.innerHTML = `
                    <div class="card mb-3">
                        <img src="/storage/books/${book.cover_image}" class="card-img-top" alt="${book.title}">
                        <div class="card-body">
                            <h5 class="card-title">${book.title}</h5>
                            <p class="card-text"><strong>Author:</strong> ${book.author}</p>
                            <p class="card-text"><strong>Stock:</strong> ${book.stock}</p>
                            <button class="btn btn-danger" onclick="deleteBook(${book.id})">Delete</button>
                            <button class="btn btn-secondary" onclick="editBook(${book.id})">Edit</button>
                        </div>
                    </div>
                `;
                bookList.appendChild(bookCard);
            });
        }

        async function handleFormSubmit(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('title', document.getElementById('title').value);
            formData.append('author', document.getElementById('author').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('price', document.getElementById('price').value);
            formData.append('stock', document.getElementById('stock').value);
            formData.append('cover_image', document.getElementById('cover_image').files[0]);

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData,
            });

            if (response.ok) {
                showContent('books-list');
            }
        }

        async function deleteBook(id) {
            const response = await fetch(`${apiUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            });

            if (response.ok) {
                fetchBooks();
            }
        }
    </script>
</body>

</html>
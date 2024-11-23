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
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <a href="/">BookCoupon</a>
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
                <a href="#" class="list-group-item list-group-item-action bg-light" data-bs-toggle="collapse" data-bs-target="#checkoutSubmenu" aria-expanded="false">
                    <i class="bi bi-cart me-2"></i>Checkout
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="checkoutSubmenu">
                    <a href="#" class="list-group-item list-group-item-action bg-light ps-4" onclick="showContent('checkout')">Apply Coupon</a>
                </div>
            </div>
        </div>

        <div id="page-content-wrapper">
            <div class="container-fluid" id="content-area">
                <!-- Tampilannya di sini -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const bookApiUrl = '/api/books';
        const couponApiUrl = '/api/coupons';
        const checkoutApiUrl = '/api/checkout';

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
            } else if (contentType === 'coupons-list') {
                content = `
                <h1 class="mt-4">Coupons List</h1>
                <div id="couponList" class="row"></div>
            `;
                fetchCoupons();
            } else if (contentType === 'coupons-add') {
                content = `
                <h1 class="mt-4">Add New Coupon</h1>
                <form id="couponForm">
                    <input type="text" class="form-control mb-2" id="code_prefix" placeholder="Code Prefix (e.g., SUMMER)" required>
                    <input type="number" class="form-control mb-2" id="discount" placeholder="Discount (%)" required>
                    <input type="date" class="form-control mb-2" id="expiry_date" required>
                    <button type="submit" class="btn btn-primary">Add Coupon</button>
                </form>
            `;
            } else if (contentType === 'checkout') {
                content = `
                <h1 class="mt-4">Checkout</h1>
                <form id="checkoutForm">
                    <input type="text" class="form-control mb-2" id="coupon_code" placeholder="Coupon Code" required>
                    <input type="number" class="form-control mb-2" id="total_price" placeholder="Total Price" required>
                    <button type="submit" class="btn btn-primary">Apply Coupon</button>
                </form>
                <div id="checkoutResult" class="mt-4"></div>
            `;
            }

            contentArea.innerHTML = content;

            if (contentType === 'books-add') {
                document.getElementById('bookForm').addEventListener('submit', handleFormSubmit);
            } else if (contentType === 'coupons-add') {
                document.getElementById('couponForm').addEventListener('submit', handleCouponFormSubmit);
            } else if (contentType === 'checkout') {
                document.getElementById('checkoutForm').addEventListener('submit', handleCheckoutFormSubmit);
            }
        }

        async function fetchBooks() {
            const response = await fetch(bookApiUrl);
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

            const response = await fetch(bookApiUrl, {
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
            const response = await fetch(`${bookApiUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            });

            if (response.ok) {
                fetchBooks();
            }
        }

        async function fetchCoupons() {
            const response = await fetch(couponApiUrl);
            const data = await response.json();
            displayCoupons(data.data.data);
        }

        function displayCoupons(coupons) {
            const couponList = document.getElementById('couponList');
            couponList.innerHTML = '';
            coupons.forEach(coupon => {
                const couponCard = document.createElement('div');
                couponCard.classList.add('col-md-4');
                couponCard.innerHTML = `
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Code: ${coupon.code}</h5>
                            <p class="card-text">Discount: ${coupon.discount}%</p>
                            <p class="card-text">Expiry Date: ${coupon.expiry_date}</p>
                            <button class="btn btn-secondary mb-2" onclick="editCoupon(${coupon.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteCoupon(${coupon.id})">Delete</button>
                        </div>
                    </div>
                `;
                couponList.appendChild(couponCard);
            });
        }

        async function handleCouponFormSubmit(e) {
            e.preventDefault();
            const formData = {
                code_prefix: document.getElementById('code_prefix').value,
                discount: document.getElementById('discount').value,
                expiry_date: document.getElementById('expiry_date').value,
            };

            const response = await fetch(couponApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(formData),
            });

            if (response.ok) {
                showContent('coupons-list');
            }
        }

        async function deleteCoupon(id) {
            const response = await fetch(`${couponApiUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            if (response.ok) {
                fetchCoupons();
            }
        }

        async function editCoupon(id) {
            const response = await fetch(`${couponApiUrl}/${id}`);
            const data = await response.json();
            console.log(data);

            const contentArea = document.getElementById('content-area');
            contentArea.innerHTML = `
                <h1 class="mt-4">Edit Coupon</h1>
                <form id="editCouponForm">
                    <input type="text" class="form-control mb-2" id="edit_code_prefix" value="${data.data.code}" disabled>
                    <input type="number" class="form-control mb-2" id="edit_discount" value="${data.data.discount}" required>
                    <input type="date" class="form-control mb-2" id="edit_expiry_date" value="${data.data.expiry_date}" required>
                    <button type="submit" class="btn btn-primary">Update Coupon</button>
                </form>
            `;

            document.getElementById('editCouponForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = {
                    discount: document.getElementById('edit_discount').value,
                    expiry_date: document.getElementById('edit_expiry_date').value,
                };

                const updateResponse = await fetch(`${couponApiUrl}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(formData),
                });

                if (updateResponse.ok) {
                    showContent('coupons-list');
                }
            });
        }

        async function handleCheckoutFormSubmit(e) {
            e.preventDefault();
            const formData = {
                coupon_code: document.getElementById('coupon_code').value,
                total_price: document.getElementById('total_price').value
            };

            const response = await fetch(checkoutApiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            const checkoutResult = document.getElementById('checkoutResult');
            if (result.success) {
                checkoutResult.innerHTML = `
                <div class="alert alert-success">Discount Applied! Total after discount: Rp. ${result.data}</div>
            `;
            } else {
                checkoutResult.innerHTML = `
                <div class="alert alert-danger">${result.message}</div>
            `;
            }
        }
    </script>
</body>

</html>
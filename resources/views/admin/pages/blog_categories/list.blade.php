@extends("admin.pages.app")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">{{ __("Blog Categories") }}</h4>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __("New Category") }}
                    </button>
                </div>

                <!-- Toast container -->
                <div class="position-fixed end-0 top-0 p-3" style="z-index: 1100;">
                    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessage">{{ __("Success message") }}</div>
                            <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"
                                aria-label="{{ __("Close") }}"></button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table align-middle" id="categoriesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:60px">#</th>
                                        <th>{{ __("Name") }}</th>
                                        <th>{{ __("Status") }}</th>
                                        <th>{{ __("Image") }}</th>
                                        <th class="text-center" style="width:180px">{{ __("Actions") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $index => $cat)
                                        <tr id="categoryRow{{ $cat->id }}">
                                            <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                            <td class="category-name">{{ $cat->name }}</td>
                                            <td>{{ ucfirst($cat->status) }}</td>
                                            <td class="category-image">
                                                @if ($cat->image)
                                                    <img src="{{ asset("storage/" . $cat->image) }}"
                                                        alt="{{ $cat->name }}" width="60" height="60"
                                                        style="object-fit:cover;border-radius:4px;">
                                                @else
                                                    <span class="text-muted">{{ __("No image") }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-secondary edit-category"
                                                    data-id="{{ $cat->id }}">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-category"
                                                    data-id="{{ $cat->id }}" data-name="{{ $cat->name }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3 px-5">
                                {{ $categories->links("pagination::bootstrap-5") }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="createCategoryForm" class="modal-content" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Create Category") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label class="form-label">{{ __("Name") }}</label>
                        <input type="text" name="name" class="form-control" required>
                        <div class="invalid-feedback name-error"></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __("Description") }}</label>
                        <textarea name="description" class="form-control"></textarea>
                        <div class="invalid-feedback description-error"></div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">{{ __("Status") }}</label>
                        <select name="status" class="form-select">
                            <option value="active">{{ __("Active") }}</option>
                            <option value="inactive">{{ __("Inactive") }}</option>
                        </select>
                        <div class="invalid-feedback status-error"></div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">{{ __("Image") }}</label>
                        <input type="file" name="image" class="form-control">
                        <div class="invalid-feedback image-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                    <button type="submit" class="btn btn-success">{{ __("Create") }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="editCategoryForm" class="modal-content" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Edit Category") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label class="form-label">{{ __("Name") }}</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                        <div class="invalid-feedback name-error"></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">{{ __("Description") }}</label>
                        <textarea name="description" id="edit_description" class="form-control"></textarea>
                        <div class="invalid-feedback description-error"></div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">{{ __("Status") }}</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="active">{{ __("Active") }}</option>
                            <option value="inactive">{{ __("Inactive") }}</option>
                        </select>
                        <div class="invalid-feedback status-error"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __("Image (optional)") }}</label>
                        <input type="file" name="image" class="form-control">
                        <div class="invalid-feedback image-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __("Close") }}</button>
                    <button type="submit" class="btn btn-primary">{{ __("Update") }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">{{ __("Confirm Delete") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __("Close") }}"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __("Are you sure you want to delete") }} <strong id="deleteCategoryName"></strong>?</p>
                    <input type="hidden" id="deleteCategoryId">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                    <button type="button" class="btn btn-danger"
                        id="confirmDeleteCategoryBtn">{{ __("Delete") }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', () => {


            const toastEl = document.getElementById('liveToast');
            const toastMsg = document.getElementById('toastMessage');
            const toast = new bootstrap.Toast(toastEl);

            function showToast(message, type = 'success') {
                toastMsg.textContent = message;
                toastEl.className = `toast align-items-center text-bg-${type} border-0`;
                toast.show();
            }

            function showErrors(form, errors) {
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                for (const [field, msgs] of Object.entries(errors)) {
                    const input = form.querySelector(`[name="${field}"]`);
                    const error = form.querySelector(`.${field}-error`);
                    if (input && error) {
                        input.classList.add('is-invalid');
                        error.textContent = msgs[0];
                    }
                }
            }


            function attachDynamicEvents() {
                document.querySelectorAll('.edit-category').forEach(btn => {
                    btn.onclick = () => openEdit(btn.dataset.id);
                });
                document.querySelectorAll('.delete-category').forEach(btn => {
                    btn.onclick = () => openDelete(btn.dataset.id, btn.dataset.name);
                });
            }

            function openEdit(id) {
                fetch(`/admin/blog_categories/${id}`)
                    .then(r => r.json())
                    .then(cat => {
                        document.getElementById('edit_id').value = cat.id;
                        document.getElementById('edit_name').value = cat.name;
                        document.getElementById('edit_description').value = cat.description ?? '';
                        document.getElementById('edit_status').value = cat.status;
                        new bootstrap.Modal('#editCategoryModal').show();
                    });
            }

            
            function openDelete(id, name) {
                document.getElementById('deleteCategoryId').value = id;
                document.getElementById('deleteCategoryName').textContent = name;
                new bootstrap.Modal('#deleteCategoryModal').show();
            }

            document.getElementById('createCategoryForm').addEventListener('submit', e => {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);

                fetch('/admin/blog_categories', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const cat = data.category;
                            const tbody = document.querySelector('#categoriesTable tbody');

                            tbody.insertAdjacentHTML('beforeend', `
                    <tr id="categoryRow${cat.id}">
                        <td class="fw-bold text-center">${cat.id}</td>
                        <td class="category-name">${cat.name}</td>
                        <td>${cat.status.charAt(0).toUpperCase() + cat.status.slice(1)}</td>
                        <td>
                            ${cat.image
                                ? `<img src="/storage/${cat.image}" width="60" height="60" style="object-fit:cover;border-radius:4px;">`
                                : '<span class="text-muted">No image</span>'
                            }
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary edit-category" data-id="${cat.id}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-category" data-id="${cat.id}" data-name="${cat.name}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>
                `);

                            bootstrap.Modal.getInstance(document.getElementById('createCategoryModal'))
                                .hide();
                            showToast(data.message, 'success');
                            form.reset();
                            attachDynamicEvents();
                        } else if (data.errors) {
                            showErrors(form, data.errors);
                        }
                    });
            });

            document.getElementById('editCategoryForm').addEventListener('submit', e => {
                e.preventDefault();
                const form = e.target;
                const id = document.getElementById('edit_id').value;
                const formData = new FormData(form);

                fetch(`/admin/blog_categories/${id}`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.getElementById(`categoryRow${id}`);
                            row.querySelector('.category-name').textContent = data.category.name;
                            row.querySelector('td:nth-child(3)').textContent =
                                data.category.status.charAt(0).toUpperCase() + data.category.status
                                .slice(1);


                            const imgCell = row.querySelector('td:nth-child(4)');
                            if (data.category.image) {
                                imgCell.innerHTML =
                                    `<img src="/storage/${data.category.image}" width="60" height="60" style="object-fit:cover;border-radius:4px;">`;
                            }

                            bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'))
                                .hide();
                            showToast(data.message, 'success');
                        } else if (data.errors) {
                            showErrors(form, data.errors);
                        }
                    });
            });

            document.getElementById('confirmDeleteCategoryBtn').addEventListener('click', () => {
                const id = document.getElementById('deleteCategoryId').value;

                fetch(`/admin/blog_categories/${id}`, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`categoryRow${id}`).remove();
                            bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal'))
                                .hide();
                            showToast(data.message, 'success');
                        }
                    });
            });

            attachDynamicEvents();
        });
    </script>
@endpush

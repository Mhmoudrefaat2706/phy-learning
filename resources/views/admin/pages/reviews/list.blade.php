@extends("admin.pages.app")
@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">{{ __('reviews') }}</h4>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createReviewModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('add new review') }}
                    </button>
                </div>
                <!-- Toast container -->
                <div class="position-fixed end-0 top-0 p-3" style="z-index: 1100;">
                    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessage">{{ __('success_message') }}</div>
                            <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"
                                aria-label="{{ __('close') }}"></button>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-hover mb-0 table align-middle" id="reviewsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:60px">#</th>
                                        <th>{{ __('name') }}</th>
                                        <th>{{ __('position') }}</th>
                                        <th>{{ __('description') }}</th>
                                        <th>{{ __('image') }}</th>
                                        <th class="text-center" style="width:180px">{{ __('actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $index => $review)
                                        <tr id="reviewRow{{ $review->id }}">
                                            <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                            <td class="review-name">{{ $review->name }}</td>
                                            <td class="review-position">{{ $review->position }}</td>
                                            <td class="review-description" data-full="{{ e($review->description) }}">
                                                {{ Str::limit($review->description, 50) }}
                                            </td>
                                            <td>
                                                @if ($review->image)
                                                    <img src="{{ asset('storage/' . $review->image) }}" class="rounded"
                                                        width="50" height="50" alt="{{ __('review_image') }}">
                                                @else
                                                    <span class="text-muted">{{ __('no_image') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-secondary edit-review"
                                                    data-id="{{ $review->id }}">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-review"
                                                    data-id="{{ $review->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3 px-5">
                                {{ $reviews->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Create Review Modal --}}
    <div class="modal fade" id="createReviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('add_new_review') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createReviewForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('name') }}</label>
                            <input type="text" class="form-control" id="createReviewName" name="name" required>
                            <div class="invalid-feedback" id="createReviewNameError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('position') }}</label>
                            <input type="text" class="form-control" id="createReviewPosition" name="position">
                            <div class="invalid-feedback" id="createReviewPositionError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('description') }}</label>
                            <textarea class="form-control" id="createReviewDescription" name="description" required></textarea>
                            <div class="invalid-feedback" id="createReviewDescriptionError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('image') }}</label>
                            <input type="file" class="form-control" id="createReviewImage" name="image"
                                accept="image/*">
                            <div class="invalid-feedback" id="createReviewImageError"></div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('cancel') }}</button>
                            <button type="submit" class="btn btn-success">{{ __('add') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Review Modal --}}
    <div class="modal fade" id="editReviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('edit_review') }} - <span id="modalReviewName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editReviewForm" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <input type="hidden" id="modalReviewId">
                        <div class="mb-3">
                            <label class="form-label">{{ __('name') }}</label>
                            <input type="text" class="form-control" id="reviewNameInput" name="name" required>
                            <div class="invalid-feedback" id="reviewNameInputError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('position') }}</label>
                            <input type="text" class="form-control" id="reviewPositionInput" name="position">
                            <div class="invalid-feedback" id="reviewPositionInputError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('description') }}</label>
                            <textarea class="form-control" id="reviewDescriptionInput" name="description" required></textarea>
                            <div class="invalid-feedback" id="reviewDescriptionInputError"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('image') }}</label>
                            <input type="file" class="form-control" id="reviewImageInput" name="image"
                                accept="image/*">
                            <div class="invalid-feedback" id="reviewImageInputError"></div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Review Modal --}}
    <div class="modal fade" id="deleteReviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">{{ __('confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('delete_confirmation') }} <strong id="deleteReviewName"></strong>؟</p>
                    <input type="hidden" id="deleteReviewId">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteReviewBtn">{{ __('delete') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push("scripts")
<script>
document.addEventListener('DOMContentLoaded', function () {

    function clearValidation(form) {
        form.querySelectorAll('.form-control').forEach(i => i.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(d => d.textContent = '');
    }

    const createForm  = document.getElementById('createReviewForm');
    const editForm    = document.getElementById('editReviewForm');
    const createModal = new bootstrap.Modal(document.getElementById('createReviewModal'));
    const editModal   = new bootstrap.Modal(document.getElementById('editReviewModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteReviewModal'));
    const tableBody   = document.querySelector('#reviewsTable tbody');
            const toastEl = document.getElementById('liveToast');
            const toastMsg = document.getElementById('toastMessage');
            const toast = new bootstrap.Toast(toastEl);

            function showToast(message, type = 'success') {
                toastMsg.textContent = message;
                toastEl.className = `toast align-items-center text-bg-${type} border-0`;
                toast.show();
            }
    function makeRow(review, index) {
        return `
        <tr id="reviewRow${review.id}">
            <td class="fw-bold text-center">${index}</td>
            <td class="review-name">${review.name}</td>
            <td class="review-position">${review.position ?? ''}</td>
            <td class="review-description" data-full="${review.description}">
                ${review.description.length > 50 ? review.description.slice(0,50)+'…' : review.description}
            </td>
            <td>${review.image
                ? `<img src="/storage/${review.image}" width="50" height="50" class="rounded">`
                : '<span class="text-muted">لا توجد صورة</span>'}
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-secondary edit-review" data-id="${review.id}">
                    <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-review" data-id="${review.id}">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </td>
        </tr>`;
    }

    // ----------- Create Review -----------
    createForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearValidation(createForm);

        const formData = new FormData(createForm);

        fetch("{{ route('admin.reviews.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const newIndex = tableBody.children.length + 1;
                tableBody.insertAdjacentHTML('afterbegin', makeRow(data.review, newIndex));
                createForm.reset();
                createModal.hide();
                showToast(data.message, 'success');
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([k,v])=>{
                    const inp = createForm.querySelector(`[name=${k}]`);
                    const err = createForm.querySelector(`#createReview${k.charAt(0).toUpperCase()+k.slice(1)}Error`);
                    if (inp) inp.classList.add('is-invalid');
                    if (err) err.textContent = v[0];
                });
            }
        });
    });

    // ----------- Open Edit/Delete -----------
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-review');
        const delBtn  = e.target.closest('.delete-review');

        if (editBtn) {
            const id   = editBtn.dataset.id;
            const row  = document.getElementById(`reviewRow${id}`);
            document.getElementById('modalReviewId').value = id;
            document.getElementById('modalReviewName').textContent = row.querySelector('.review-name').textContent;
            document.getElementById('reviewNameInput').value = row.querySelector('.review-name').textContent;
            document.getElementById('reviewPositionInput').value = row.querySelector('.review-position').textContent;
            document.getElementById('reviewDescriptionInput').value = row.querySelector('.review-description').dataset.full;
            clearValidation(editForm);
            editModal.show();
        }

        if (delBtn) {
            const id   = delBtn.dataset.id;
            const row  = document.getElementById(`reviewRow${id}`);
            document.getElementById('deleteReviewId').value = id;
            document.getElementById('deleteReviewName').textContent = row.querySelector('.review-name').textContent;
            deleteModal.show();
        }
    });

    // ----------- Edit Review -----------
    editForm.addEventListener('submit', function (e) {
        e.preventDefault();
        clearValidation(editForm);

        const id = document.getElementById('modalReviewId').value;
        const formData = new FormData(editForm);
        formData.append('_method', 'PUT');

        fetch(`/admin/reviews/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`reviewRow${id}`);
                row.outerHTML = makeRow(data.review, row.children[0].textContent);
                editModal.hide();
                showToast(data.message, 'success');
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([k,v])=>{
                    const inp = editForm.querySelector(`[name=${k}]`);
                    const err = editForm.querySelector(`#review${k.charAt(0).toUpperCase()+k.slice(1)}InputError`);
                    if (inp) inp.classList.add('is-invalid');
                    if (err) err.textContent = v[0];
                });
            }
        });
    });

    // ----------- Delete Review -----------
    document.getElementById('confirmDeleteReviewBtn').addEventListener('click', function () {
        const id = document.getElementById('deleteReviewId').value;
        fetch(`/admin/reviews/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`reviewRow${id}`).remove();
                deleteModal.hide();
                showToast(data.message, 'success');
            }
        });
    });

});
</script>
@endpush


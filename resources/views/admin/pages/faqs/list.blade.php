@extends("admin.pages.app")

@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('FAQs') }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('Add New FAQ') }}
                </button>
            </div>

            <!-- Toast container -->
            <div class="position-fixed end-0 top-0 p-3" style="z-index: 1100;">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
                     aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">{{ __('Success message') }}</div>
                        <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"
                                aria-label="{{ __('Close') }}"></button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle" id="faqsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width:60px">#</th>
                                    <th>{{ __('Question') }}</th>
                                    <th>{{ __('Answer') }}</th>
                                    {{-- <th>{{ __('Lang') }}</th> --}}
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center" style="width:180px">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faqs as $index => $faq)
                                    <tr id="faqRow{{ $faq->id }}">
                                        <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                        <td class="faq-question">{{ $faq->question }}</td>
                                        <td class="faq-answer">{{ $faq->answer }}</td>
                                        {{-- <td class="faq-lang">{{ $faq->lang }}</td> --}}
                                        <td class="faq-status">{{ $faq->status }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary edit-faq"
                                                    data-id="{{ $faq->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-faq"
                                                    data-id="{{ $faq->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Create FAQ Modal --}}
<div class="modal fade" id="createFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add New FAQ') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createFaqForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Question') }}</label>
                        <input type="text" class="form-control" id="createFaqQuestion" required>
                        <div class="invalid-feedback" id="createFaqQuestionError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Answer') }}</label>
                        <textarea class="form-control" id="createFaqAnswer" rows="3" required></textarea>
                        <div class="invalid-feedback" id="createFaqAnswerError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="createFaqStatus" required>
                            <option value="active" selected>{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit FAQ Modal --}}
<div class="modal fade" id="editFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit FAQ') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editFaqForm">
                    @csrf
                    @method("PUT")
                    <input type="hidden" id="modalFaqId">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Question') }}</label>
                        <input type="text" class="form-control" id="faqQuestionInput" required>
                        <div class="invalid-feedback" id="faqQuestionInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Answer') }}</label>
                        <textarea class="form-control" id="faqAnswerInput" rows="3" required></textarea>
                        <div class="invalid-feedback" id="faqAnswerInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="faqStatusInput" required>
                            <option value="active">{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete FAQ Modal --}}
<div class="modal fade" id="deleteFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this FAQ?') }}</p>
                <input type="hidden" id="deleteFaqId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteFaqBtn">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push("scripts")
<script>
document.addEventListener('DOMContentLoaded', function () {

    const createForm   = document.getElementById('createFaqForm');
    const editForm     = document.getElementById('editFaqForm');
    const createModal  = new bootstrap.Modal(document.getElementById('createFaqModal'));
    const editModal    = new bootstrap.Modal(document.getElementById('editFaqModal'));
    const deleteModal  = new bootstrap.Modal(document.getElementById('deleteFaqModal'));
    const toastEl      = document.getElementById('liveToast');
    const toastMsg     = document.getElementById('toastMessage');
    const toast        = new bootstrap.Toast(toastEl);

    function showToast(message, type = 'success') {
        toastMsg.textContent = message;
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        toast.show();
    }

    createForm.addEventListener('submit', function (e) {
        e.preventDefault();

        ['createFaqQuestion', 'createFaqAnswer', 'createFaqStatus'].forEach(id => {
            document.getElementById(id).classList.remove('is-invalid');
            const err = document.getElementById(id + 'Error');
            if (err) err.textContent = '';
        });

        const question = document.getElementById('createFaqQuestion').value.trim();
        const answer   = document.getElementById('createFaqAnswer').value.trim();
        const status   = document.getElementById('createFaqStatus').value;

        fetch("{{ route('admin.faqs.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question, answer, status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const faq   = data.faq;
                const table = document.querySelector('#faqsTable tbody');
                const index = table.rows.length;
                const row   = document.createElement('tr');
                row.id = `faqRow${faq.id}`;
                row.innerHTML = `
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td class="faq-question">${faq.question}</td>
                    <td class="faq-answer">${faq.answer}</td>
                    <td class="faq-status">${faq.status}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary edit-faq" data-id="${faq.id}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-faq" data-id="${faq.id}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>`;
                table.appendChild(row);
                createModal.hide();
                createForm.reset();
                showToast(data.message, 'success');
            } else if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const inputId  = 'createFaq' + key.charAt(0).toUpperCase() + key.slice(1);
                    const input    = document.getElementById(inputId);
                    const errorDiv = document.getElementById(inputId + 'Error');
                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = data.errors[key][0];
                    }
                });
            }
        });
    });


    document.addEventListener('click', function (e) {


        if (e.target.closest('.edit-faq')) {
            const btn = e.target.closest('.edit-faq');
            const id  = btn.dataset.id;
            const row = document.getElementById(`faqRow${id}`);

            document.getElementById('modalFaqId').value = id;
            document.getElementById('faqQuestionInput').value =
                row.querySelector('.faq-question').textContent;
            document.getElementById('faqAnswerInput').value =
                row.querySelector('.faq-answer').textContent;
            document.getElementById('faqStatusInput').value =
                row.querySelector('.faq-status').textContent;

            editModal.show();
        }

        if (e.target.closest('.delete-faq')) {
            document.getElementById('deleteFaqId').value =
                e.target.closest('.delete-faq').dataset.id;
            deleteModal.show();
        }
    });


    editForm.addEventListener('submit', function (e) {
        e.preventDefault();

        ['faqQuestionInput', 'faqAnswerInput', 'faqStatusInput'].forEach(id => {
            document.getElementById(id).classList.remove('is-invalid');
            const err = document.getElementById(id + 'Error');
            if (err) err.textContent = '';
        });

        const id       = document.getElementById('modalFaqId').value;
        const question = document.getElementById('faqQuestionInput').value.trim();
        const answer   = document.getElementById('faqAnswerInput').value.trim();
        const status   = document.getElementById('faqStatusInput').value;

        fetch(`/admin/faqs/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question, answer, status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`faqRow${id}`);
                row.querySelector('.faq-question').textContent = data.faq.question;
                row.querySelector('.faq-answer').textContent   = data.faq.answer;
                row.querySelector('.faq-status').textContent   = data.faq.status;
                editModal.hide();
                showToast(data.message, 'success');
            } else if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const inputId =
                        key === 'question' ? 'faqQuestionInput' :
                        key === 'answer'   ? 'faqAnswerInput'   :
                                             'faqStatusInput';
                    const input    = document.getElementById(inputId);
                    const errorDiv = document.getElementById(inputId + 'Error');
                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = data.errors[key][0];
                    }
                });
            }
        });
    });


    document.getElementById('confirmDeleteFaqBtn').addEventListener('click', function () {
        const id = document.getElementById('deleteFaqId').value;

        fetch(`/admin/faqs/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`faqRow${id}`).remove();
                deleteModal.hide();
                showToast(data.message, 'success');
            }
        });
    });

});
</script>
@endpush


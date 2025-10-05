@extends("admin.pages.app")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">{{ __("Question & Answer") }}</h4>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAnswerModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __("Add Question & Answer") }}
                    </button>
                </div>

                {{-- Toast Notification --}}
                <div class="position-fixed end-0 top-0 p-3" style="z-index:1100;">
                    <div id="liveToast" class="toast align-items-center text-bg-success border-0">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessage">{{ __("Success") }}</div>
                            <button type="button" class="btn-close btn-close-white m-auto me-2"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="text" id="searchQuestionInput" class="form-control"
                        placeholder="{{ __("Search by question...") }}">
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="answerTable" class="table-bordered table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>{{ __("Question") }}</th>
                                        <th>{{ __("Level") }}</th>
                                        <th>{{ __("Answers") }}</th>
                                        <th>{{ __("Actions") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $q)
                                        <tr id="answerRow{{ $q->id }}">
                                            <td class="fw-bold text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $q->question }}</td>
                                            <td><span class="badge bg-primary">{{ $q->level->name ?? "—" }}</span></td>
                                            <td>
                                                @foreach ($q->answers as $a)
                                                    <div
                                                        class="{{ $q->answer_id === $a->id ? "bg-success text-white" : "bg-light border" }} mb-1 rounded p-2">
                                                        {{ $q->answer_id === $a->id ? "✅ " : "" }}{{ $a->answer }}
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-secondary edit-question"
                                                    data-id="{{ $q->id }}">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-question"
                                                    data-id="{{ $q->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3 px-5">
                                {{ $questions->links("pagination::bootstrap-5") }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== Create Modal ===== --}}
    <div class="modal fade" id="createAnswerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Add Question & Answer") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createAnswerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __("Level") }}</label>
                            <select class="form-select" id="createLevelSelect" required>
                                <option value="">{{ __("Select Level") }}</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __("Question") }}</label>
                            <input type="text" id="createQuestionInput" class="form-control"
                                placeholder="{{ __("Write your question here") }}" required>
                            <div class="invalid-feedback" id="createQuestionInputError"></div>
                        </div>

                        <div class="mb-3" id="createAnswersWrapper">
                            <label class="form-label">{{ __("Answers") }}</label>
                            <div class="answer-item d-flex align-items-center mb-2">
                                <input type="radio" name="correct_answer" class="form-check-input me-2" required>
                                <input type="text" name="answers[]" class="form-control create-answer-input me-2"
                                    required>
                                <button type="button" class="btn btn-outline-danger remove-answer d-none">–</button>
                            </div>
                            <div class="invalid-feedback d-block" id="createAnswersError"></div>
                        </div>

                        <button type="button" id="addCreateAnswer" class="btn btn-outline-secondary btn-sm">+
                            {{ __("Add Another Answer") }}</button>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                            <button type="submit" class="btn btn-success">{{ __("Add") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Edit Modal ===== --}}
    <div class="modal fade" id="editAnswerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Edit Question & Answers") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editAnswerForm">
                        @csrf
                        @method("PUT")
                        <input type="hidden" id="editQuestionId">

                        <div class="mb-3">
                            <label class="form-label">{{ __("Level") }}</label>
                            <select class="form-select" id="editLevelSelect" required>
                                <option value="">{{ __("Select Level") }}</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __("Question") }}</label>
                            <input type="text" id="editQuestionInput" class="form-control" required>
                            <div class="invalid-feedback" id="editQuestionInputError"></div>
                        </div>

                        <div class="mb-3" id="editAnswersWrapper">
                            <label class="form-label">{{ __("Answers") }}</label>
                            <!-- Answers will be loaded dynamically -->
                            <div class="invalid-feedback d-block" id="editAnswersError"></div>
                        </div>

                        <button type="button" id="addEditAnswer" class="btn btn-outline-secondary btn-sm">
                            + {{ __("Add Another Answer") }}
                        </button>

                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                {{ __("Cancel") }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ __("Save Changes") }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Delete Modal ===== --}}
    <div class="modal fade" id="deleteAnswerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">{{ __("Confirm Delete") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __("Delete this answer?") }}</p>
                    <input type="hidden" id="deleteAnswerId">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                    <button type="button" class="btn btn-danger"
                        id="confirmDeleteAnswerBtn">{{ __("Delete") }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const createModal = new bootstrap.Modal(document.getElementById('createAnswerModal'));
            const editModal = new bootstrap.Modal(document.getElementById('editAnswerModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteAnswerModal'));

            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            const toastMsg = document.getElementById('toastMessage');
            const tBody = document.querySelector('#answerTable tbody');

            const showToast = msg => {
                toastMsg.textContent = msg;
                toast.show();
            };

            const updateRowNumbers = () => {
                tBody.querySelectorAll('tr').forEach((tr, i) => {
                    tr.querySelector('td:first-child').textContent = i + 1;
                });
            };

            // ===== Dynamic Answer Fields =====
            function addAnswerField(wrapperId) {
                const wrapper = document.getElementById(wrapperId);
                const div = document.createElement('div');
                div.className = 'answer-item d-flex align-items-center mb-2';
                div.innerHTML = `
            <input type="radio" name="${wrapperId === 'createAnswersWrapper' ? 'correct_answer' : 'edit_correct_answer'}" class="form-check-input me-2" required>
            <input type="text" name="answers[]" class="form-control me-2" required>
            <button type="button" class="btn btn-outline-danger remove-answer">–</button>
        `;
                wrapper.appendChild(div);
            }

            document.getElementById('addCreateAnswer').addEventListener('click', () => addAnswerField(
                'createAnswersWrapper'));
            document.getElementById('addEditAnswer').addEventListener('click', () => addAnswerField(
                'editAnswersWrapper'));

            document.addEventListener('click', e => {
                if (e.target.classList.contains('remove-answer')) {
                    e.target.closest('.answer-item').remove();
                }
            });

            // ===== Create Question =====
            document.getElementById('createAnswerForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const levelId = document.getElementById('createLevelSelect').value;
                const questionText = document.getElementById('createQuestionInput').value.trim();
                const answerItems = document.querySelectorAll('#createAnswersWrapper .answer-item');
                const answers = [];
                let correctIndex = -1;

                answerItems.forEach((item, index) => {
                    const input = item.querySelector('input[name="answers[]"]');
                    const radio = item.querySelector('input[type="radio"]');
                    if (input.value.trim()) answers.push(input.value.trim());
                    if (radio.checked) correctIndex = index;
                });

                if (!questionText) return document.getElementById('createQuestionInputError').textContent =
                    "{{ __("Question is required") }}";
                if (answers.length < 2) return document.getElementById('createAnswersError').textContent =
                    "{{ __("At least two answers are required") }}";
                if (correctIndex === -1) return document.getElementById('createAnswersError').textContent =
                    "{{ __("Please select the correct answer") }}";

                const formData = new FormData();
                formData.append('level_id', levelId);
                formData.append('question_text', questionText);
                formData.append('correct_answer', correctIndex);
                answers.forEach(a => formData.append('answers[]', a));

                fetch("{{ route("admin.questions.store") }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            createModal.hide();
                            showToast("{{ __("Question and answers added successfully ✅") }}");

                            let answersHtml = '';
                            data.answers.forEach(a => {
                                answersHtml +=
                                    `<div class="p-2 mb-1 rounded ${a.id === data.correct_answer_id ? 'bg-success text-white' : 'bg-light border'}">${a.id === data.correct_answer_id ? '✅ ' : ''}${a.answer}</div>`;
                            });

                            tBody.insertAdjacentHTML('afterbegin', `
                    <tr id="answerRow${data.question.id}">
                        <td class="text-center fw-bold"></td>
                        <td class="fw-semibold">${data.question.question}</td>
                        <td><span class="badge bg-primary">${data.question.level.name ?? '—'}</span></td>
                        <td>${answersHtml}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary edit-question" data-id="${data.question.id}"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger delete-question" data-id="${data.question.id}"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                `);
                            updateRowNumbers();

                            this.reset();
                            document.getElementById('createAnswersWrapper').innerHTML = `
                    <div class="answer-item d-flex align-items-center mb-2">
                        <input type="radio" name="correct_answer" class="form-check-input me-2" required>
                        <input type="text" name="answers[]" class="form-control me-2" required>
                        <button type="button" class="btn btn-outline-danger remove-answer d-none">–</button>
                    </div>`;
                        }
                    }).catch(err => alert("{{ __("Server error occurred") }}"));
            });

            // ===== Open Edit Modal =====
            document.addEventListener('click', e => {
                const btn = e.target.closest('.edit-question');
                if (!btn) return;
                const questionId = btn.dataset.id;

                fetch(`/admin/questions/${questionId}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('editQuestionId').value = data.question.id;
                        document.getElementById('editQuestionInput').value = data.question.question;
                        document.getElementById('editLevelSelect').value = data.question.level_id;

                        const wrapper = document.getElementById('editAnswersWrapper');
                        wrapper.innerHTML = '';
                        data.answers.forEach(a => {
                            const div = document.createElement('div');
                            div.className = 'answer-item d-flex align-items-center mb-2';
                            div.dataset.id = a.id;
                            div.innerHTML = `
                        <input type="radio" name="edit_correct_answer" class="form-check-input me-2" ${a.id == data.question.answer_id ? 'checked' : ''} required>
                        <input type="text" name="answers[]" class="form-control me-2" value="${a.answer}" required>
                        <button type="button" class="btn btn-outline-danger remove-answer">–</button>
                    `;
                            wrapper.appendChild(div);
                        });

                        editModal.show();
                    });
            });

            // ===== Submit Edit =====
            document.getElementById('editAnswerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const questionId = document.getElementById('editQuestionId').value;
                const levelId = document.getElementById('editLevelSelect').value;
                const questionText = document.getElementById('editQuestionInput').value.trim();
                const answerItems = document.querySelectorAll('#editAnswersWrapper .answer-item');
                const answers = [];
                let correctIndex = -1;

                if (answerItems.length < 2) return document.getElementById('editAnswersError').textContent =
                    "{{ __("At least two answers are required") }}";

                answerItems.forEach((item, i) => {
                    const input = item.querySelector('input[name="answers[]"]');
                    const radio = item.querySelector('input[type="radio"]');
                    const answerId = item.dataset.id || null;
                    if (input.value.trim()) answers.push({
                        id: answerId,
                        answer: input.value.trim()
                    });
                    if (radio.checked) correctIndex = i;
                });

                if (correctIndex === -1) return document.getElementById('editAnswersError').textContent =
                    "{{ __("Please select the correct answer") }}";

                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('level_id', levelId);
                formData.append('question_text', questionText);
                formData.append('correct_answer_index', correctIndex);

                answers.forEach((a, i) => {
                    formData.append(`answers[${i}][answer]`, a.answer);
                    if (a.id) formData.append(`answers[${i}][id]`, a.id);
                });

                fetch(`/admin/questions/${questionId}`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            editModal.hide();
                            showToast("{{ __("Question and answers updated successfully ✅") }}");

                            // Update row without reload
                            fetch(`/admin/questions/${questionId}/edit`)
                                .then(res => res.json())
                                .then(updated => {
                                    const row = document.querySelector(`#answerRow${questionId}`);
                                    if (!row) return;

                                    let answersHtml = '';
                                    updated.answers.forEach(a => {
                                        answersHtml +=
                                            `<div class="p-2 mb-1 rounded ${a.id === updated.question.answer_id ? 'bg-success text-white' : 'bg-light border'}">${a.id === updated.question.answer_id ? '✅ ' : ''}${a.answer}</div>`;
                                    });

                                    row.innerHTML = `
                            <td class="text-center fw-bold"></td>
                            <td class="fw-semibold">${updated.question.question}</td>
                            <td><span class="badge bg-primary">${updated.question.level.name ?? '—'}</span></td>
                            <td>${answersHtml}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary edit-question" data-id="${updated.question.id}"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn btn-sm btn-outline-danger delete-question" data-id="${updated.question.id}"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        `;
                                    updateRowNumbers();
                                });
                        }
                    })
                    .catch(err => alert("{{ __("Server error occurred") }}"));
            });
            document.getElementById('searchQuestionInput').addEventListener('keyup', function() {
                const q = this.value;

                fetch(`/admin/questions/search?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        tBody.innerHTML = '';
                        data.data.forEach((qItem, i) => {
                            let answersHtml = '';
                            qItem.answers.forEach(a => {
                                answersHtml +=
                                    `<div class="p-2 mb-1 rounded ${a.id === qItem.answer_id ? 'bg-success text-white' : 'bg-light border'}">${a.id === qItem.answer_id ? '✅ ' : ''}${a.answer}</div>`;
                            });

                            tBody.insertAdjacentHTML('beforeend', `
                    <tr id="answerRow${qItem.id}">
                        <td class="text-center fw-bold">${i + 1}</td>
                        <td class="fw-semibold">${qItem.question}</td>
                        <td><span class="badge bg-primary">${qItem.level?.name ?? '—'}</span></td>
                        <td>${answersHtml}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary edit-question" data-id="${qItem.id}"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger delete-question" data-id="${qItem.id}"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                `);
                        });
                    });
            });

            // ===== Delete Question =====
            document.addEventListener('click', e => {
                const btn = e.target.closest('.delete-question');
                if (!btn) return;
                document.getElementById('deleteAnswerId').value = btn.dataset.id;
                deleteModal.show();
            });

            document.getElementById('confirmDeleteAnswerBtn').addEventListener('click', () => {
                const id = document.getElementById('deleteAnswerId').value;
                fetch(`/admin/questions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`#answerRow${id}`)?.remove();
                            deleteModal.hide();
                            showToast("{{ __("Question deleted successfully ✅") }}");
                            updateRowNumbers();
                        }
                    })
                    .catch(err => alert("{{ __("Server error occurred") }}"));
            });
        });
    </script>
@endpush

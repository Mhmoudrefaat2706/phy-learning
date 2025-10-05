@extends("admin.pages.app")
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __("Projects") }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __("Add New Project") }}
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
                        <table class="table-hover mb-0 table align-middle" id="ProjectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width:60px">#</th>
                                    <th>{{ __("Title") }}</th>
                                    <th>{{ __("Short Description") }}</th>
                                    <th>{{ __("Category") }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th>{{ __("Price") }}</th>
                                    <th>{{ __("Image") }}</th>
                                    <th class="text-center" style="width:180px">{{ __("Actions") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $index => $project)
                                    <tr id="ProjectRow{{ $project->id }}">
                                        <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                        <td class="Project-title">{{ $project->title }}</td>
                                        <td class="Project-short">{{ $project->short_description }}</td>
                                        <td class="Project-category" data-id="{{ $project->category_id }}">
                                            {{ $project->category->name ?? "" }}
                                        </td>
                                        <td class="Project-status">{{ $project->status }}</td>
                                        <td class="Project-price">{{ $project->price }}</td>
                                        <td class="Project-description d-none">{!! $project->description !!}</td>
                                        <td class="Project-image">
                                            @if ($project->image)
                                                <img src="{{ asset("storage/" . $project->image) }}"  width="60" height="60"
                                                        style="object-fit:cover;border-radius:4px;" alt="{{ $project->title }}">
                                            @else
                                                {{ __("No Image") }}
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary edit-Project"
                                                data-id="{{ $project->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-Project"
                                                data-id="{{ $project->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 px-5">
                            {{ $projects->links("pagination::bootstrap-5") }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Add New Course --}}
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Add New Course") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createProjectForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" id="createProjectTitle" required>
                        <div class="invalid-feedback" id="createProjectTitleError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Short Description") }}</label>
                        <input type="text" name="short_description" class="form-control" id="createProjectShortDescription">
                        <div class="invalid-feedback" id="createProjectShortDescriptionError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Category") }}</label>
                        <select class="form-control" name="category_id" id="createProjectCategory" required>
                            @foreach (\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="createProjectCategoryError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Status") }}</label>
                        <select class="form-control" name="status" id="createProjectStatus" required>
                            <option value="active">{{ __("Active") }}</option>
                            <option value="inactive">{{ __("Inactive") }}</option>
                        </select>
                        <div class="invalid-feedback" id="createProjectStatusError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" >{{ __("Price") }}</label>
                        <input type="number" name="price" class="form-control" id="createProjectPrice">
                        <div class="invalid-feedback" id="createProjectPriceError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Description") }}</label>
                        <textarea class="form-control" name="description" id="createProjectDescription"></textarea>
                        <div class="invalid-feedback" id="createProjectDescriptionError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Image") }}</label>
                        <input type="file" class="form-control" name="image" id="createProjectImage">
                        <div class="invalid-feedback" id="createProjectImageError"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-success">{{ __("Add") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Course --}}
<div class="modal fade" id="editProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Edit Course") }} - <span id="modalProjectTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProjectForm" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <input type="hidden" id="modalProjectId" name="id">
                    <div class="mb-3">
                        <label class="form-label">{{ __("Title") }}</label>
                        <input type="text" class="form-control" id="ProjectTitleInput" name="title" required>
                        <div class="invalid-feedback" id="ProjectTitleInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Short Description") }}</label>
                        <input type="text" class="form-control" name="short_description" id="ProjectShortDescriptionInput">
                        <div class="invalid-feedback" id="ProjectShortDescriptionInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Category") }}</label>
                        <select class="form-control" id="ProjectCategoryInput" name="category_id" required>
                            @foreach (\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="ProjectCategoryInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Status") }}</label>
                        <select class="form-control" id="ProjectStatusInput" name="status" required>
                            <option value="active">{{ __("Active") }}</option>
                            <option value="inactive">{{ __("Inactive") }}</option>
                        </select>
                        <div class="invalid-feedback" id="ProjectStatusInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Price") }}</label>
                        <input type="number" class="form-control" name="price" id="ProjectPriceInput">
                        <div class="invalid-feedback" id="ProjectPriceInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Description") }}</label>
                        <textarea class="form-control" name="description" id="ProjectDescriptionInput"></textarea>
                        <div class="invalid-feedback" id="ProjectDescriptionInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Image") }}</label>
                        <input type="file" class="form-control" name="image" id="ProjectImageInput">
                        <div class="invalid-feedback" id="ProjectImageInputError"></div>
                        <div id="currentImagePreview" class="mt-2"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Course --}}
<div class="modal fade" id="deleteProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __("Delete Confirmation") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __("Are you sure you want to delete") }} <strong id="deleteProjectTitle"></strong>?</p>
                <input type="hidden" id="deleteProjectId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProjectBtn">{{ __("Delete") }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push("scripts")
<script>
document.addEventListener("DOMContentLoaded", function() {
    let createDescriptionEditor, editDescriptionEditor;

    ClassicEditor.create(document.querySelector("#createProjectDescription"))
        .then(editor => createDescriptionEditor = editor);
    ClassicEditor.create(document.querySelector("#ProjectDescriptionInput"))
        .then(editor => editDescriptionEditor = editor);

    const createForm = document.getElementById('createProjectForm');
    const editForm = document.getElementById('editProjectForm');
    const createModal = new bootstrap.Modal('#createProjectModal');
    const editModal = new bootstrap.Modal('#editProjectModal');
    const deleteModal = new bootstrap.Modal('#deleteProjectModal');


            const toastEl = document.getElementById('liveToast');
            const toastMsg = document.getElementById('toastMessage');
            const toast = new bootstrap.Toast(toastEl);

            function showToast(message, type = 'success') {
                toastMsg.textContent = message;
                toastEl.className = `toast align-items-center text-bg-${type} border-0`;
                toast.show();
            }
    createForm.addEventListener('submit', e => {
        e.preventDefault();
        resetValidation(createForm);

        const fd = new FormData();
        fd.append('title', document.getElementById('createProjectTitle').value);
        fd.append('short_description', document.getElementById('createProjectShortDescription').value);
        fd.append('category_id', document.getElementById('createProjectCategory').value);
        fd.append('status', document.getElementById('createProjectStatus').value);
        fd.append('price', document.getElementById('createProjectPrice').value);
        fd.append('description', createDescriptionEditor.getData());
        const img = document.getElementById('createProjectImage').files[0];
        if (img) fd.append('image', img);

        fetch("{{ route('admin.projects.store') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                addProjectRow(data.project);
                createForm.reset();
                createDescriptionEditor.setData('');
                createModal.hide();
                showToast(data.message, 'success');
            } else {
                showErrors('createProject', data.errors);
            }
        });
    });


    editForm.addEventListener('submit', e => {
        e.preventDefault();
        resetValidation(editForm);

        const id = document.getElementById('modalProjectId').value;
        const fd = new FormData(editForm);
        fd.append('description', editDescriptionEditor.getData());
        fd.append('_method', 'PUT');

        fetch(`/admin/projects/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                updateProjectRow(data.project);
                editModal.hide();
                showToast(data.message, 'success');
            } else {
                showErrors('Project', data.errors);
            }
        });
    });


    document.getElementById('confirmDeleteProjectBtn').addEventListener('click', () => {
        const id = document.getElementById('deleteProjectId').value;
        fetch(`/admin/projects/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('ProjectRow' + id).remove();
                deleteModal.hide();
                showToast(data.message, 'success');
            }
        });
    });


    document.addEventListener('click', e => {
        const btn = e.target.closest('.edit-Project');
        if (!btn) return;
        const id = btn.dataset.id;
        const row = document.getElementById('ProjectRow' + id);

        document.getElementById('modalProjectId').value = id;
        document.getElementById('modalProjectTitle').textContent = row.querySelector('.Project-title').textContent;
        document.getElementById('ProjectTitleInput').value = row.querySelector('.Project-title').textContent;
        document.getElementById('ProjectShortDescriptionInput').value = row.querySelector('.Project-short').textContent;
        document.getElementById('ProjectCategoryInput').value = row.querySelector('.Project-category').dataset.id;
        document.getElementById('ProjectStatusInput').value = row.querySelector('.Project-status').textContent.trim();
        document.getElementById('ProjectPriceInput').value = row.querySelector('.Project-price').textContent.trim();
        editDescriptionEditor.setData(row.querySelector('.Project-description').innerHTML);

        const img = row.querySelector('.Project-image img');
        document.getElementById('currentImagePreview').innerHTML = img ? `<img src="${img.src}" width="100">` : '';
        editModal.show();
    });


    document.addEventListener('click', e => {
        const btn = e.target.closest('.delete-Project');
        if (!btn) return;
        document.getElementById('deleteProjectId').value = btn.dataset.id;
        document.getElementById('deleteProjectTitle').textContent = document.querySelector(`#ProjectRow${btn.dataset.id} .Project-title`).textContent;
        deleteModal.show();
    });


    function addProjectRow(project) {
        const tbody = document.querySelector('#ProjectsTable tbody');
        const row = document.createElement('tr');
        row.id = 'ProjectRow' + project.id;
        row.innerHTML = `
            <td class="fw-bold text-center">${tbody.children.length + 1}</td>
            <td class="Project-title">${project.title}</td>
            <td class="Project-short">${project.short_description ?? ''}</td>
            <td class="Project-category" data-id="${project.category_id}">${project.category ? project.category.name : ''}</td>
            <td class="Project-status">${project.status}</td>
            <td class="Project-price">${project.price}</td>
            <td class="Project-description d-none">${project.description ?? ''}</td>
            <td class="Project-image">
                ${project.image ? `<img src="/storage/${project.image}"  width="60" height="60"
                                                        style="object-fit:cover;border-radius:4px;">` : 'no image'}
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-secondary edit-Project" data-id="${project.id}"><i class="bi bi-pencil-fill"></i></button>
                <button class="btn btn-sm btn-outline-danger delete-Project" data-id="${project.id}"><i class="bi bi-trash-fill"></i></button>
            </td>
        `;
        tbody.prepend(row);
    }


    function updateProjectRow(project) {
        const row = document.getElementById('ProjectRow' + project.id);
        row.querySelector('.Project-title').textContent = project.title;
        row.querySelector('.Project-short').textContent = project.short_description ?? '';
        row.querySelector('.Project-category').textContent = project.category ? project.category.name : '';
        row.querySelector('.Project-category').dataset.id = project.category_id;
        row.querySelector('.Project-status').textContent = project.status;
        row.querySelector('.Project-price').textContent = project.price;
        row.querySelector('.Project-description').innerHTML = project.description ?? '';
        row.querySelector('.Project-image').innerHTML = project.image ? `<img src="/storage/${project.image}" width="80">` : 'no image';
    }

    function showErrors(prefix, errors) {
        for (const key in errors) {
            const field = document.getElementById(`${prefix}${capitalize(key)}`);
            const err = document.getElementById(`${prefix}${capitalize(key)}Error`);
            if (field && err) {
                field.classList.add('is-invalid');
                err.textContent = errors[key][0];
            }
        }
    }

    function resetValidation(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
</script>
@endpush


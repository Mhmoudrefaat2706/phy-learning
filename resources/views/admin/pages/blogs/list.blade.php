@extends("admin.pages.app")
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __("Blogs") }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createBlogModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __("Add New Blog") }}
                </button>
            </div>

            {{-- Toast --}}
            <div class="position-fixed end-0 top-0 p-3" style="z-index: 1100;">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">{{ __("Success") }}</div>
                        <button type="button" class="btn-close btn-close-white m-auto me-2"
                            data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle" id="BlogsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width:60px">#</th>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Category") }}</th>
                                    <th>{{ __("Views") }}</th>
                                    <th>{{ __("Tags") }}</th>
                                    <th>{{ __("Keywords") }}</th>
                                    <th>{{ __("Meta Description") }}</th>
                                    <th>{{ __("Image") }}</th>
                                    <th class="text-center" style="width:180px">{{ __("Actions") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $index => $blog)
                                    <tr id="BlogRow{{ $blog->id }}">
                                        <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                        <td class="Blog-name">{{ $blog->name }}</td>
                                        <td class="Blog-category" data-id="{{ $blog->blog_category_id }}">
                                            {{ $blog->category->name ?? "" }}
                                        </td>
                                        <td class="Blog-views">{{ $blog->views }}</td>
                                        <td class="Blog-tags">
                                            @foreach(array_filter(array_map('trim', explode(',', $blog->tags ?? ''))) as $tag)
                                                <span class="badge bg-primary me-1">{{ $tag }}</span>
                                            @endforeach
                                        </td>
                                        <td class="Blog-keywords">
                                            @foreach(array_filter(array_map('trim', explode(',', $blog->keywords ?? ''))) as $keyword)
                                                <span class="badge bg-info me-1">{{ $keyword }}</span>
                                            @endforeach
                                        </td>
                                        <td class="Blog-meta_description">{{ $blog->meta_description }}</td>
                                        <td class="Blog-content d-none">{!! $blog->content !!}</td>
                                        <td class="Blog-image">
                                            @if ($blog->image)
                                                <img src="{{ asset("storage/" . $blog->image) }}" width="60"
                                                    height="60" style="object-fit:cover;border-radius:4px;">
                                            @else
                                                {{ __("No Image") }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary edit-Blog"
                                                data-id="{{ $blog->id }}">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-Blog"
                                                data-id="{{ $blog->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 px-5">
                            {{ $blogs->links("pagination::bootstrap-5") }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Create --}}
<div class="modal fade" id="createBlogModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Add New Blog") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createBlogForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __("Name") }}</label>
                        <input type="text" name="name" class="form-control" id="createBlogName" required>
                        <div class="invalid-feedback" id="createBlogNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Category") }}</label>
                        <select class="form-control" name="blog_category_id" id="createBlogCategory" required>
                            @foreach (\App\Models\BlogCategory::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="createBlogCategoryError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Tags (comma separated)") }}</label>
                        <input type="text" name="tags" class="form-control" id="createBlogTags">
                        <div class="invalid-feedback" id="createBlogTagsError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Keywords (comma separated)") }}</label>
                        <input type="text" name="keywords" class="form-control" id="createBlogKeywords">
                        <div class="invalid-feedback" id="createBlogKeywordsError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Meta Description") }}</label>
                        <input type="text" name="meta_description" class="form-control"
                            id="createBlogMetaDescription">
                        <div class="invalid-feedback" id="createBlogMetaDescriptionError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Content") }}</label>
                        <textarea class="form-control" name="content" id="createBlogContent"></textarea>
                        <div class="invalid-feedback" id="createBlogContentError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Image") }}</label>
                        <input type="file" name="image" class="form-control" id="createBlogImage">
                        <div class="invalid-feedback" id="createBlogImageError"></div>
                    </div>
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

{{-- Edit --}}
<div class="modal fade" id="editBlogModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Edit Blog") }} - <span id="modalBlogName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editBlogForm" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <input type="hidden" id="modalBlogId" name="id">
                    <div class="mb-3">
                        <label class="form-label">{{ __("Name") }}</label>
                        <input type="text" class="form-control" id="BlogNameInput" name="name" required>
                        <div class="invalid-feedback" id="BlogNameInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Category") }}</label>
                        <select class="form-control" id="BlogCategoryInput" name="blog_category_id" required>
                            @foreach (\App\Models\BlogCategory::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="BlogCategoryInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Tags") }}</label>
                        <input type="text" class="form-control" name="tags" id="BlogTagsInput">
                        <div class="invalid-feedback" id="BlogTagsInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Keywords") }}</label>
                        <input type="text" class="form-control" name="keywords" id="BlogKeywordsInput">
                        <div class="invalid-feedback" id="BlogKeywordsInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Meta Description") }}</label>
                        <input type="text" class="form-control" name="meta_description"
                            id="BlogMetaDescriptionInput">
                        <div class="invalid-feedback" id="BlogMetaDescriptionInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Content") }}</label>
                        <textarea class="form-control" name="content" id="BlogContentInput"></textarea>
                        <div class="invalid-feedback" id="BlogContentInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __("Image") }}</label>
                        <input type="file" class="form-control" name="image" id="BlogImageInput">
                        <div class="invalid-feedback" id="BlogImageInputError"></div>
                        <div id="currentBlogImage" class="mt-2"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-primary">{{ __("Save Changes") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteBlogModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __("Delete Confirmation") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __("Are you sure you want to delete") }} <strong id="deleteBlogName"></strong>?</p>
                <input type="hidden" id="deleteBlogId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBlogBtn">{{ __("Delete") }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push("scripts")
   <script>
document.addEventListener("DOMContentLoaded", function() {
    let createEditor, editEditor;
    ClassicEditor.create(document.querySelector("#createBlogContent")).then(e => createEditor = e);
    ClassicEditor.create(document.querySelector("#BlogContentInput")).then(e => editEditor = e);

    const createForm = document.getElementById('createBlogForm');
    const editForm = document.getElementById('editBlogForm');
    const createModal = new bootstrap.Modal('#createBlogModal');
    const editModal = new bootstrap.Modal('#editBlogModal');
    const deleteModal = new bootstrap.Modal('#deleteBlogModal');

    const toastEl = document.getElementById('liveToast');
    const toastMsg = document.getElementById('toastMessage');
    const toast = new bootstrap.Toast(toastEl);
    const showToast = (msg, type = 'success') => {
        toastMsg.textContent = msg;
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        toast.show();
    };

    createForm.addEventListener('submit', e => {
        e.preventDefault();
        resetValidation(createForm);
        const fd = new FormData(createForm);
        fd.set('content', createEditor.getData());

        fetch("{{ route('admin.blogs.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: fd
        }).then(r => r.json()).then(data => {
            if (data.success) {
                addBlogRow(data.blog);
                createForm.reset();
                createEditor.setData('');
                createModal.hide();
                showToast(data.message);
            } else showErrors('createBlog', data.errors);
        });
    });

    editForm.addEventListener('submit', e => {
        e.preventDefault();
        resetValidation(editForm);
        const id = document.getElementById('modalBlogId').value;
        const fd = new FormData(editForm);
        fd.set('content', editEditor.getData());
        fd.append('_method', 'PUT');

        fetch(`/admin/blogs/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: fd
        }).then(r => r.json()).then(data => {
            if (data.success) {
                updateBlogRow(data.blog);
                editModal.hide();
                showToast(data.message);
            } else showErrors('Blog', data.errors);
        });
    });

    document.getElementById('confirmDeleteBlogBtn').addEventListener('click', () => {
        const id = document.getElementById('deleteBlogId').value;
        fetch(`/admin/blogs/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            if (data.success) {
                document.getElementById('BlogRow' + id).remove();
                deleteModal.hide();
                showToast(data.message);
            }
        });
    });

    document.addEventListener('click', e => {
        const btn = e.target.closest('.edit-Blog');
        if (!btn) return;
        const id = btn.dataset.id;
        const row = document.getElementById('BlogRow' + id);

        document.getElementById('modalBlogId').value = id;
        document.getElementById('modalBlogName').textContent = row.querySelector('.Blog-name').textContent;
        document.getElementById('BlogNameInput').value = row.querySelector('.Blog-name').textContent;
        document.getElementById('BlogCategoryInput').value = row.querySelector('.Blog-category').dataset.id;

        // Tags
        document.getElementById('BlogTagsInput').value = Array.from(
            row.querySelectorAll('.Blog-tags .badge')
        ).map(el => el.textContent.trim()).join(', ');

        // Keywords
        document.getElementById('BlogKeywordsInput').value = Array.from(
            row.querySelectorAll('.Blog-keywords .badge')
        ).map(el => el.textContent.trim()).join(', ');

        document.getElementById('BlogMetaDescriptionInput').value = row.querySelector('.Blog-meta_description').textContent;
        editEditor.setData(row.querySelector('.Blog-content').innerHTML);

        const img = row.querySelector('.Blog-image img');
        document.getElementById('currentBlogImage').innerHTML = img ? `<img src="${img.src}" width="100">` : '';

        editModal.show();
    });

    document.addEventListener('click', e => {
        const btn = e.target.closest('.delete-Blog');
        if (!btn) return;
        document.getElementById('deleteBlogId').value = btn.dataset.id;
        document.getElementById('deleteBlogName').textContent = document.querySelector(`#BlogRow${btn.dataset.id} .Blog-name`).textContent;
        deleteModal.show();
    });

    function addBlogRow(blog) {
        const tbody = document.querySelector('#BlogsTable tbody');
        const row = document.createElement('tr');
        row.id = 'BlogRow' + blog.id;
        row.innerHTML = `
            <td class="fw-bold text-center">${tbody.children.length+1}</td>
            <td class="Blog-name">${blog.name}</td>
            <td class="Blog-category" data-id="${blog.blog_category_id}">${blog.category?blog.category.name:''}</td>
            <td class="Blog-views">${blog.views}</td>
            <td class="Blog-tags">
                ${blog.tags ? blog.tags.split(',').map(t => t.trim()).filter(t => t).map(t => `<span class="badge bg-primary me-1">${t}</span>`).join('') : ''}
            </td>
            <td class="Blog-keywords">
                ${blog.keywords ? blog.keywords.split(',').map(k => k.trim()).filter(k => k).map(k => `<span class="badge bg-info me-1">${k}</span>`).join('') : ''}
            </td>
            <td class="Blog-meta_description">${blog.meta_description ?? ''}</td>
            <td class="Blog-content d-none">${blog.content ?? ''}</td>
            <td class="Blog-image">${blog.image ? `<img src="/storage/${blog.image}" width="60" height="60" style="object-fit:cover;border-radius:4px;">` : 'No Image'}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-secondary edit-Blog" data-id="${blog.id}"><i class="bi bi-pencil-fill"></i></button>
                <button class="btn btn-sm btn-outline-danger delete-Blog" data-id="${blog.id}"><i class="bi bi-trash-fill"></i></button>
            </td>`;
        tbody.prepend(row);
    }

    function updateBlogRow(blog) {
        const row = document.getElementById('BlogRow' + blog.id);
        row.querySelector('.Blog-name').textContent = blog.name;
        row.querySelector('.Blog-category').dataset.id = blog.blog_category_id;
        row.querySelector('.Blog-category').textContent = blog.category ? blog.category.name : '';
        row.querySelector('.Blog-views').textContent = blog.views;

        row.querySelector('.Blog-tags').innerHTML = blog.tags
            ? blog.tags.split(',').map(t => t.trim()).filter(t => t).map(t => `<span class="badge bg-primary me-1">${t}</span>`).join('')
            : '';

        row.querySelector('.Blog-keywords').innerHTML = blog.keywords
            ? blog.keywords.split(',').map(k => k.trim()).filter(k => k).map(k => `<span class="badge bg-info me-1">${k}</span>`).join('')
            : '';

        row.querySelector('.Blog-meta_description').textContent = blog.meta_description ?? '';
        row.querySelector('.Blog-content').innerHTML = blog.content ?? '';
        row.querySelector('.Blog-image').innerHTML = blog.image
            ? `<img src="/storage/${blog.image}" width="60" height="60" style="object-fit:cover;border-radius:4px;">`
            : 'No Image';
    }

    function showErrors(prefix, errors) {
        for (const k in errors) {
            const f = document.getElementById(`${prefix}${capitalize(k)}`);
            const e = document.getElementById(`${prefix}${capitalize(k)}Error`);
            if (f && e) {
                f.classList.add('is-invalid');
                e.textContent = errors[k][0];
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

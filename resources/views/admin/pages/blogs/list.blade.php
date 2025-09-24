@extends("admin.pages.app")

@section("title", "Manage Blogs")

@section("content")
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">{{ __('blogs') }}</h4>
                    <button class="btn btn-success d-flex align-items-center" data-bs-toggle="modal"
                        data-bs-target="#createBlogModal">
                        <i class="bi bi-plus-lg me-2"></i> {{ __('add new blog') }}
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
                            <table class="table-hover mb-0 table align-middle" id="blogsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('category') }}</th>
                                        <th>{{ __('name') }}</th>
                                        {{-- <th>{{ __('slug') }}</th> --}}
                                        <th class="d-none">{{ __('content') }}</th>
                                        <th>{{ __('tags') }}</th>
                                        <th>{{ __('keywords') }}</th>
                                        <th>{{ __('meta_description') }}</th>
                                        <th>{{ __('views') }}</th>
                                        <th>{{ __('image') }}</th>
                                        <th class="text-center">{{ __('actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($blogs as $index => $blog)
                                        <tr id="blog-{{ $blog->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $blog->blogCategory->name ?? "" }}</td>
                                            <td>{{ $blog->name }}</td>
                                            {{-- <td>{{ $blog->slug }}</td> --}}
                                            <td class="course-body d-none">{!! $blog->content !!}</td>
                                            <td>
                                                @if (is_array($blog->tags))
                                                    @foreach ($blog->tags as $t)
                                                        <span class="badge bg-info me-1">{{ $t }}</span>
                                                    @endforeach
                                                @else
                                                    {{ $blog->tags }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (is_array($blog->keywords))
                                                    @foreach ($blog->keywords as $k)
                                                        <span class="badge bg-primary me-1">{{ $k }}</span>
                                                    @endforeach
                                                @else
                                                    {{ $blog->keywords }}
                                                @endif
                                            </td>
                                            <td>{{ \Illuminate\Support\Str::limit($blog->meta_description, 50) }}</td>
                                            <td>{{ $blog->views }}</td>
                                            <td>
                                                @if ($blog->image)
                                                    <img src="{{ asset("storage/" . $blog->image) }}" width="60" alt="{{ $blog->name }}">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-warning btn-sm edit-blog"
                                                    data-id="{{ $blog->id }}" title="{{ __('edit') }}" aria-label="{{ __('edit_blog') }} {{ $blog->name }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm delete-blog"
                                                    data-id="{{ $blog->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteBlogModal" title="{{ __('delete') }}" aria-label="{{ __('delete_blog') }} {{ $blog->name }}">
                                                    <i class="bi bi-trash"></i>
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

    <!-- Modal Create -->
    <div class="modal fade" id="createBlogModal" tabindex="-1" aria-labelledby="createBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBlogModalLabel">{{ __('add_blog') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('close') }}"></button>
                </div>
                <form id="createBlogForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label for="create_blog_category_id" class="form-label">{{ __('category') }}</label>
                            <select name="blog_category_id" id="create_blog_category_id" class="form-control" required autocomplete="off">
                                <option value="">{{ __('choose_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="create_name" class="form-label">{{ __('name') }}</label>
                            <input type="text" name="name" id="create_name" class="form-control" placeholder="{{ __('name') }}" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>


                        <div class="col-12">
                            <label for="create_content" class="form-label">{{ __('content') }}</label>
                            <textarea name="content" id="create_content" class="form-control" placeholder="{{ __('content') }}" autocomplete="off"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="create_tags" class="form-label">{{ __('tags') }}</label>
                            <select name="tags[]" id="create_tags" class="form-control" multiple="multiple" autocomplete="off"></select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="create_keywords" class="form-label">{{ __('keywords') }}</label>
                            <select name="keywords[]" id="create_keywords" class="form-control" multiple="multiple" autocomplete="off"></select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <label for="create_meta_description" class="form-label">{{ __('meta_description') }}</label>
                            <textarea name="meta_description" id="create_meta_description" class="form-control" autocomplete="off"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <label for="create_image" class="form-label">{{ __('image') }}</label>
                            <input type="file" name="image" id="create_image" class="form-control" accept="image/*" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editBlogModal" tabindex="-1" aria-labelledby="editBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBlogModalLabel">{{ __('edit_blog') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('close') }}"></button>
                </div>
                <form id="editBlogForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_blog_id">
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label for="edit_blog_category_id" class="form-label">{{ __('category') }}</label>
                            <select name="blog_category_id" id="edit_blog_category_id" class="form-control" required autocomplete="off">
                                <option value="" id="edit_category_default">-- {{ __('choose_category') }} --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" id="edit_category_{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="edit_name" class="form-label">{{ __('name') }}</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required autocomplete="off">
                        </div>

                        <div class="col-12">
                            <label for="edit_content" class="form-label">{{ __('content') }}</label>
                            <textarea name="content" id="edit_content" class="form-control" required autocomplete="off"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_tags" class="form-label">{{ __('tags') }}</label>
                            <select name="tags[]" id="edit_tags" class="form-control" multiple="multiple" autocomplete="off">

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_keywords" class="form-label">{{ __('keywords') }}</label>
                            <select name="keywords[]" id="edit_keywords" class="form-control" multiple="multiple" autocomplete="off">
                            
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="edit_meta_description" class="form-label">{{ __('meta_description') }}</label>
                            <textarea name="meta_description" id="edit_meta_description" class="form-control" autocomplete="off" placeholder="{{ __('meta_description') }}"></textarea>
                        </div>

                        <div class="col-12">
                            <label for="edit_image" class="form-label">{{ __('image') }}</label>
                            <input type="file" name="image" id="edit_image" class="form-control" accept="image/*" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteBlogModal" tabindex="-1" aria-labelledby="deleteBlogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteBlogModalLabel">{{ __('confirm_delete') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('close') }}"></button>
            </div>

            <div class="modal-body">
                <p>{{ __('delete_confirmation') }} <strong id="deleteReviewName"></strong>؟</p>
                <input type="hidden" id="deleteReviewId">
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBlog">{{ __('delete') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push("scripts")
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor
    let createContentEditor, editContentEditor;
    ClassicEditor.create(document.querySelector("#create_content"))
        .then(editor => createContentEditor = editor)
        .catch(err => console.error(err));

    ClassicEditor.create(document.querySelector("#edit_content"))
        .then(editor => editContentEditor = editor)
        .catch(err => console.error(err));

    // Initialize Select2
    $('#create_tags, #create_keywords, #edit_tags, #edit_keywords').select2({
        tags: true,
        tokenSeparators: [',', ' '],
        placeholder: "Type and press enter",
        width: '100%'
    });

    // Initialize Modals
    const createModal = new bootstrap.Modal('#createBlogModal');
    const editModal = new bootstrap.Modal('#editBlogModal');
    const deleteModal = new bootstrap.Modal('#deleteBlogModal');

    const createForm = document.getElementById('createBlogForm');
    const editForm = document.getElementById('editBlogForm');

    // =========== إضافة مدونة ===========
    createForm.addEventListener('submit', function(e) {
        e.preventDefault();
        resetValidation(createForm);

        let formData = new FormData();
        formData.append('name', document.getElementById('create_name').value);
        formData.append('blog_category_id', document.getElementById('create_blog_category_id').value);
        formData.append('content', createContentEditor.getData());
        formData.append('meta_description', document.getElementById('create_meta_description').value);

        let tags = $('#create_tags').val() || [];
        let keywords = $('#create_keywords').val() || [];

        tags.forEach(tag => formData.append('tags[]', tag));
        keywords.forEach(keyword => formData.append('keywords[]', keyword));

        const imageFile = document.getElementById('create_image').files[0];
        if (imageFile) formData.append('image', imageFile);

        fetch("{{ route('admin.blogs.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else if (data.errors) {
                    showErrors('create_', data.errors);
                }
            })
            .catch(error => {
                console.error('Create error:', error);
                alert('حدث خطأ أثناء الإضافة');
            });
    });

    // =========== تعبئة بيانات التعديل ===========
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-blog');
        if (!btn) return;

        const id = btn.dataset.id;
        console.log('Editing blog ID:', id);

        // استخدم نفس طريقة الـ courses - جلب البيانات من الـ server
        fetch(`/admin/blogs/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(res => {
                console.log('Blog data received:', res);

                if (!res.success || !res.blog) {
                    throw new Error('Invalid response format');
                }

                let blog = res.blog;

                // تعبئة البيانات
                document.getElementById('edit_blog_id').value = blog.id;
                document.getElementById('edit_blog_category_id').value = blog.blog_category_id;
                document.getElementById('edit_name').value = blog.name || '';

                document.getElementById('edit_meta_description').value = blog.meta_description || '';

                // Wait for CKEditor to be ready
                if (editContentEditor) {
                    editContentEditor.setData(blog.content || '');
                } else {
                    console.warn('Edit content editor not ready');
                }

                // Populate Select2 fields
                populateSelect2('#edit_tags', blog.tags);
                populateSelect2('#edit_keywords', blog.keywords);

                // Show modal
                editModal.show();
            })
            .catch(error => {
                console.error('Error fetching blog data:', error);
                alert('خطأ في جلب بيانات المدونة: ' + error.message);
            });
    });

    // =========== حفظ التعديلات ===========
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        resetValidation(editForm);

        console.log('Edit form submitted');

        let id = document.getElementById('edit_blog_id').value;
        let url = `/admin/blogs/${id}`;

        let formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('blog_category_id', document.getElementById('edit_blog_category_id').value);
        formData.append('name', document.getElementById('edit_name').value);

        formData.append('meta_description', document.getElementById('edit_meta_description').value);

        if (editContentEditor) {
            formData.append('content', editContentEditor.getData());
        }

        // Get Select2 values
        let tags = $('#edit_tags').val() || [];
        let keywords = $('#edit_keywords').val() || [];

        tags.forEach(tag => formData.append('tags[]', tag));
        keywords.forEach(keyword => formData.append('keywords[]', keyword));

        // Handle image file
        const imageFile = document.getElementById('edit_image').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        console.log('Sending update request to:', url);

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    editModal.hide();
                    location.reload();
                } else if (data.errors) {
                    showErrors('edit_', data.errors);
                } else {
                    alert('حدث خطأ أثناء التحديث: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('حدث خطأ أثناء الاتصال بالخادم: ' + error.message);
            });
    });

    // =========== حذف المدونة ===========
    let deleteId = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-blog');
        if (!btn) return;

        deleteId = btn.dataset.id;
        const blogName = document.querySelector(`#blog-${deleteId} td:nth-child(3)`).textContent;
        document.querySelector('#deleteBlogModal .modal-body').innerHTML =
            `هل أنت متأكد أنك تريد حذف المدونة "<strong>${blogName}</strong>"؟`;

        deleteModal.show();
    });

    document.getElementById('confirmDeleteBlog').addEventListener('click', function() {
        if (!deleteId) return;

        fetch(`/admin/blogs/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('blog-' + deleteId).remove();
                    deleteModal.hide();
                } else {
                    alert('فشل في الحذف: ' + (data.message || 'خطأ غير معروف'));
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('حدث خطأ أثناء الحذف');
            });
    });

    // =========== Helper Functions ===========
    function populateSelect2(fieldId, values) {
        $(fieldId).empty();
        if (values && Array.isArray(values)) {
            values.forEach(val => {
                let option = new Option(val, val, true, true);
                $(fieldId).append(option);
            });
        }
        $(fieldId).trigger('change');
    }

    function showErrors(prefix, errors) {
        for (const key in errors) {
            const field = document.getElementById(`${prefix}${key}`);
            if (field) {
                field.classList.add('is-invalid');
                // Add error message if you have error divs
                const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.textContent = errors[key][0];
                }
            }
        }
    }

    function resetValidation(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }
});
    </script>
@endpush

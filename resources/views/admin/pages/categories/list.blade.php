@extends('admin.pages.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">{{ __('Categories') }}</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
          <i class="bi bi-plus-lg me-1"></i> {{ __('Create New Category') }}
        </button>
      </div>

      <!-- Toast container -->
      <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
          <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex">
                  <div class="toast-body" id="toastMessage">{{ __('Success message') }}</div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
              </div>
          </div>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="categoriesTable">
              <thead class="table-light">
                <tr>
                  <th class="text-center" style="width:60px">#</th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Description') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Image') }}</th>
                  <th class="text-center" style="width:180px">{{ __('Actions') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categories as $index => $category)
                  <tr id="categoryRow{{ $category->id }}">
                    <td class="text-center fw-bold">{{ $index+1 }}</td>
                    <td class="category-name">{{ $category->name }}</td>
                    <td class="category-description">{{ $category->description }}</td>
                    <td class="category-status text-center" data-status="{{ $category->status }}">
                      <span class="badge bg-{{ $category->status=='active'?'success':'secondary' }}">
                        {{ ucfirst($category->status) }}
                      </span>
                    </td>
                    <td class="category-image">
                      @if($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}" width="50" />
                      @endif
                        <td class="text-center">
                      <button class="btn btn-sm btn-outline-secondary edit-category" data-id="{{ $category->id }}">
                        <i class="bi bi-pencil-fill"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger delete-category" data-id="{{ $category->id }}">
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
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Create New Category') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
      </div>
      <div class="modal-body">
        <form id="createCategoryForm" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="createCategoryName" name="name" required>
            <div class="invalid-feedback" id="createCategoryNameError"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="createCategoryDescription" name="description"></textarea>
            <div class="invalid-feedback" id="createCategoryDescriptionError"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select class="form-select" id="createCategoryStatus" name="status" required>
              <option value="active">{{ __('Active') }}</option>
              <option value="inactive">{{ __('Inactive') }}</option>
            </select>
            <div class="invalid-feedback" id="createCategoryStatusError"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Image') }}</label>
            <input type="file" class="form-control" id="createCategoryImage" name="image" accept="image/*">
            <div class="invalid-feedback" id="createCategoryImageError"></div>
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

{{-- Edit Modal --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('Edit Category') }} - <span id="modalCategoryName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
      </div>
      <div class="modal-body">
        <form id="editCategoryForm" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" id="modalCategoryId">

          <div class="mb-3">
            <label class="form-label">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="categoryNameInput" name="name" required>
            <div class="invalid-feedback" id="categoryNameInputError"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="categoryDescriptionInput" name="description"></textarea>
            <div class="invalid-feedback" id="categoryDescriptionInputError"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select class="form-select" id="categoryStatusInput" name="status" required>
              <option value="active">{{ __('Active') }}</option>
              <option value="inactive">{{ __('Inactive') }}</option>
            </select>
            <div class="invalid-feedback" id="categoryStatusInputError"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">{{ __('Image (Optional)') }}</label>
            <input type="file" class="form-control" id="categoryImageInput" name="image" accept="image/*">
            <div class="invalid-feedback" id="categoryImageInputError"></div>
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

{{-- Delete Modal --}}
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header border-0">
        <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
      </div>
      <div class="modal-body">
        <p>{{ __('Are you sure you want to delete') }} <strong id="deleteCategoryName"></strong>?</p>
        <input type="hidden" id="deleteCategoryId">
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteCategoryBtn">{{ __('Delete') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const createForm = document.getElementById('createCategoryForm');
  const editForm = document.getElementById('editCategoryForm');
  const createModal = new bootstrap.Modal(document.getElementById('createCategoryModal'));
  const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
  const toastEl = document.getElementById('liveToast');
  const toastMsg = document.getElementById('toastMessage');
  const toast = new bootstrap.Toast(toastEl);

  function showToast(message, type='success'){
      toastMsg.textContent = message;
      toastEl.className = `toast align-items-center text-bg-${type} border-0`;
      toast.show();
  }

  function showErrors(errors, formType){
    Object.entries(errors).forEach(([key, msgs])=>{
      let map = {
        name: formType=='create'?'createCategoryName':'categoryNameInput',
        description: formType=='create'?'createCategoryDescription':'categoryDescriptionInput',
        status: formType=='create'?'createCategoryStatus':'categoryStatusInput',
        image: formType=='create'?'createCategoryImage':'categoryImageInput'
      };
      const input = document.getElementById(map[key]);
      const errorDiv = document.getElementById(map[key]+'Error');
      if(input && errorDiv){
        input.classList.add('is-invalid');
        errorDiv.textContent = msgs[0];
      }
    });
  }

  // Create
  createForm.addEventListener('submit', function(e){
    e.preventDefault();
        ['Name','Description','Status','Image'].forEach(f=>{
      document.getElementById('createCategory'+f).classList.remove('is-invalid');
      document.getElementById('createCategory'+f+'Error').textContent='';
    });

    const fd = new FormData();
    fd.append('name', document.getElementById('createCategoryName').value.trim());
    fd.append('description', document.getElementById('createCategoryDescription').value.trim());
    fd.append('status', document.getElementById('createCategoryStatus').value);
    const img = document.getElementById('createCategoryImage').files[0];
    if(img) fd.append('image', img);

    fetch("{{ route('admin.categories.store') }}", {
      method:'POST',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body: fd
    }).then(res=>res.json()).then(data=>{
      if(data.success){
        const cat = data.category;
        const table = document.querySelector('#categoriesTable tbody');
        const row = document.createElement('tr');
        row.id = `categoryRow${cat.id}`;
        row.innerHTML = `
          <td class="text-center fw-bold">${table.rows.length+1}</td>
          <td class="category-name">${cat.name}</td>
          <td class="category-description">${cat.description}</td>
          <td class="category-status text-center" data-status="${cat.status}">
            <span class="badge bg-${cat.status=='active'?'success':'secondary'}">${cat.status.charAt(0).toUpperCase()+cat.status.slice(1)}</span>
          </td>
          <td class="category-image">${cat.image?`<img src="/storage/${cat.image}" width="50"/>`:''}</td>
          <td class="text-center">
            <button class="btn btn-sm btn-outline-secondary edit-category" data-id="${cat.id}">
              <i class="bi bi-pencil-fill"></i> {{ __('Edit') }}
            </button>
            <button class="btn btn-sm btn-outline-danger delete-category" data-id="${cat.id}">
              <i class="bi bi-trash-fill"></i> {{ __('Delete') }}
            </button>
          </td>`;
        table.appendChild(row);
        createModal.hide();
        createForm.reset();
        showToast(data.message, 'success');
      } else if(data.errors){
        showErrors(data.errors, 'create');
      }
    });
  });

  // Show edit & delete modal
  document.addEventListener('click', e => {
    if(e.target.closest('.edit-category')){
      const id = e.target.closest('.edit-category').dataset.id;
      const row = document.getElementById(`categoryRow${id}`);
      document.getElementById('modalCategoryId').value = id;
      document.getElementById('modalCategoryName').textContent = row.querySelector('.category-name').textContent;
      document.getElementById('categoryNameInput').value = row.querySelector('.category-name').textContent;
      document.getElementById('categoryDescriptionInput').value = row.querySelector('.category-description').textContent;
      document.getElementById('categoryStatusInput').value = row.querySelector('.category-status').dataset.status;

      editModal.show();
    }

    if(e.target.closest('.delete-category')){
      const id = e.target.closest('.delete-category').dataset.id;
      const row = document.getElementById(`categoryRow${id}`);
      document.getElementById('deleteCategoryId').value = id;
      document.getElementById('deleteCategoryName').textContent = row.querySelector('.category-name').textContent;
      deleteModal.show();
    }
  });

  // Update
  editForm.addEventListener('submit', function(e){
    e.preventDefault();
    ['categoryNameInput','categoryDescriptionInput','categoryStatusInput','categoryImageInput'].forEach(id=>{
      document.getElementById(id).classList.remove('is-invalid');
      document.getElementById(id+'Error').textContent='';
    });

    const id = document.getElementById('modalCategoryId').value;
    const fd = new FormData();
    fd.append('name', document.getElementById('categoryNameInput').value.trim());
    fd.append('description', document.getElementById('categoryDescriptionInput').value.trim());
    fd.append('status', document.getElementById('categoryStatusInput').value);
    const img = document.getElementById('categoryImageInput').files[0];
    if(img) fd.append('image', img);
    fd.append('_method','PUT');

    fetch(`/admin/categories/${id}`,{
      method:'POST',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body: fd
    }).then(res=>res.json()).then(data=>{
      if(data.success){
        const row = document.getElementById(`categoryRow${id}`);
        row.querySelector('.category-name').textContent = data.category.name;
        row.querySelector('.category-description').textContent = data.category.description;
        row.querySelector('.category-status').dataset.status = data.category.status;
        row.querySelector('.category-status').innerHTML = `<span class="badge bg-${data.category.status=='active'?'success':'secondary'}">${data.category.status.charAt(0).toUpperCase()+data.category.status.slice(1)}</span>`;
        if(data.category.image){
          row.querySelector('.category-image').innerHTML = `<img src="/storage/${data.category.image}" width="50"/>`;
        }
        editModal.hide();
        showToast(data.message, 'success');
      } else if(data.errors){
        showErrors(data.errors, 'edit');
      }
    });
  });

  // Delete
  document.getElementById('confirmDeleteCategoryBtn').addEventListener('click', ()=>{
    const id = document.getElementById('deleteCategoryId').value;
    fetch(`/admin/categories/${id}`,{
      method:'DELETE',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(res=>res.json()).then(data=>{
      if(data.success){
        document.getElementById(`categoryRow${id}`).remove();
        deleteModal.hide();
        showToast(data.message, 'success');
      }
    });
  });

});
</script>
@endpush

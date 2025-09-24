@extends('admin.pages.app')
@section('content')
<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
        <h4 class="fw-bold mb-2 mb-md-0">{{ __('Permissions') }}</h4>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
            <i class="bi bi-plus-lg"></i> {{ __('Add Permission') }}
        </button>
    </div>

    <!-- Toast container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    {{ __('Success message') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="permissionsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $index => $permission)
                            <tr id="permissionRow{{ $permission->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td class="perm-name">{{ $permission->name }}</td>
                                <td class="perm-desc">{{ $permission->description }}</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-secondary btn-sm edit-permission" data-id="{{ $permission->id }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-permission" data-id="{{ $permission->id }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3 px-5">
                    {{ $permissions->links("pagination::bootstrap-5") }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createPermissionForm">@csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Permission') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="permName" required>
                        <div class="invalid-feedback" id="permNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" id="permDesc" required></textarea>
                        <div class="invalid-feedback" id="permDescError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success btn-sm">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPermissionForm">@csrf @method('PUT')
                <input type="hidden" id="editPermId">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Permission') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="editPermName" required>
                        <div class="invalid-feedback" id="editPermNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" id="editPermDesc" required></textarea>
                        <div class="invalid-feedback" id="editPermDescError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deletePermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Delete permission') }} <strong id="deletePermName"></strong>?</p>
                <input type="hidden" id="deletePermId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button class="btn btn-danger btn-sm" id="confirmDeletePerm">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const createModal = new bootstrap.Modal(document.getElementById('createPermissionModal'));
    const editModal   = new bootstrap.Modal(document.getElementById('editPermissionModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deletePermissionModal'));
    const toastEl     = document.getElementById('liveToast');
    const toast       = new bootstrap.Toast(toastEl);
    const toastMsg    = document.getElementById('toastMessage');

    function showToast(message, type='success'){
        toastMsg.textContent = message;
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        toast.show();
    }

    function resetErrors(ids){
        ids.forEach(id=>{
            const input = document.getElementById(id);
            const errorDiv = document.getElementById(id+'Error');
            if(input) input.classList.remove('is-invalid');
            if(errorDiv) errorDiv.textContent = '';
        });
    }

    // CREATE
    document.getElementById('createPermissionForm').addEventListener('submit', function(e){
        e.preventDefault();
        resetErrors(['permName','permDesc']);
        fetch("{{ route('admin.permissions.store') }}",{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({
                name: document.getElementById('permName').value.trim(),
                description: document.getElementById('permDesc').value.trim()
            })
        })
        .then(async res=>{
            const data = await res.json();
            if(res.ok){
                const p = data.permission;
                const table = document.querySelector('#permissionsTable tbody');
                table.insertAdjacentHTML('beforeend', `
                    <tr id="permissionRow${p.id}">
                        <td>${table.rows.length+1}</td>
                        <td class="perm-name">${p.name}</td>
                        <td class="perm-desc">${p.description}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-secondary btn-sm edit-permission" data-id="${p.id}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm delete-permission" data-id="${p.id}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </td>
                    </tr>
                `);
                this.reset();
                createModal.hide();
                showToast(data.message, 'success');
            } else if(res.status === 422){
                Object.keys(data.errors).forEach(key=>{
                    const inputId = key==='name'?'permName':'permDesc';
                    const input = document.getElementById(inputId);
                    const errorDiv = document.getElementById(inputId+'Error');
                    input.classList.add('is-invalid');
                    errorDiv.textContent = data.errors[key][0];
                });
            }
        });
    });

    // EDIT
    document.addEventListener('click', function(e){
        if(e.target.closest('.edit-permission')){
            const id = e.target.closest('.edit-permission').dataset.id;
            const row = document.getElementById(`permissionRow${id}`);
            document.getElementById('editPermId').value = id;
            document.getElementById('editPermName').value = row.querySelector('.perm-name').textContent;
            document.getElementById('editPermDesc').value = row.querySelector('.perm-desc').textContent;
            resetErrors(['editPermName','editPermDesc']);
            editModal.show();
        }
        if(e.target.closest('.delete-permission')){
            const id = e.target.closest('.delete-permission').dataset.id;
            const row = document.getElementById(`permissionRow${id}`);
            document.getElementById('deletePermId').value = id;
            document.getElementById('deletePermName').textContent = row.querySelector('.perm-name').textContent;
            deleteModal.show();
        }
    });

    document.getElementById('editPermissionForm').addEventListener('submit', function(e){
        e.preventDefault();
        resetErrors(['editPermName','editPermDesc']);
        const id = document.getElementById('editPermId').value;

        fetch(`/admin/permissions/${id}`,{
            method:'PUT',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({
                name: document.getElementById('editPermName').value.trim(),
                description: document.getElementById('editPermDesc').value.trim()
            })
        })
        .then(async res=>{
            const data = await res.json();
            if(res.ok){
                const p = data.permission;
                const row = document.getElementById(`permissionRow${id}`);
                row.querySelector('.perm-name').textContent = p.name;
                row.querySelector('.perm-desc').textContent = p.description;
                editModal.hide();
                showToast(data.message, 'success');
            } else if(res.status === 422){
                Object.keys(data.errors).forEach(key=>{
                    const inputId = key==='name'?'editPermName':'editPermDesc';
                    const input = document.getElementById(inputId);
                    const errorDiv = document.getElementById(inputId+'Error');
                    input.classList.add('is-invalid');
                    errorDiv.textContent = data.errors[key][0];
                });
            }
        });
    });

    // DELETE
    document.getElementById('confirmDeletePerm').addEventListener('click', function(){
        const id = document.getElementById('deletePermId').value;
        fetch(`/admin/permissions/${id}`,{
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                document.getElementById(`permissionRow${id}`).remove();
                deleteModal.hide();
                showToast(data.message, 'success');
            }
        });
    });
});
</script>

@endpush

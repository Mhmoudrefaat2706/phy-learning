@extends('admin.pages.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('Admins') }}</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('Create New Admin') }}
                </button>
            </div>

            <!-- Toast -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index:1100;">
                <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">{{ __('Success') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="adminsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width:60px">#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th class="text-center" style="width:180px">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $i=>$admin)
                                <tr id="adminRow{{ $admin->id }}">
                                    <td class="text-center fw-bold">{{ $i+1 }}</td>
                                    <td class="admin-name">{{ $admin->name }}</td>
                                    <td class="admin-email">{{ $admin->email }}</td>
                                    <td class="admin-phone">{{ $admin->phone ?? '-' }}</td>
                                    <td class="admin-status">{{ $admin->status }}</td>
                                    <td class="admin-role" data-role-id="{{ $admin->role_id }}">
                                        {{ $admin->role ? $admin->role->name : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-secondary edit-admin" data-id="{{ $admin->id }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-admin" data-id="{{ $admin->id }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                                <div class="mt-3 px-5">
                            {{ $admins->links("pagination::bootstrap-5") }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Create --}}
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Admin') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createAdminForm">@csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="createAdminName" name="name">
                        <div class="invalid-feedback" id="createAdminNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="createAdminEmail" name="email">
                        <div class="invalid-feedback" id="createAdminEmailError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="createAdminPhone" name="phone">
                        <div class="invalid-feedback" id="createAdminPhoneError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <input type="password" class="form-control" id="createAdminPassword" name="password">
                        <div class="invalid-feedback" id="createAdminPasswordError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Confirm Password') }}</label>
                        <input type="password" class="form-control" id="createAdminPassword_confirmation" name="password_confirmation">
                        <div class="invalid-feedback" id="createAdminPassword_confirmationError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Role') }}</label>
                        <select class="form-select" id="createAdminRole" name="role_id">
                            <option value="">{{ __('Select Role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="createAdminRoleError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="createAdminStatus" name="status">
                            <option value="active" selected>{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                        <div class="invalid-feedback" id="createAdminStatusError"></div>
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

{{-- Edit --}}
<div class="modal fade" id="editAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Admin') }} - <span id="modalAdminName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm">@csrf @method('PUT')
                    <input type="hidden" id="modalAdminId">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" id="adminNameInput" name="name">
                        <div class="invalid-feedback" id="adminNameInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="adminEmailInput" name="email">
                        <div class="invalid-feedback" id="adminEmailInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" id="adminPhoneInput" name="phone">
                        <div class="invalid-feedback" id="adminPhoneInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('New Password (optional)') }}</label>
                        <input type="password" class="form-control" id="adminPasswordInput" name="password">
                        <div class="invalid-feedback" id="adminPasswordInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Role') }}</label>
                        <select class="form-select" id="adminRoleInput" name="role_id">
                            <option value="">{{ __('Select Role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="adminRoleInputError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select class="form-select" id="adminStatusInput" name="status">
                            <option value="active">{{ __('Active') }}</option>
                            <option value="inactive">{{ __('Inactive') }}</option>
                        </select>
                        <div class="invalid-feedback" id="adminStatusInputError"></div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete --}}
<div class="modal fade" id="deleteAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete') }} <strong id="deleteAdminName"></strong>?</p>
                <input type="hidden" id="deleteAdminId">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAdminBtn">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const createForm=document.getElementById('createAdminForm'),
          editForm=document.getElementById('editAdminForm'),
          createModal=new bootstrap.Modal('#createAdminModal'),
          editModal=new bootstrap.Modal('#editAdminModal'),
          deleteModal=new bootstrap.Modal('#deleteAdminModal');

    const toastEl=document.getElementById('liveToast');
    const showToast=(msg)=>{
        document.getElementById('toastMessage').textContent=msg;
        new bootstrap.Toast(toastEl).show();
    };

    function showErrors(errors,prefix){
        Object.keys(errors).forEach(k=>{
            const id=prefix+k.charAt(0).toUpperCase()+k.slice(1);
            const input=document.getElementById(id);
            const err=document.getElementById(id+'Error');
            if(input&&err){
                input.classList.add('is-invalid');
                err.textContent=errors[k][0];
            }
        });
    }

    createForm.addEventListener('submit',e=>{
        e.preventDefault();
        ['Name','Email','Phone','Password','Password_confirmation','Role','Status']
            .forEach(s=>document.getElementById('createAdmin'+s).classList.remove('is-invalid'));
        fetch("{{ route('admin.admins.store') }}",{
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:new FormData(createForm)
        }).then(r=>r.json()).then(data=>{
            if(data.success){
                const t=document.querySelector('#adminsTable tbody');
                const idx=t.rows.length+1;
                const a=data.admin;
                t.insertAdjacentHTML('beforeend',
                    `<tr id="adminRow${a.id}">
                        <td class="text-center fw-bold">${idx}</td>
                        <td class="admin-name">${a.name}</td>
                        <td class="admin-email">${a.email}</td>
                        <td class="admin-phone">${a.phone??'-'}</td>
                        <td class="admin-status">${a.status}</td>
                        <td class="admin-role" data-role-id="${a.role_id}">${a.role?a.role.name:'-'}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary edit-admin" data-id="${a.id}"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger delete-admin" data-id="${a.id}"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>`);
                createModal.hide(); createForm.reset();
                showToast('Admin Created');
            }else if(data.errors){showErrors(data.errors,'createAdmin');}
        });
    });

    document.addEventListener('click',e=>{
        if(e.target.closest('.edit-admin')){
            const id=e.target.closest('.edit-admin').dataset.id,
                  row=document.getElementById('adminRow'+id);
            document.getElementById('modalAdminId').value=id;
            document.getElementById('modalAdminName').textContent=row.querySelector('.admin-name').textContent;
            document.getElementById('adminNameInput').value=row.querySelector('.admin-name').textContent;
            document.getElementById('adminEmailInput').value=row.querySelector('.admin-email').textContent;
            document.getElementById('adminPhoneInput').value=row.querySelector('.admin-phone').textContent;
            document.getElementById('adminRoleInput').value=row.querySelector('.admin-role').dataset.roleId;
            document.getElementById('adminStatusInput').value=row.querySelector('.admin-status').textContent;
            ['adminNameInput','adminEmailInput','adminPhoneInput','adminRoleInput','adminStatusInput']
                .forEach(i=>document.getElementById(i).classList.remove('is-invalid'));
            editModal.show();
        }
        if(e.target.closest('.delete-admin')){
            const id=e.target.closest('.delete-admin').dataset.id,
                  row=document.getElementById('adminRow'+id);
            document.getElementById('deleteAdminId').value=id;
            document.getElementById('deleteAdminName').textContent=row.querySelector('.admin-name').textContent;
            deleteModal.show();
        }
    });

    editForm.addEventListener('submit',e=>{
        e.preventDefault();
        ['adminNameInput','adminEmailInput','adminPhoneInput','adminPasswordInput','adminRoleInput','adminStatusInput']
            .forEach(i=>document.getElementById(i).classList.remove('is-invalid'));
        const id=document.getElementById('modalAdminId').value;
        const fd=new FormData(editForm); fd.append('_method','PUT');
        fetch(`/admin/admins/${id}`,{
            method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},body:fd
        }).then(r=>r.json()).then(data=>{
            if(data.success){
                const row=document.getElementById('adminRow'+id);
                row.querySelector('.admin-name').textContent=data.admin.name;
                row.querySelector('.admin-email').textContent=data.admin.email;
                row.querySelector('.admin-phone').textContent=data.admin.phone??'-';
                row.querySelector('.admin-status').textContent=data.admin.status;
                row.querySelector('.admin-role').textContent=data.admin.role?data.admin.role.name:'-';
                row.querySelector('.admin-role').dataset.roleId=data.admin.role_id;
                editModal.hide();
                showToast('Admin Updated');
            }else if(data.errors){showErrors(data.errors,'admin');}
        });
    });

    document.getElementById('confirmDeleteAdminBtn').addEventListener('click',()=>{
        const id=document.getElementById('deleteAdminId').value;
        fetch(`/admin/admins/${id}`,{
            method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        }).then(r=>r.json()).then(data=>{
            if(data.success){
                document.getElementById('adminRow'+id).remove();
                document.querySelectorAll('#adminsTable tbody tr').forEach((tr,i)=>{
                    tr.querySelector('td:first-child').textContent=i+1;
                });
                deleteModal.hide();
                showToast('Admin Deleted');
            }
        });
    });
});
</script>
@endpush

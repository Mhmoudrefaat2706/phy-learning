@extends("admin.pages.app")

@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">{{ __('Messages') }}</h4>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table align-middle" id="messagesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('phone') }}</th>
                                    <th>{{ __('email') }}</th>
                                    <th>{{ __('message') }}</th>
                                    <th>{{ __('category') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th class="text-center">{{ __('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $index => $message)
                                    <tr id="messageRow{{ $message->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $message->name }}</td>
                                        <td>{{ $message->phone }}</td>
                                        <td>{{ $message->email }}</td>
                                        <td>
                                            <a href="#" class="view-message"
                                               data-message="{{ $message->body }}"
                                               data-username="{{ $message->name }}"
                                               data-bs-toggle="modal" data-bs-target="#messageModal">
                                               {{ __('view message') }}
                                            </a>
                                        </td>
                                        <td>{{ $message->category ? $message->category->name : '-' }}</td>

                                        <td class="msg-read" id="status-{{ $message->id }}">
                                            <span class="badge {{ $message->status === 'read' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $message->status === 'read' ? __('read') : __('unread') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary toggle-read"
                                                    data-id="{{ $message->id }}">
                                                <i class="bi bi-eye"></i>
                                                {{ $message->status === 'read' ? __('Mark as unread') : __('Mark as read') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $messages->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-read').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    fetch(`/admin/messages/${id}/toggle-read`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())

                        .then(res => {
                            if (res.success) {
                                let statusCell = document.getElementById(`status-${id}`);
                                statusCell.innerHTML =
                                    `<span class="badge ${res.status === 'read' ? 'bg-success' : 'bg-danger'}">${res.status === 'read' ? 'read' : 'unread'}</span>`;

                                this.innerHTML =
                                    `<i class="bi bi-eye"></i> ${res.status === 'read' ? 'Mark as unread' : 'Mark as read'}`;
                            }

                        });
                });
            });
        });

        document.querySelectorAll('.view-message').forEach(link => {
            link.addEventListener('click', function() {
                let message = this.dataset.message;
                let username = this.dataset.username;

                let modalTitle = document.querySelector('#messageModal .modal-title');
                let modalBody = document.querySelector('#messageModal .modal-body');

                modalTitle.textContent = username;
                modalBody.textContent = message;
            });
        });
    </script>
@endpush

    @extends('layouts.app')

    @section('title', 'Activities')

    @section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center bg-transparent">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Activities</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="py-4 bg-primary text-white rounded-4 p-4 shadow-lg text-center">
                    <h2 class="fw-bold mb-2">Manage Activities</h2>
                    <p class="mb-0">Create, update and organize all activities easily</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-running"></i> Activities List</h4>
                <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Add Activity
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 p-3 mb-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Search by Title</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Type to search...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Filter by Category</label>
                            <select id="categoryFilter" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ strtolower($cat->title) }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 d-flex gap-2">
                            <button type="button" id="filterBtn" class="btn btn-primary flex-grow-1"><i class="fas fa-search"></i> Filter</button>
                            <button type="button" id="resetBtn" class="btn btn-secondary flex-grow-1"><i class="fas fa-undo"></i> Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <!--<a href="{{ route('admin.create.meet') }}" class="btn btn-primary">
    Create a New Meeting
</a> -->

    <!-- Activities Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="activitiesTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Media</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>User</th>
                            <th>Time</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>
                                @if($activity->media_type === 'image')
                                    <img src="{{ $activity->media_url }}" class="activity-media">
                                @elseif($activity->media_type === 'video')
                                    <video class="activity-media" muted>
                                        <source src="{{ $activity->media_url }}" type="video/mp4">
                                        Your browser does not support video playback.
                                    </video>
                                @else
                                    <span class="text-muted">No media</span>
                                @endif
                            </td>
                            <td class="activity-title">{{ $activity->title }}</td>
                            <td class="activity-category">{{ $activity->category->title ?? '-' }}</td>
                            <td>{{ $activity->user->name ?? '-' }}</td>
                            <td>{{ $activity->time }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.activities.show', $activity->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $activity->id }}" 
                                    data-title="{{ $activity->title }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No activities found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="activityTitle"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
    </div>
    @endsection

    @push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Delete modal
        const deleteButtons = document.querySelectorAll(".delete-btn");
        const deleteForm = document.getElementById("deleteForm");
        const activityTitle = document.getElementById("activityTitle");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const id = this.dataset.id;
                const title = this.dataset.title;
                deleteForm.action = `/admin/activities/${id}`;
                activityTitle.textContent = title;
                const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
                modal.show();
            });
        });

        // Live Search + Category Filter
        const searchInput = document.getElementById("searchInput");
        const categoryFilter = document.getElementById("categoryFilter");
        const tableRows = document.querySelectorAll("#activitiesTable tbody tr");

        function filterTable() {
            const searchQuery = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value.toLowerCase();

            tableRows.forEach(row => {
                const title = row.querySelector(".activity-title").textContent.toLowerCase();
                const category = row.querySelector(".activity-category").textContent.toLowerCase();

                const matchesSearch = title.includes(searchQuery);
                const matchesCategory = !selectedCategory || category.includes(selectedCategory);

                row.style.display = (matchesSearch && matchesCategory) ? "" : "none";
            });
        }

        // Live filtering
        searchInput.addEventListener("keyup", filterTable);
        categoryFilter.addEventListener("change", filterTable);

        // Reset button
        document.getElementById("resetBtn").addEventListener("click", function () {
            searchInput.value = '';
            categoryFilter.value = '';
            filterTable();
        });

        // Filter button triggers filtering manually
        document.getElementById("filterBtn").addEventListener("click", filterTable);
    });
    document.addEventListener("DOMContentLoaded", function () {
        const openBtn = document.getElementById('openChat');
        const closeBtn = document.getElementById('closeChat');
        const chatWindow = document.getElementById('chatWindow');
        const chatContent = document.getElementById('chatContent');
        const chatInput = document.getElementById('chatInput');
        const chatSend = document.getElementById('chatSend');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Toggle chat window
        openBtn.addEventListener('click', () => {
            chatWindow.style.display = 'flex';
            if(chatContent.children.length === 0){
                appendMessage('assistant', 'Hi there! Start your discussion with me. 🤖');
            }
        });
        closeBtn.addEventListener('click', () => chatWindow.style.display = 'none');

        // Append message
        function appendMessage(role, text){
            const msgDiv = document.createElement('div');
            msgDiv.style.padding = '10px 14px';
            msgDiv.style.borderRadius = '16px';
            msgDiv.style.maxWidth = '75%';
            msgDiv.style.wordBreak = 'break-word';
            msgDiv.style.clear = 'both';

            if(role === 'user'){
                msgDiv.style.background = '#d1e7dd';
                msgDiv.style.alignSelf = 'flex-end';
                msgDiv.style.float = 'right';
            } else {
                msgDiv.style.background = '#f1f3f5';
                msgDiv.style.alignSelf = 'flex-start';
                msgDiv.style.float = 'left';
            }

            msgDiv.innerHTML = text.replace(/\n/g, '<br>');
            chatContent.appendChild(msgDiv);
            chatContent.scrollTop = chatContent.scrollHeight;
        }

        // Send message
        async function sendMessage(){
            const text = chatInput.value.trim();
            if(!text) return;

            appendMessage('user', text);
            chatInput.value = '';
            appendMessage('assistant', 'Typing...');

            try {
                const response = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message: text }),
                });

                const data = await response.json();
                chatContent.lastChild.remove(); // remove "Typing..."
                if(data.assistant){
                    appendMessage('assistant', data.assistant);
                } else {
                    appendMessage('assistant', 'Error: ' + (data.error || 'No response'));
                }
            } catch(err){
                chatContent.lastChild.remove();
                appendMessage('assistant', 'Network error: ' + err.message);
            }
        }

        chatSend.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', e => {
            if(e.key === 'Enter' && !e.shiftKey){
                e.preventDefault();
                sendMessage();
            }
        });
    });

    </script>
    <!-- Floating Chat Bubble -->
    <div id="chatBubble" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; font-family: Arial, sans-serif;">
        <!-- Chat Toggle Button -->
        <button id="openChat" style="
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4e9af1, #0052cc);
            color: white;
            border: none;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        ">
            <i class="fas fa-robot"></i>
        </button>

        <!-- Chat Window -->
        <div id="chatWindow" style="
            display: none;
            width: 360px;
            max-height: 500px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            overflow: hidden;
            flex-direction: column;
            display: flex;
            animation: fadeIn 0.3s ease;
            font-size: 14px;
        ">
            <!-- Header -->
            <div style="background: #0052cc; color: white; padding: 12px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
                AI Chat
                <button id="closeChat" style="background: transparent; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
            </div>

            <!-- Messages -->
            <div id="chatContent" style="flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px;"></div>

            <!-- Input -->
            <div style="padding: 10px; border-top: 1px solid #ddd; display: flex; gap: 8px;">
                <textarea id="chatInput" placeholder="Type a message..." style="
                    flex: 1;
                    height: 50px;
                    padding: 10px;
                    border-radius: 12px;
                    border: 1px solid #ccc;
                    resize: none;
                "></textarea>
                <button id="chatSend" style="
                    background: #4e9af1;
                    color: white;
                    border: none;
                    border-radius: 12px;
                    padding: 0 16px;
                    cursor: pointer;
                    font-weight: bold;
                ">Send</button>
            </div>
        </div>
    </div>

    <style>
    /* Simple fade-in animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Make all images/videos same size */
    .activity-media {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        display: block;
        margin: 0 auto;
    }

    /* Optional: smaller media size on mobile */
    @media (max-width: 768px) {
        .activity-media {
            width: 90px;
            height: 60px;
        }
    }
    </style>

    @endpush

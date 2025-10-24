@extends('layouts.app')

@section('title', 'Create Meeting')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Meeting</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="py-5 bg-primary text-white rounded-4 p-5 shadow-lg">
                <div class="text-center">
                    <h2 class="display-6 fw-bold mb-3">🎥 Create a New Meeting</h2>
                    <p class="lead mb-0">Connect with your team members through seamless video conferencing</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-transparent py-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Select Participants</h4>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-3" id="selectedCount">0 selected</span>
                            <!-- Enhanced Select All Checkbox -->
                            <div class="select-all-container">
                                <input type="checkbox" id="selectAll" class="select-all-checkbox">
                                <label for="selectAll" class="select-all-label">
                                    <div class="select-all-box">
                                        <i class="select-all-icon"></i>
                                    </div>
                                    <span class="select-all-text">Select All</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Advanced Search -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <label for="userSearch" class="form-label fw-bold">Search Participants</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text" id="userSearch" class="form-control border-start-0 auto-filter" 
                                                       placeholder="Search by name or email..." 
                                                       data-filter="search">
                                                <button type="button" id="clear-search" class="btn btn-outline-secondary" title="Clear search" style="display: none;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters -->
                    <div id="active-filters" class="mb-4" style="display: none;">
                        <small class="text-muted fw-bold">Active filters:</small>
                        <div id="active-filters-list" class="mt-1"></div>
                    </div>

                    <!-- Users Grid -->
                    <form action="{{ route('admin.start.meet') }}" method="POST" id="meetingForm">
                        @csrf
                        
                        <div class="row g-3" id="usersContainer">
                            @foreach($users as $user)
                                <div class="col-xl-4 col-lg-6 user-card" 
                                     data-name="{{ strtolower($user->name) }}" 
                                     data-email="{{ strtolower($user->email) }}"
                                     data-status="{{ $user->status ?? 'active' }}">
                                    <div class="user-select-card p-3 rounded-3 border position-relative">
                                        <!-- Selection Indicator -->
                                        <div class="selection-indicator">
                                            <input type="checkbox" name="users[]" value="{{ $user->id }}" id="user{{ $user->id }}" class="user-checkbox">
                                            <label for="user{{ $user->id }}" class="selection-label">
                                                <i class="fas fa-check"></i>
                                            </label>
                                        </div>

                                        <!-- User Avatar & Info -->
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3">
                                                @if($user->avatar)
                                                    <img src="{{ $user->avatar }}" class="rounded-circle" alt="{{ $user->name }}">
                                                @else
                                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div class="status-indicator {{ $user->status === 'online' ? 'bg-success' : 'bg-secondary' }}"></div>
                                            </div>
                                            <div class="user-info flex-grow-1">
                                                <h6 class="mb-1 fw-bold user-name">{{ $user->name }}</h6>
                                                <p class="mb-1 text-muted small user-email">{{ $user->email }}</p>
                                                <div class="user-meta">
                                                    @if($user->role)
                                                        <span class="badge bg-info user-role">{{ $user->role }}</span>
                                                    @endif
                                                    <span class="badge {{ $user->status === 'online' ? 'bg-success' : 'bg-secondary' }} user-status">
                                                        {{ $user->status === 'online' ? 'Online' : 'Offline' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hover Actions -->
                                        <div class="user-actions">
                                            <button type="button" class="btn btn-sm btn-outline-primary quick-view" data-user-id="{{ $user->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- No Results State -->
                        <div id="no-results" class="text-center py-5" style="display: none;">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Users Found</h4>
                            <p class="text-muted">Try adjusting your search criteria</p>
                        </div>
                    </form>
                </div>

                <!-- Action Footer -->
                <div class="card-footer bg-transparent py-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" id="clearSelection" class="btn btn-outline-danger">
                                <i class="fas fa-times me-2"></i>Clear Selection
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end">
                                <div class="fw-bold text-primary" id="totalSelected">0 users selected</div>
                                <small class="text-muted">Ready to start meeting</small>
                            </div>
                            <button type="submit" form="meetingForm" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-video me-2"></i>Start Meeting
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Quick View Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- User details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
.user-select-card {
    background: #fff;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.user-select-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #b7d1ff;
}

.user-select-card.selected {
    background: linear-gradient(135deg, #f0f8ff 0%, #e1f0ff 100%);
    border-color: #007bff;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

/* Enhanced Select All Checkbox */
.select-all-container {
    display: flex;
    align-items: center;
    gap: 8px;
}

.select-all-checkbox {
    display: none;
}

.select-all-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #495057;
    transition: all 0.3s ease;
    padding: 4px 8px;
    border-radius: 6px;
}

.select-all-label:hover {
    background: #f8f9fa;
    color: #007bff;
}

.select-all-box {
    width: 20px;
    height: 20px;
    border: 2px solid #6c757d;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
    background: white;
}

.select-all-icon {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
    color: white;
    font-size: 12px;
}

.select-all-checkbox:checked + .select-all-label .select-all-box {
    background: #007bff;
    border-color: #007bff;
    transform: scale(1.05);
}

.select-all-checkbox:checked + .select-all-label .select-all-icon {
    opacity: 1;
    transform: scale(1);
}

.select-all-checkbox:indeterminate + .select-all-label .select-all-box {
    background: #007bff;
    border-color: #007bff;
}

.select-all-checkbox:indeterminate + .select-all-label .select-all-icon::before {
    content: '';
    position: absolute;
    width: 10px;
    height: 2px;
    background: white;
    border-radius: 1px;
}

.select-all-text {
    font-size: 0.875rem;
    font-weight: 500;
}

/* Selection Indicator */
.selection-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 2;
}

.user-checkbox {
    display: none;
}

.selection-label {
    width: 24px;
    height: 24px;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-checkbox:checked + .selection-label {
    background: #007bff;
    border-color: #007bff;
    transform: scale(1.1);
}

.user-checkbox:checked + .selection-label i {
    color: white;
    font-size: 12px;
}

/* User Avatar */
.user-avatar {
    position: relative;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    font-weight: bold;
    font-size: 18px;
}

.user-avatar img {
    width: 50px;
    height: 50px;
    object-fit: cover;
}

.status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    border: 2px solid white;
    border-radius: 50%;
}

/* User Actions */
.user-actions {
    position: absolute;
    bottom: 12px;
    right: 12px;
    opacity: 0;
    transition: all 0.3s ease;
}

.user-select-card:hover .user-actions {
    opacity: 1;
}

.user-meta {
    margin-top: 8px;
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.user-meta .badge {
    font-size: 0.7rem;
}

/* Search and Filters */
.auto-filter {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.auto-filter:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

#active-filters .badge {
    font-size: 0.8rem;
    padding: 6px 10px;
    border-radius: 20px;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    color: white;
    border: none;
}

#clear-search {
    border-left: none;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

#clear-search:hover {
    background: #e9ecef;
    color: #dc3545;
}

/* Responsive Design */
@media (max-width: 768px) {
    .user-select-card {
        margin-bottom: 1rem;
    }
    
    .card-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-footer .d-flex > div {
        width: 100%;
        text-align: center;
    }
    
    .select-all-container {
        flex-direction: column;
        gap: 4px;
    }
}

/* Animation for selection */
@keyframes selectPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.user-select-card.selected {
    animation: selectPulse 0.3s ease-in-out;
}

/* Custom scrollbar for user container */
#usersContainer {
    max-height: 600px;
    overflow-y: auto;
}

#usersContainer::-webkit-scrollbar {
    width: 6px;
}

#usersContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#usersContainer::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#usersContainer::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Enhanced badge styling */
.badge.bg-success {
    background: linear-gradient(135deg, #198754, #0f5132) !important;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #495057) !important;
}

.badge.bg-info {
    background: linear-gradient(135deg, #0dcaf0, #0aa2c0) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchDelay = 300;
    
    // Get elements
    const searchInput = document.getElementById('userSearch');
    const clearSearchBtn = document.getElementById('clear-search');
    const selectAllCheckbox = document.getElementById('selectAll');
    const clearSelectionBtn = document.getElementById('clearSelection');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const userCards = document.querySelectorAll('.user-select-card');
    const selectedCount = document.getElementById('selectedCount');
    const totalSelected = document.getElementById('totalSelected');
    const activeFilters = document.getElementById('active-filters');
    const activeFiltersList = document.getElementById('active-filters-list');
    const noResults = document.getElementById('no-results');
    const usersContainer = document.getElementById('usersContainer');

    // Initialize
    updateSelectionCount();
    updateActiveFilters();

    // Search functionality
    function performSearch() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';
            
            let visibleCount = 0;
            
            document.querySelectorAll('.user-card').forEach(card => {
                const name = card.dataset.name || '';
                const email = card.dataset.email || '';
                
                const matchesSearch = !searchQuery || 
                    name.includes(searchQuery) || 
                    email.includes(searchQuery);
                
                if (matchesSearch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
            
            updateActiveFilters({
                search: searchQuery
            });
        }, searchDelay);
    }

    // Update active filters display
    function updateActiveFilters(filters = null) {
        if (!activeFilters || !activeFiltersList) return;
        
        let filtersHtml = '';
        let hasActiveFilters = false;
        
        if (!filters) {
            filters = {
                search: searchInput ? searchInput.value : ''
            };
        }
        
        if (filters.search && filters.search.trim() !== '') {
            hasActiveFilters = true;
            filtersHtml += `
                <span class="badge bg-primary me-2 mb-2">
                    <i class="fas fa-search me-1"></i>
                    "${filters.search}"
                    <button type="button" class="btn-close btn-close-white ms-1" data-clear="search" title="Clear this filter"></button>
                </span>
            `;
        }
        
        if (hasActiveFilters) {
            activeFiltersList.innerHTML = filtersHtml;
            activeFilters.style.display = 'block';
            
            activeFiltersList.querySelectorAll('[data-clear]').forEach(button => {
                button.addEventListener('click', function() {
                    const filterType = this.getAttribute('data-clear');
                    clearFilter(filterType);
                });
            });
        } else {
            activeFilters.style.display = 'none';
        }
        
        if (clearSearchBtn) {
            if (searchInput && searchInput.value.trim()) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
        }
    }

    function clearFilter(filterType) {
        switch (filterType) {
            case 'search':
                if (searchInput) searchInput.value = '';
                break;
        }
        performSearch();
    }

    // Update selection count
    function updateSelectionCount() {
        const selected = document.querySelectorAll('.user-checkbox:checked').length;
        selectedCount.textContent = `${selected} selected`;
        totalSelected.textContent = `${selected} user${selected !== 1 ? 's' : ''} selected`;
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            const totalUsers = document.querySelectorAll('.user-checkbox').length;
            const visibleUsers = document.querySelectorAll('.user-card[style=""]').length;
            
            // Handle indeterminate state
            if (selected > 0 && selected < visibleUsers) {
                selectAllCheckbox.indeterminate = true;
                selectAllCheckbox.checked = false;
            } else if (selected === visibleUsers && visibleUsers > 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            }
        }
    }

    // Card selection functionality
    userCards.forEach((card, index) => {
        const checkbox = userCheckboxes[index];
        
        card.addEventListener('click', (e) => {
            // Don't trigger if clicking on the checkbox or quick view button
            if (e.target.closest('.user-checkbox') || e.target.closest('.quick-view')) {
                return;
            }
            
            checkbox.checked = !checkbox.checked;
            updateCardSelection(card, checkbox.checked);
            updateSelectionCount();
        });

        checkbox.addEventListener('change', () => {
            updateCardSelection(card, checkbox.checked);
            updateSelectionCount();
        });

        // Initialize card selection state
        updateCardSelection(card, checkbox.checked);
    });

    function updateCardSelection(card, isSelected) {
        if (isSelected) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            const visibleUserCards = document.querySelectorAll('.user-card[style=""]');
            
            visibleUserCards.forEach(card => {
                const checkbox = card.querySelector('.user-checkbox');
                if (checkbox) {
                    checkbox.checked = isChecked;
                    updateCardSelection(card.closest('.user-select-card'), isChecked);
                }
            });
            updateSelectionCount();
        });
    }

    // Clear selection
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                const card = checkbox.closest('.user-select-card');
                if (card) {
                    updateCardSelection(card, false);
                }
            });
            updateSelectionCount();
        });
    }

    // Quick view functionality
    document.querySelectorAll('.quick-view').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const userId = this.dataset.userId;
            // Implement quick view modal functionality here
            console.log('Quick view for user:', userId);
        });
    });

    // Event listeners for search and filters
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Escape') {
                this.value = '';
                performSearch();
            }
        });
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            performSearch();
        });
    }

    // Form submission validation
    const meetingForm = document.getElementById('meetingForm');
    if (meetingForm) {
        meetingForm.addEventListener('submit', function(e) {
            const selectedUsers = document.querySelectorAll('.user-checkbox:checked').length;
            if (selectedUsers === 0) {
                e.preventDefault();
                showToast('Please select at least one user for the meeting', 'warning', 'exclamation-triangle');
            }
        });
    }
});

// Toast notification function
function showToast(message, type = 'info', icon = null) {
    let iconClass, alertClass;
    
    switch (type) {
        case 'success':
            iconClass = icon || 'check-circle';
            alertClass = 'alert-success';
            break;
        case 'error':
            iconClass = icon || 'exclamation-circle';
            alertClass = 'alert-danger';
            break;
        case 'warning':
            iconClass = icon || 'exclamation-triangle';
            alertClass = 'alert-warning';
            break;
        default:
            iconClass = icon || 'info-circle';
            alertClass = 'alert-info';
    }
    
    const toast = document.createElement('div');
    toast.className = `alert ${alertClass} alert-dismissible fade show position-fixed shadow-lg`;
    toast.style.cssText = `
        top: 30px; 
        right: 30px; 
        z-index: 9999; 
        min-width: 350px; 
        border: none; 
        border-radius: 15px;
        backdrop-filter: blur(10px);
        animation: slideInRight 0.5s ease-out;
    `;
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${iconClass} me-3" style="font-size: 20px;"></i>
            <div class="flex-grow-1">
                <strong>${message}</strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.5s ease-in forwards';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500);
        }
    }, 4000);
}

// CSS animations
const styles = document.createElement('style');
styles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(styles);
</script>
@endpush
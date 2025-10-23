@extends('layouts.app')

@section('title', 'Create Meeting')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <div class="bg-white p-5 rounded-4 shadow-lg">

        <h2 class="text-center text-primary mb-4 fw-bold" style="font-size: 32px; letter-spacing: 1px;">
            Create a Meeting
        </h2>

        <!-- Live Search -->
        <div class="mb-4">
            <input type="text" id="userSearch" class="form-control shadow-sm" placeholder="Search users by name or email..." style="border-radius: 50px; padding: 14px; transition: all 0.3s;">
        </div>

        <form action="{{ route('admin.start.meet') }}" method="POST">
            @csrf

            <div class="row g-4" id="usersContainer">
                @foreach($users as $user)
                    <div class="col-lg-4 col-md-6 col-sm-12 user-card" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                        <div class="invite-card p-3 rounded-3 border d-flex align-items-center justify-content-between shadow-sm" style="background: #f9f9f9; cursor: pointer; transition: all 0.3s;">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" name="users[]" value="{{ $user->id }}" id="user{{ $user->id }}" class="custom-checkbox">
                                <label for="user{{ $user->id }}" class="mb-0 ms-3" style="cursor: pointer;">
                                    <strong class="d-block">{{ $user->name }}</strong>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </label>
                            </div>
                            <div class="checkmark"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4 d-flex justify-content-center gap-3">
                <button type="button" id="clearSelection" class="btn btn-outline-danger btn-lg px-4 fw-semibold" style="border-radius: 50px; transition: all 0.3s;">
                    Clear Selection
                </button>
                <button type="submit" class="btn btn-primary btn-lg px-5 fw-semibold" style="border-radius: 50px; transition: all 0.3s;">
                    Start Meeting
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live search
    const searchInput = document.getElementById('userSearch');
    const userCards = document.querySelectorAll('.user-card');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        userCards.forEach(card => {
            const name = card.dataset.name;
            const email = card.dataset.email;
            card.style.display = (name.includes(query) || email.includes(query)) ? '' : 'none';
        });
    });

    // Card click & highlight
    document.querySelectorAll('.invite-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');

        card.addEventListener('click', e => {
            if(e.target !== checkbox){
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });

        checkbox.addEventListener('change', () => {
            if(checkbox.checked){
                card.style.background = '#e0f2ff';
                card.style.borderColor = '#007bff';
                card.style.boxShadow = '0 8px 20px rgba(0,123,255,0.2)';
            } else {
                card.style.background = '#f9f9f9';
                card.style.borderColor = '#ddd';
                card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)';
            }
        });

        card.addEventListener('mouseenter', () => {
            if(!checkbox.checked) card.style.transform = 'translateY(-3px)';
        });

        card.addEventListener('mouseleave', () => {
            if(!checkbox.checked) card.style.transform = 'translateY(0)';
        });
    });

    // Clear selection button
    document.getElementById('clearSelection').addEventListener('click', () => {
        document.querySelectorAll('.custom-checkbox').forEach(cb => {
            cb.checked = false;
            cb.dispatchEvent(new Event('change'));
        });
    });
</script>

<style>
/* Smooth custom checkboxes */
.custom-checkbox {
    appearance: none;
    width: 24px;
    height: 24px;
    border: 2px solid #007bff;
    border-radius: 6px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s;
    background: white;
}

.custom-checkbox:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.custom-checkbox:checked::after {
    content: '';
    position: absolute;
    top: 4px;
    left: 8px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

/* Hover effect for cards */
.invite-card {
    transition: all 0.3s ease;
}

/* Smooth shadow & scale on hover */
.invite-card:hover {
    transform: translateY(-2px) scale(1.02);
}

/* Input focus effect */
#userSearch:focus {
    box-shadow: 0 4px 20px rgba(0,123,255,0.25);
    border-color: #007bff;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .invite-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .invite-card label {
        margin-top: 5px;
    }
}
</style>
@endsection

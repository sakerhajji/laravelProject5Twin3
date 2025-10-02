@extends('layouts.front')

@section('title', 'Mes Repas')

@section('content')
<style>
.meals-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    font-weight: 400;
}

.meal-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.meal-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.meal-header {
    background: #6366f1;
    color: white;
    padding: 1.5rem;
    position: relative;
}

.meal-title {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
}

.ingredient-count {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.meal-body {
    padding: 1.5rem;
}

.meal-description {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-style: italic;
    line-height: 1.6;
}

.ingredients-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ingredients-title::before {
    content: '🥗';
}

.ingredient-item {
    background: #f8f9fa;
    color: #495057;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.ingredient-item:hover {
    background: #e9ecef;
    transform: translateX(3px);
}

.ingredient-name {
    font-weight: 500;
}

.ingredient-quantity {
    background: #6366f1;
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.no-meals {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    color: #6c757d;
    margin: 2rem auto;
    max-width: 500px;
    border: 1px solid #e9ecef;
}

.no-meals-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.7;
}

.no-meals h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-weight: 600;
}

.no-ingredients {
    text-align: center;
    color: #adb5bd;
    font-style: italic;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-top: 1rem;
    border: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .meals-page {
        padding: 1rem 0;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .page-header {
        margin-bottom: 2rem;
        padding: 1rem 0;
    }
    
    .meal-body {
        padding: 1rem;
    }
}
</style>

<div class="meals-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Mes Repas</h1>
            <p class="page-subtitle">Découvrez vos repas personnalisés</p>
        </div>

        @if($repas->isEmpty())
            <div class="no-meals">
                <div class="no-meals-icon">🍽️</div>
                <h3>Aucun repas disponible</h3>
                <p>Vous n'avez aucun repas assigné pour le moment. Revenez plus tard pour découvrir vos repas personnalisés.</p>
            </div>
        @else
            <div class="row">
                @foreach($repas as $repa)
                    <div class="col-md-6 col-lg-4">
                        <div class="meal-card">
                            <div class="meal-header">
                                <h5 class="meal-title">{{ $repa->nom }}</h5>
                                @if($repa->aliments->isNotEmpty())
                                    <div class="ingredient-count">
                                        {{ $repa->aliments->count() }} ingrédient{{ $repa->aliments->count() > 1 ? 's' : '' }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="meal-body">
                                @if($repa->description)
                                    <p class="meal-description">{{ $repa->description }}</p>
                                @endif

                                @if($repa->aliments->isNotEmpty())
                                    <h6 class="ingredients-title">Ingrédients</h6>
                                    @foreach($repa->aliments as $aliment)
                                        <div class="ingredient-item">
                                            <span class="ingredient-name">{{ $aliment->nom }}</span>
                                            <span class="ingredient-quantity">{{ $aliment->pivot->quantite }}g</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-ingredients">
                                        Ce repas ne contient aucun ingrédient pour le moment
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
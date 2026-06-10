@extends('layouts.guest')
@section('title', 'Find Best Salons - Glamora')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    
    .hero-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        font-family: 'Playfair Display', serif;
        position: relative;
        z-index: 1;
    }
    
    .hero-section p {
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }
    
    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-top: -2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        z-index: 2;
    }
    
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #E91E8C;
        box-shadow: 0 0 0 3px rgba(233,30,140,0.1);
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #E91E8C, #c2185b);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(233,30,140,0.3);
    }
    
    /* Grid Layout */
    .salons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    /* Salon Card */
    .salon-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .salon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(233,30,140,0.15);
    }
    
    .salon-image {
        height: 200px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .salon-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .salon-card:hover .salon-image img {
        transform: scale(1.05);
    }
    
    .salon-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(5px);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .salon-content {
        padding: 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .salon-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }
    
    .salon-location {
        font-size: 0.8rem;
        color: #E91E8C;
        margin-bottom: 0.8rem;
    }
    
    .salon-location i {
        margin-right: 0.3rem;
    }
    
    .salon-desc {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex: 1;
    }
    
    .btn-view {
        background: #fdf2f8;
        color: #E91E8C;
        border: none;
        border-radius: 50px;
        padding: 0.3rem 1.2rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        width: auto;
        margin-top: auto;
    }
    
    .btn-view:hover {
        background: #E91E8C;
        color: white;
    }
    
    .result-count {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 1rem;
        padding-left: 0.5rem;
    }
    
    @media (max-width: 992px) {
        .salons-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
        }
    }
    
    @media (max-width: 576px) {
        .salons-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .hero-section h1 {
            font-size: 1.8rem;
        }
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1>Find Your Perfect Salon</h1>
                <p class="mt-3">Discover the best salons in your city. Book appointments with top-rated professionals.</p>
            </div>
        </div>
    </div>
</section>

<div class="container">
    
  
    <div class="filter-card">
        <form method="GET" action="{{ route('salons.index') }}" class="row g-3" id="filterForm">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="🔍 Search by salon name..." value="{{ request('search') }}" id="searchInput">
            </div>
            <div class="col-md-3">
                <select name="city" class="form-select" id="cityFilter">
                    <option value="">📍 All Cities</option>
                    <option value="Lahore" {{ request('city')=='Lahore' ? 'selected' : '' }}>Lahore</option>
                    <option value="Karachi" {{ request('city')=='Karachi' ? 'selected' : '' }}>Karachi</option>
                    <option value="Islamabad" {{ request('city')=='Islamabad' ? 'selected' : '' }}>Islamabad</option>
                    <option value="Rawalpindi" {{ request('city')=='Rawalpindi' ? 'selected' : '' }}>Rawalpindi</option>
                    <option value="Faisalabad" {{ request('city')=='Faisalabad' ? 'selected' : '' }}>Faisalabad</option>
                    <option value="Multan" {{ request('city')=='Multan' ? 'selected' : '' }}>Multan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select" id="categoryFilter">
                    <option value="">📂 All Categories</option>
                    <option value="hair">💇 Hair Styling</option>
                    <option value="makeup">💄 Makeup</option>
                    <option value="bridal">👰 Bridal</option>
                    <option value="facial">✨ Facial</option>
                    <option value="nail">💅 Nail Art</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
    
   
    <div class="result-count">
        <i class="fas fa-store me-1"></i> <strong id="salonCount">12</strong> premium salons found
    </div>
    
   
    <div class="salons-grid" id="salonsGrid">
        
        
        <div class="salon-item" data-city="Lahore" data-name="Beauty Blush Salon">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1527799820374-dcf8d9d4a5b5?w=500" alt="Beauty Blush Salon">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Lahore</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Beauty Blush Salon</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> DHA Phase 5, Lahore</div>
                    <p class="salon-desc">Premium salon offering hair styling, makeup, and bridal packages. Professional staff with 10+ years experience.</p>
                    <a href="{{ route('salons.show', 'beauty-blush-salon') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
   
        <div class="salon-item" data-city="Lahore" data-name="Chic Beauty Hub">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=500" alt="Chic Beauty Hub">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Lahore</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Chic Beauty Hub</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> Gulberg, Lahore</div>
                    <p class="salon-desc">Trendy salon in heart of Lahore. Best for hair coloring, styling, and modern cuts.</p>
                    <a href="{{ route('salons.show', 'chic-beauty-hub') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
      
        <div class="salon-item" data-city="Lahore" data-name="Royal Beauty Salon">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1521590832167-7bcbfa638b1b?w=500" alt="Royal Beauty Salon">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Lahore</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Royal Beauty Salon</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> Johar Town, Lahore</div>
                    <p class="salon-desc">Royal treatment at affordable prices. Expert in bridal makeup and threading.</p>
                    <a href="{{ route('salons.show', 'royal-beauty-salon') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
       
        <div class="salon-item" data-city="Karachi" data-name="Glamour Haven">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1633681926031-ee4c3c9d3c8d?w=500" alt="Glamour Haven">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Karachi</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Glamour Haven</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> Clifton, Karachi</div>
                    <p class="salon-desc">Luxury salon for all your beauty needs. Specializing in bridal packages and skin treatments.</p>
                    <a href="{{ route('salons.show', 'glamour-haven') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
      
        <div class="salon-item" data-city="Karachi" data-name="Luxury Locks">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b3f?w=500" alt="Luxury Locks">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Karachi</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Luxury Locks</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> DHA Phase 8, Karachi</div>
                    <p class="salon-desc">Premium haircare and beauty services. International standards at affordable prices.</p>
                    <a href="{{ route('salons.show', 'luxury-locks') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
       
        <div class="salon-item" data-city="Karachi" data-name="Pearl Beauty Salon">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=500" alt="Pearl Beauty Salon">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Karachi</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Pearl Beauty Salon</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> Gulshan-e-Iqbal, Karachi</div>
                    <p class="salon-desc">Complete beauty solution with professional staff. Best for facials and nail art.</p>
                    <a href="{{ route('salons.show', 'pearl-beauty-salon') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
  
        <div class="salon-item" data-city="Islamabad" data-name="The Style Studio">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1560869713-7d0a2943084a?w=500" alt="The Style Studio">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Islamabad</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">The Style Studio</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> F-7 Markaz, Islamabad</div>
                    <p class="salon-desc">Modern salon with trending styles and professional makeup artists.</p>
                    <a href="{{ route('salons.show', 'the-style-studio') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
       
        <div class="salon-item" data-city="Islamabad" data-name="Elegance Salon">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?w=500" alt="Elegance Salon">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Islamabad</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Elegance Salon</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> G-11 Markaz, Islamabad</div>
                    <p class="salon-desc">Elegant salon with premium services. Specialized in bridal and event makeup.</p>
                    <a href="{{ route('salons.show', 'elegance-salon') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
      
        <div class="salon-item" data-city="Islamabad" data-name="Divine Beauty Lounge">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=500" alt="Divine Beauty Lounge">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Islamabad</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Divine Beauty Lounge</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> E-11 Sector, Islamabad</div>
                    <p class="salon-desc">Luxury beauty lounge with international standards. Best bridal makeup.</p>
                    <a href="{{ route('salons.show', 'divine-beauty-lounge') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
      
        <div class="salon-item" data-city="Karachi" data-name="Beauty Glow Salon">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b3f?w=500" alt="Beauty Glow Salon">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Karachi</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Beauty Glow Salon</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> North Nazimabad, Karachi</div>
                    <p class="salon-desc">Premium salon with glowing skin treatments and hair services.</p>
                    <a href="{{ route('salons.show', 'beauty-glow-salon') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
      
        <div class="salon-item" data-city="Lahore" data-name="Glam Studio">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1633681926031-ee4c3c9d3c8d?w=500" alt="Glam Studio">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Lahore</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Glam Studio</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> MM Alam Road, Lahore</div>
                    <p class="salon-desc">High-end salon with celebrity makeup artists and premium services.</p>
                    <a href="{{ route('salons.show', 'glam-studio') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
        
        <div class="salon-item" data-city="Islamabad" data-name="Pretty Face Salon">
            <div class="salon-card">
                <div class="salon-image">
                    <img src="https://images.unsplash.com/photo-1521590832167-7bcbfa638b1b?w=500" alt="Pretty Face Salon">
                    <div class="salon-badge"><i class="fas fa-map-marker-alt me-1"></i>Islamabad</div>
                </div>
                <div class="salon-content">
                    <h3 class="salon-name">Pretty Face Salon</h3>
                    <div class="salon-location"><i class="fas fa-location-dot"></i> Blue Area, Islamabad</div>
                    <p class="salon-desc">Affordable luxury salon with experienced staff. Best for daily grooming.</p>
                    <a href="{{ route('salons.show', 'pretty-face-salon') }}" class="btn-view">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

@endsection

@push('scripts')
<script>
    const cityFilter = document.getElementById('cityFilter');
    const searchInput = document.getElementById('searchInput');
    const salonItems = document.querySelectorAll('.salon-item');
    const salonCountSpan = document.getElementById('salonCount');
    
    function filterSalons() {
        const selectedCity = cityFilter.value;
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;
        
        salonItems.forEach(item => {
            const itemCity = item.getAttribute('data-city');
            const itemName = item.getAttribute('data-name').toLowerCase();
            
            const cityMatch = selectedCity === '' || itemCity === selectedCity;
            const searchMatch = searchTerm === '' || itemName.includes(searchTerm);
            
            if (cityMatch && searchMatch) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        salonCountSpan.textContent = visibleCount;
    }
    
    cityFilter.addEventListener('change', filterSalons);
    searchInput.addEventListener('keyup', filterSalons);
    filterSalons();
</script>
@endpush
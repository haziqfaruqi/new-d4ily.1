<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D4ily.1 - Vintage Thrift Shop | Sustainable Fashion Reimagined</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .category-card {
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-zinc-50">
@include('partials.navigation')

    <!-- Hero Section -->
    <section
    class="relative w-full py-20 sm:py-32 border-t-[12px] border-dashed border-[#D65A48] text-stone-800"
    style="
        background-color: #CBBFA2;
        background-image: repeating-linear-gradient(
        90deg,
        #a1b26c 0px,
        #a1b26c 50px,
        #94d6f2 50px,
        #94d6f2 100px
        );
    "
    >
  <div class="relative z-10 px-6 sm:px-8 lg:px-12">
      <div class="max-w-4xl mx-auto text-center">
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
              Sustainable Style,<br>
              <span style="color: #D65A48;">Rediscovered</span>
          </h1>
          <p class="text-xl sm:text-2xl mb-8 max-w-3xl mx-auto text-stone-700">
              Discover unique vintage treasures and give pre-loved fashion a new life.
              Every piece tells a story, every purchase makes a difference.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-stone-800 text-white rounded-full font-semibold hover:bg-[#D65A48] transition-all hover-lift">
                  <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                  Start Shopping
              </a>
              <a href="#features" class="inline-flex items-center gap-2 px-8 py-3 bg-transparent border-2 border-stone-800 text-stone-800 rounded-full font-semibold hover:bg-stone-800 hover:text-white transition-all">
                  <i data-lucide="info" class="w-5 h-5"></i>
                  Learn More
              </a>
          </div>
      </div>
  </div>

  <div class="absolute inset-0 pointer-events-none opacity-20 mix-blend-multiply"
       style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
  </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white" style="border-bottom: 1px solid #e5e7eb;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="flex items-center justify-center mb-2">
                    <i data-lucide="recycle" class="w-8 h-8 text-green-600"></i>
                </div>
                <p class="text-3xl font-bold text-zinc-900">10K+</p>
                <p class="text-sm text-zinc-600">Items Saved</p>
            </div>
            <div>
                <div class="flex items-center justify-center mb-2">
                    <i data-lucide="users" class="w-8 h-8 text-blue-600"></i>
                </div>
                <p class="text-3xl font-bold text-zinc-900">5K+</p>
                <p class="text-sm text-zinc-600">Happy Customers</p>
            </div>
            <div>
                <div class="flex items-center justify-center mb-2">
                    <i data-lucide="heart" class="w-8 h-8 text-red-600"></i>
                </div>
                <p class="text-3xl font-bold text-zinc-900">98%</p>
                <p class="text-sm text-zinc-600">Satisfaction</p>
            </div>
            <div>
                <div class="flex items-center justify-center mb-2">
                    <i data-lucide="leaf" class="w-8 h-8 text-emerald-600"></i>
                </div>
                <p class="text-3xl font-bold text-zinc-900">Zero</p>
                <p class="text-sm text-zinc-600">Waste Policy</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-16" id="features">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-4">Shop by Category</h2>
            <p class="text-lg text-zinc-600 max-w-2xl mx-auto">
                Explore our curated collection of vintage fashion across different styles and eras
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-16">
            <a href="{{ route('shop.index', ['category' => 1]) }}" class="group">
                <div class="category-card bg-gradient-to-br from-pink-100 to-pink-200 rounded-lg p-6 text-center h-32 flex flex-col items-center justify-center">
                    <i data-lucide="shirt" class="w-10 h-10 text-pink-700 mb-3"></i>
                    <h3 class="font-semibold text-pink-900">Clothing</h3>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 2]) }}" class="group">
                <div class="category-card bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg p-6 text-center h-32 flex flex-col items-center justify-center">
                    <i data-lucide="bag" class="w-10 h-10 text-blue-700 mb-3"></i>
                    <h3 class="font-semibold text-blue-900">Bags</h3>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 3]) }}" class="group">
                <div class="category-card bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg p-6 text-center h-32 flex flex-col items-center justify-center">
                    <i data-lucide="zap" class="w-10 h-10 text-purple-700 mb-3"></i>
                    <h3 class="font-semibold text-purple-900">Accessories</h3>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 4]) }}" class="group">
                <div class="category-card bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg p-6 text-center h-32 flex flex-col items-center justify-center">
                    <i data-lucide="watch" class="w-10 h-10 text-yellow-700 mb-3"></i>
                    <h3 class="font-semibold text-yellow-900">Jewelry</h3>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 5]) }}" class="group">
                <div class="category-card bg-gradient-to-br from-green-100 to-green-200 rounded-lg p-6 text-center h-32 flex flex-col items-center justify-center">
                    <i data-lucide="cap" class="w-10 h-10 text-green-700 mb-3"></i>
                    <h3 class="font-semibold text-green-900">Headwear</h3>
                </div>
            </a>
            <a href="{{ route('shop.index') }}" class="group">
                <div class="category-card bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg p-6 text-center h-32 flex flex-col items-center justify-center">
                    <i data-lucide="grid" class="w-10 h-10 text-gray-700 mb-3"></i>
                    <h3 class="font-semibold text-gray-900">All Items</h3>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-2">Featured Treasures</h2>
                <p class="text-lg text-zinc-600">Handpicked vintage finds just for you</p>
            </div>
            <a href="{{ route('shop.index') }}" class="flex items-center gap-2 text-purple-700 hover:text-purple-800 font-medium">
                View All
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($featuredProducts ?? App\Models\Product::inRandomOrder()->take(10)->get() as $product)
                <a href="{{ route('shop.product', $product->id) }}" class="group">
                    <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 mb-3 hover-lift">
                        <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400' }}" alt="{{ $product->name }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-2 left-2 px-2 py-1 text-[9px] font-medium rounded backdrop-blur bg-white/90 text-zinc-900">
                            {{ ucfirst($product->condition) }}
                        </div>
                        @if($product->featured)
                            <div class="absolute top-2 right-2 px-2 py-1 text-[9px] font-medium rounded backdrop-blur bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
                                <i data-lucide="star" class="w-3 h-3 inline mr-0.5"></i>
                                Featured
                            </div>
                        @endif
                    </div>
                    <h3 class="text-sm font-medium text-zinc-900 group-hover:text-purple-600 transition-colors line-clamp-2">
                        {{ $product->name }}
                    </h3>
                    <p class="text-xs text-zinc-500 mt-1">{{ $product->brand }}</p>
                    <p class="text-sm font-semibold text-purple-700 mt-1">RM{{ number_format($product->price, 2) }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-zinc-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-4">How D4ily Works</h2>
            <p class="text-lg text-zinc-600 max-w-2xl mx-auto">
                Join the sustainable fashion revolution in three simple steps
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="mx-auto w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <i data-lucide="search" class="w-10 h-10 text-purple-700"></i>
                </div>
                <h3 class="text-xl font-semibold text-zinc-900 mb-3">Discover</h3>
                <p class="text-zinc-600">
                    Browse our curated collection of vintage fashion finds from different eras and styles
                </p>
            </div>
            <div class="text-center">
                <div class="mx-auto w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                    <i data-lucide="heart" class="w-10 h-10 text-blue-700"></i>
                </div>
                <h3 class="text-xl font-semibold text-zinc-900 mb-3">Choose</h3>
                <p class="text-zinc-600">
                    Select items that speak to your style. Each piece is carefully inspected and authenticated
                </p>
            </div>
            <div class="text-center">
                <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <i data-lucide="leaf" class="w-10 h-10 text-green-700"></i>
                </div>
                <h3 class="text-xl font-semibold text-zinc-900 mb-3">Sustain</h3>
                <p class="text-zinc-600">
                    Give pre-loved fashion a new life while reducing fashion waste and environmental impact
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-zinc-600">
                Everything you need to know about shopping with D4ily
            </p>
        </div>

        <div class="space-y-4" id="faq-container">
            <!-- FAQ Item 1 -->
            <div class="faq-item border border-zinc-200 rounded-lg overflow-hidden">
                <button class="faq-button w-full px-6 py-4 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100 transition-colors text-left">
                    <span class="font-semibold text-zinc-900">What is D4ily and how does it work?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-zinc-500 transition-transform duration-300"></i>
                </button>
                <div class="faq-content hidden px-6 py-4 bg-white">
                    <p class="text-zinc-600">
                        D4ily is a vintage thrift shop dedicated to sustainable fashion. We curate and sell pre-loved clothing, bags, accessories, and jewelry. Each item is carefully inspected for quality and authenticity, giving vintage pieces a new life while reducing fashion waste.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="faq-item border border-zinc-200 rounded-lg overflow-hidden">
                <button class="faq-button w-full px-6 py-4 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100 transition-colors text-left">
                    <span class="font-semibold text-zinc-900">How do you determine the condition of items?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-zinc-500 transition-transform duration-300"></i>
                </button>
                <div class="faq-content hidden px-6 py-4 bg-white">
                    <p class="text-zinc-600">
                        Each item is thoroughly inspected and rated on condition: Excellent (like new), Great (minimal signs of wear), Good (normal wear consistent with age), or Fair (notable wear but still wearable). We always provide detailed photos and accurate descriptions so you know exactly what you're getting.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="faq-item border border-zinc-200 rounded-lg overflow-hidden">
                <button class="faq-button w-full px-6 py-4 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100 transition-colors text-left">
                    <span class="font-semibold text-zinc-900">What payment methods do you accept?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-zinc-500 transition-transform duration-300"></i>
                </button>
                <div class="faq-content hidden px-6 py-4 bg-white">
                    <p class="text-zinc-600">
                        We accept all major credit and debit cards, online banking transfers, and e-wallets including GrabPay and Touch 'n Go. All payments are securely processed through our trusted payment gateway.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="faq-item border border-zinc-200 rounded-lg overflow-hidden">
                <button class="faq-button w-full px-6 py-4 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100 transition-colors text-left">
                    <span class="font-semibold text-zinc-900">Can I return or exchange items?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-zinc-500 transition-transform duration-300"></i>
                </button>
                <div class="faq-content hidden px-6 py-4 bg-white">
                    <p class="text-zinc-600">
                        Due to the unique nature of vintage items, all sales are final. However, if an item arrives significantly different from its description or is damaged, please contact us within 48 hours of delivery and we'll work to make it right.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="faq-item border border-zinc-200 rounded-lg overflow-hidden">
                <button class="faq-button w-full px-6 py-4 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100 transition-colors text-left">
                    <span class="font-semibold text-zinc-900">How do you ship orders?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-zinc-500 transition-transform duration-300"></i>
                </button>
                <div class="faq-content hidden px-6 py-4 bg-white">
                    <p class="text-zinc-600">
                        We ship nationwide using reliable courier services. Orders are typically processed within 1-2 business days, with delivery taking 2-5 business days depending on your location. You'll receive a tracking number once your order ships.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="faq-item border border-zinc-200 rounded-lg overflow-hidden">
                <button class="faq-button w-full px-6 py-4 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100 transition-colors text-left">
                    <span class="font-semibold text-zinc-900">How is shopping vintage sustainable?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-zinc-500 transition-transform duration-300"></i>
                </button>
                <div class="faq-content hidden px-6 py-4 bg-white">
                    <p class="text-zinc-600">
                        Shopping vintage extends the lifecycle of clothing, reducing the demand for new production and keeping items out of landfills. The fashion industry is one of the world's largest polluters, and by choosing pre-loved pieces, you're helping reduce water waste, carbon emissions, and textile waste.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section
  class="relative py-16 text-stone-800"
  style="
    background-color: #CBBFA2;
    background-image: repeating-linear-gradient(
      90deg,
      #a1b26c 0px,
      #a1b26c 50px,
      #94d6f2 50px,
      #94d6f2 100px
    );
  "
>
  <div class="relative z-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold mb-6" style="color: #D65A48;">Ready to Find Your Vintage Treasure?</h2>
        <p class="text-xl mb-8">
            Join thousands of fashion lovers who are making sustainable choices every day
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @guest
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-stone-800 text-white rounded-full font-semibold hover:bg-[#D65A48] transition-all">
                    Sign Up & Start Shopping
                </a>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-transparent border-2 border-stone-800 text-stone-800 rounded-full font-semibold hover:bg-stone-800 hover:text-white transition-all">
                    Browse Collection
                </a>
            @else
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-stone-800 text-white rounded-full font-semibold hover:bg-[#D65A48] transition-all">
                    Continue Shopping
                </a>
                <a href="{{ route('shop.recommendations') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-transparent border-2 border-stone-800 text-stone-800 rounded-full font-semibold hover:bg-stone-800 hover:text-white transition-all">
                    Get Personalized Recommendations
                </a>
            @endguest
        </div>
    </div>
  </div>

  <div class="absolute inset-0 pointer-events-none opacity-20 mix-blend-multiply"
       style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');">
  </div>
</section>

<!-- Footer -->
<footer class="bg-zinc-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">d4ily.1</h3>
                <p class="text-zinc-400">
                    Sustainable fashion reimagined. Every piece has a story, every purchase makes a difference.
                </p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-zinc-400">
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white">Shop</a></li>
                    <li><a href="{{ route('shop.recommendations') }}" class="hover:text-white">For You</a></li>
                    <li><a href="#features" class="hover:text-white">Categories</a></li>
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white">New Arrivals</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Support</h4>
                <ul class="space-y-2 text-zinc-400">
                    <li><a href="#" class="hover:text-white">About Us</a></li>
                    <li><a href="#" class="hover:text-white">Sustainability</a></li>
                    <li><a href="#" class="hover:text-white">Size Guide</a></li>
                    <li><a href="#faq-container" class="hover:text-white">FAQs</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Connect</h4>
                <div class="flex space-x-4 mb-4">
                    <a href="#" class="text-zinc-400 hover:text-white">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-zinc-400 hover:text-white">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-zinc-400 hover:text-white">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-zinc-400 hover:text-white">
                        <i data-lucide="tiktok" class="w-5 h-5"></i>
                    </a>
                </div>
                <p class="text-sm text-zinc-400">
                    Subscribe to get updates on new arrivals and special offers
                </p>
            </div>
        </div>
        <div class="border-t border-zinc-800 mt-8 pt-8 text-center text-sm text-zinc-400">
            <p>&copy; 2024 D4ily.1. All rights reserved. Made with ❤️ for sustainable fashion.</p>
        </div>
    </div>
</footer>

<script>
    lucide.createIcons();

    // FAQ Accordion functionality
    document.querySelectorAll('.faq-button').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('[data-lucide="chevron-down"]');

            // Toggle current item
            content.classList.toggle('hidden');
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add scroll effect to navigation
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (window.scrollY > 10) {
            nav.classList.add('shadow-md');
        } else {
            nav.classList.remove('shadow-md');
        }
    });
</script>
</body>

</html>
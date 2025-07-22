<!-- Mobile Navigation Component -->
<nav class="mobile-nav fixed bottom-0 left-0 right-0 bg-white/95 dark:bg-hcp-secondary-900/95 backdrop-blur-lg border-t border-hcp-secondary-200 dark:border-hcp-secondary-700 z-50 md:hidden transition-all duration-300"
     x-data="{ 
         activeTab: 'home',
         isVisible: true,
         lastScrollY: 0,
         init() {
             this.handleScroll();
             window.addEventListener('scroll', () => this.handleScroll());
         },
         handleScroll() {
             const currentScrollY = window.pageYOffset;
             const isScrollingDown = currentScrollY > this.lastScrollY;
             const isNearBottom = currentScrollY + window.innerHeight > document.body.scrollHeight - 100;
             
             if (isScrollingDown && currentScrollY > 100 && !isNearBottom) {
                 this.isVisible = false;
             } else {
                 this.isVisible = true;
             }
             
             this.lastScrollY = currentScrollY;
         },
         switchTab(tab) {
             this.activeTab = tab;
             this.hapticFeedback();
             
             // Track navigation
             if (typeof trackEvent === 'function') {
                 trackEvent('mobile_nav_click', { tab: tab });
             }
         },
         hapticFeedback() {
             if ('vibrate' in navigator) {
                 navigator.vibrate(50);
             }
         }
     }"
     :class="{ 'translate-y-full': !isVisible }">
    
    <!-- Safe area support for iOS -->
    <div class="pb-safe">
        <div class="flex items-center justify-around py-2 px-4">
            
            <!-- Home Tab -->
            <button @click="switchTab('home'); scrollToTop()" 
                    class="nav-tab flex flex-col items-center py-2 px-3 transition-all duration-200 relative overflow-hidden"
                    :class="activeTab === 'home' ? 'text-hcp-primary-500' : 'text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500'"
                    aria-label="Ir para o início">
                
                <!-- Ripple effect -->
                <div class="ripple-container absolute inset-0 rounded-lg"></div>
                
                <!-- Icon with animation -->
                <div class="relative z-10">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                
                <span class="text-xs mt-1 font-medium transition-all duration-200"
                      :class="activeTab === 'home' ? 'scale-110' : 'scale-100'">
                    Início
                </span>
                
                <!-- Active indicator -->
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-hcp-primary-500 rounded-full transition-all duration-200"
                     :class="activeTab === 'home' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
            </button>
            
            <!-- Features Tab -->
            <button @click="switchTab('features'); scrollToFeatures()" 
                    class="nav-tab flex flex-col items-center py-2 px-3 transition-all duration-200 relative overflow-hidden"
                    :class="activeTab === 'features' ? 'text-hcp-primary-500' : 'text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500'"
                    aria-label="Ver recursos">
                
                <div class="ripple-container absolute inset-0 rounded-lg"></div>
                
                <div class="relative z-10">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                
                <span class="text-xs mt-1 font-medium transition-all duration-200"
                      :class="activeTab === 'features' ? 'scale-110' : 'scale-100'">
                    Recursos
                </span>
                
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-hcp-primary-500 rounded-full transition-all duration-200"
                     :class="activeTab === 'features' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
            </button>
            
            <!-- Access Tab -->
            <button @click="switchTab('access'); openRequestAccessModal()" 
                    class="nav-tab flex flex-col items-center py-2 px-3 transition-all duration-200 relative overflow-hidden"
                    :class="activeTab === 'access' ? 'text-hcp-primary-500' : 'text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500'"
                    aria-label="Solicitar acesso">
                
                <div class="ripple-container absolute inset-0 rounded-lg"></div>
                
                <div class="relative z-10">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01 1l-3.99 5.33V22h6zm-8-6v6h-3v-6l3-4 3 4z"/>
                    </svg>
                </div>
                
                <span class="text-xs mt-1 font-medium transition-all duration-200"
                      :class="activeTab === 'access' ? 'scale-110' : 'scale-100'">
                    Acesso
                </span>
                
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-hcp-primary-500 rounded-full transition-all duration-200"
                     :class="activeTab === 'access' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
            </button>
            
            <!-- Contact Tab -->
            <button @click="switchTab('contact'); scrollToFooter()" 
                    class="nav-tab flex flex-col items-center py-2 px-3 transition-all duration-200 relative overflow-hidden"
                    :class="activeTab === 'contact' ? 'text-hcp-primary-500' : 'text-hcp-secondary-600 dark:text-hcp-secondary-400 hover:text-hcp-primary-500'"
                    aria-label="Ver informações de contato">
                
                <div class="ripple-container absolute inset-0 rounded-lg"></div>
                
                <div class="relative z-10">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                </div>
                
                <span class="text-xs mt-1 font-medium transition-all duration-200"
                      :class="activeTab === 'contact' ? 'scale-110' : 'scale-100'">
                    Contato
                </span>
                
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-hcp-primary-500 rounded-full transition-all duration-200"
                     :class="activeTab === 'contact' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
            </button>
        </div>
    </div>
    
    <!-- Floating Action Button (FAB) for quick actions -->
    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
        <button @click="switchTab('login'); window.location.href='{{ route('login') }}'" 
                class="fab bg-hcp-primary-500 hover:bg-hcp-primary-600 text-white w-12 h-12 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center"
                aria-label="Fazer login">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 19l8-8-8-8z"/>
            </svg>
        </button>
    </div>
</nav>

<style>
/* Mobile Navigation Styles */
.mobile-nav {
    /* Ensure it stays above other content */
    z-index: 50 !important;
    
    /* Smooth transitions */
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Ripple effect for nav tabs */
.nav-tab {
    position: relative;
    overflow: hidden;
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
}

.ripple-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.nav-tab:active .ripple-container::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    background: rgba(14, 165, 233, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    animation: ripple 0.6s ease-out;
}

@keyframes ripple {
    to {
        transform: translate(-50%, -50%) scale(4);
        opacity: 0;
    }
}

/* Floating Action Button */
.fab {
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
}

.fab:active {
    transform: scale(0.95);
}

/* Safe area support */
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom, 0px);
}

/* Dark mode adjustments */
[data-theme="dark"] .mobile-nav {
    background: rgba(15, 23, 42, 0.95);
    border-color: rgba(148, 163, 184, 0.2);
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .mobile-nav {
        border-width: 2px;
    }
    
    .nav-tab {
        border: 1px solid transparent;
    }
    
    .nav-tab:hover {
        border-color: currentColor;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .mobile-nav,
    .nav-tab,
    .fab {
        transition: none;
    }
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .mobile-nav .nav-tab {
        padding: 0.5rem 0.25rem;
    }
    
    .mobile-nav .nav-tab span {
        font-size: 0.75rem;
    }
}
</style>

<script>
// Mobile Navigation JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to nav tabs
    document.querySelectorAll('.nav-tab').forEach(tab => {
        tab.addEventListener('touchstart', function(e) {
            const ripple = this.querySelector('.ripple-container');
            if (ripple) {
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.touches[0].clientX - rect.left - size / 2;
                const y = e.touches[0].clientY - rect.top - size / 2;
                
                ripple.style.setProperty('--ripple-x', x + 'px');
                ripple.style.setProperty('--ripple-y', y + 'px');
                ripple.style.setProperty('--ripple-size', size + 'px');
            }
        });
    });
    
    // Handle swipe gestures for navigation
    let startX = 0;
    let startY = 0;
    let endX = 0;
    let endY = 0;
    
    document.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    }, { passive: true });
    
    document.addEventListener('touchend', function(e) {
        endX = e.changedTouches[0].clientX;
        endY = e.changedTouches[0].clientY;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const deltaX = endX - startX;
        const deltaY = endY - startY;
        const minSwipeDistance = 50;
        
        // Horizontal swipe
        if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
            if (deltaX > 0) {
                // Swipe right - go to home
                scrollToTop();
            } else {
                // Swipe left - go to features
                scrollToFeatures();
            }
        }
        
        // Vertical swipe down - show nav
        if (deltaY > minSwipeDistance && Math.abs(deltaY) > Math.abs(deltaX)) {
            const nav = document.querySelector('.mobile-nav');
            if (nav && nav.__x && nav.__x.$data) {
                nav.__x.$data.isVisible = true;
            }
        }
    }
    
    // Keyboard navigation support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' || e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            const navTabs = document.querySelectorAll('.nav-tab');
            const currentIndex = Array.from(navTabs).findIndex(tab => tab === document.activeElement);
            
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                e.preventDefault();
                navTabs[currentIndex - 1].focus();
            } else if (e.key === 'ArrowRight' && currentIndex < navTabs.length - 1) {
                e.preventDefault();
                navTabs[currentIndex + 1].focus();
            }
        }
    });
});

// Navigation functions
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    if (typeof trackEvent === 'function') {
        trackEvent('navigation', { action: 'scroll_to_top' });
    }
}

function scrollToFeatures() {
    const featuresSection = document.querySelector('section');
    if (featuresSection) {
        featuresSection.scrollIntoView({ behavior: 'smooth' });
        if (typeof trackEvent === 'function') {
            trackEvent('navigation', { action: 'scroll_to_features' });
        }
    }
}

function scrollToFooter() {
    const footer = document.querySelector('footer');
    if (footer) {
        footer.scrollIntoView({ behavior: 'smooth' });
        if (typeof trackEvent === 'function') {
            trackEvent('navigation', { action: 'scroll_to_footer' });
        }
    } else {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }
}

function openRequestAccessModal() {
    // Implementar abertura do modal de solicitação de acesso
    if (typeof showToast === 'function') {
        showToast('info', 'Funcionalidade em desenvolvimento');
    }
}
</script>
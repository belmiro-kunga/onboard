<!-- Splash Screen Component -->
<div id="splash-screen" 
     class="fixed inset-0 z-[9999] bg-gradient-to-br from-hcp-primary-500 via-hcp-primary-600 to-hcp-accent-500 flex items-center justify-center overflow-hidden">
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="floating-element-1 absolute top-1/4 left-1/4 w-8 h-8 bg-white/20 rounded-full animate-float-1"></div>
        <div class="floating-element-2 absolute top-1/3 right-1/4 w-6 h-6 bg-white/15 rounded-full animate-float-2"></div>
        <div class="floating-element-3 absolute bottom-1/4 left-1/3 w-10 h-10 bg-white/25 rounded-full animate-float-3"></div>
        <div class="floating-element-4 absolute bottom-1/3 right-1/3 w-4 h-4 bg-white/20 rounded-full animate-float-4"></div>
    </div>
    
    <!-- Main Content -->
    <div class="relative z-10 text-center">
        <!-- Logo Container -->
        <div class="mb-8 animate-logo-entrance">
            <div class="w-24 h-24 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl border border-white/30">
                <span class="text-white font-bold text-3xl">HCP</span>
            </div>
            
            <!-- App Name -->
            <h1 class="text-2xl font-bold text-white mb-2 animate-text-entrance">
                Hemera Capital Partners
            </h1>
            
            <!-- Subtitle -->
            <p class="text-white/80 text-sm animate-text-entrance-delay">
                Sistema de Onboarding Interativo
            </p>
        </div>
        
        <!-- Loading Progress -->
        <div class="w-64 mx-auto mb-8 animate-progress-entrance">
            <div class="bg-white/20 rounded-full h-2 overflow-hidden backdrop-blur-sm">
                <div id="progress-bar" class="bg-white h-full rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
            </div>
            <p class="text-white/70 text-xs mt-2">
                Carregando... <span id="progress-text">0</span>%
            </p>
        </div>
        
        <!-- Loading Dots -->
        <div class="flex justify-center space-x-2 animate-dots-entrance">
            <div class="w-2 h-2 bg-white/60 rounded-full animate-pulse"></div>
            <div class="w-2 h-2 bg-white/60 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-white/60 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
        </div>
    </div>
    
    <!-- Version Info -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/50 text-xs">
        v1.0.0 • Carregando recursos...
    </div>
</div>

<style>
/* Splash Screen Styles */
#splash-screen {
    /* Prevent scrolling during splash */
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
}

/* Floating animations */
@keyframes float-1 {
    0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
    50% { transform: translate3d(10px, -20px, 0) rotate(180deg); }
}

@keyframes float-2 {
    0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
    50% { transform: translate3d(-15px, -15px, 0) rotate(-90deg); }
}

@keyframes float-3 {
    0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
    50% { transform: translate3d(20px, -10px, 0) rotate(90deg); }
}

@keyframes float-4 {
    0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
    50% { transform: translate3d(-10px, -25px, 0) rotate(-180deg); }
}

.animate-float-1 {
    animation: float-1 6s ease-in-out infinite;
}

.animate-float-2 {
    animation: float-2 8s ease-in-out infinite;
    animation-delay: 1s;
}

.animate-float-3 {
    animation: float-3 7s ease-in-out infinite;
    animation-delay: 2s;
}

.animate-float-4 {
    animation: float-4 9s ease-in-out infinite;
    animation-delay: 3s;
}

/* Entrance animations */
@keyframes logo-entrance {
    0% {
        opacity: 0;
        transform: translate3d(0, 30px, 0) scale(0.8);
    }
    100% {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
    }
}

@keyframes text-entrance {
    0% {
        opacity: 0;
        transform: translate3d(0, 20px, 0);
    }
    100% {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

@keyframes progress-entrance {
    0% {
        opacity: 0;
        transform: translate3d(0, 15px, 0);
    }
    100% {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

@keyframes dots-entrance {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-logo-entrance {
    animation: logo-entrance 1s ease-out;
}

.animate-text-entrance {
    animation: text-entrance 1s ease-out 0.3s both;
}

.animate-text-entrance-delay {
    animation: text-entrance 1s ease-out 0.6s both;
}

.animate-progress-entrance {
    animation: progress-entrance 1s ease-out 0.9s both;
}

.animate-dots-entrance {
    animation: dots-entrance 1s ease-out 1.2s both;
}

/* Safe area support */
#splash-screen {
    padding-top: env(safe-area-inset-top, 0px);
    padding-bottom: env(safe-area-inset-bottom, 0px);
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
    #splash-screen {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    #splash-screen {
        background: #000000;
    }
    
    #splash-screen .bg-white\/20 {
        background: rgba(255, 255, 255, 0.5);
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .animate-float-1,
    .animate-float-2,
    .animate-float-3,
    .animate-float-4 {
        animation: none;
    }
    
    .animate-logo-entrance,
    .animate-text-entrance,
    .animate-text-entrance-delay,
    .animate-progress-entrance,
    .animate-dots-entrance {
        animation: none;
        opacity: 1;
        transform: none;
    }
}

/* Mobile optimizations */
@media (max-width: 480px) {
    #splash-screen .w-24 {
        width: 5rem;
        height: 5rem;
    }
    
    #splash-screen h1 {
        font-size: 1.5rem;
    }
    
    #splash-screen .w-64 {
        width: 16rem;
    }
}

/* Landscape mode adjustments */
@media (orientation: landscape) and (max-height: 500px) {
    #splash-screen .mb-8 {
        margin-bottom: 1rem;
    }
    
    #splash-screen .w-24 {
        width: 4rem;
        height: 4rem;
    }
    
    #splash-screen h1 {
        font-size: 1.25rem;
    }
}
</style>

<script>
// Splash Screen JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const splashScreen = document.getElementById('splash-screen');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    if (!splashScreen) return;
    
    let progress = 0;
    const duration = 2000; // 2 seconds
    const interval = 50; // Update every 50ms
    const steps = duration / interval;
    let currentStep = 0;
    
    function updateProgress() {
        currentStep++;
        progress = Math.min((currentStep / steps) * 100, 100);
        
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        
        if (progressText) {
            progressText.textContent = Math.round(progress);
        }
        
        if (currentStep < steps) {
            setTimeout(updateProgress, interval);
        } else {
            hideSplash();
        }
    }
    
    function hideSplash() {
        setTimeout(() => {
            splashScreen.style.opacity = '0';
            splashScreen.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                splashScreen.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
                
                // Track splash screen completion
                if (typeof trackEvent === 'function') {
                    trackEvent('splash_screen_complete', { duration: 2000 });
                }
            }, 500);
        }, 500);
    }
    
    // Start progress animation
    updateProgress();
    
    // Hide splash screen when page is fully loaded
    window.addEventListener('load', function() {
        const loadTime = performance.now();
        
        if (typeof trackEvent === 'function') {
            trackEvent('page_load_time', { 
                load_time: Math.round(loadTime),
                user_agent: navigator.userAgent 
            });
        }
    });
    
    // Fallback: hide splash screen after 3 seconds maximum
    setTimeout(function() {
        if (splashScreen.style.display !== 'none') {
            hideSplash();
        }
    }, 3000);
});
</script> 
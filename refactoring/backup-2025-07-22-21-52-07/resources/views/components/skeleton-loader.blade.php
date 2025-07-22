<!-- Skeleton Loader Component -->
<div id="skeleton-container" class="skeleton-loader" data-type="card" data-lines="3" data-animated="true">
    <!-- Skeleton content will be generated dynamically -->
</div>

<style>
/* Skeleton Loader Styles */
.skeleton-loader {
    /* Base styles */
    position: relative;
    overflow: hidden;
}

/* Skeleton animation */
.skeleton-line,
.skeleton-avatar,
.skeleton-button,
.skeleton-image {
    background: linear-gradient(90deg, 
        #f1f5f9 0%, 
        #e2e8f0 25%, 
        #f1f5f9 50%, 
        #e2e8f0 75%, 
        #f1f5f9 100%
    );
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
}

/* Dark mode skeleton */
[data-theme="dark"] .skeleton-line,
[data-theme="dark"] .skeleton-avatar,
[data-theme="dark"] .skeleton-button,
[data-theme="dark"] .skeleton-image {
    background: linear-gradient(90deg, 
        #334155 0%, 
        #475569 25%, 
        #334155 50%, 
        #475569 75%, 
        #334155 100%
    );
    background-size: 200% 100%;
}

/* Skeleton animation keyframes */
@keyframes skeleton-loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Card skeleton */
.skeleton-card-container {
    background: white;
    border-radius: 0.75rem;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

[data-theme="dark"] .skeleton-card-container {
    background: #1e293b;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.skeleton-card-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Text skeleton */
.skeleton-text-container {
    padding: 0.5rem 0;
}

/* Avatar skeleton */
.skeleton-avatar {
    flex-shrink: 0;
    border-radius: 50%;
}

/* Button skeleton */
.skeleton-button {
    display: inline-block;
    border-radius: 0.5rem;
}

/* Image skeleton */
.skeleton-image {
    background-color: #f1f5f9;
    border-radius: 0.5rem;
}

[data-theme="dark"] .skeleton-image {
    background-color: #334155;
}

/* List skeleton */
.skeleton-list-container {
    padding: 0.5rem 0;
}

/* Pulse animation for better visual feedback */
.skeleton-loader[data-animated="true"] .skeleton-line,
.skeleton-loader[data-animated="true"] .skeleton-avatar,
.skeleton-loader[data-animated="true"] .skeleton-button,
.skeleton-loader[data-animated="true"] .skeleton-image {
    animation: skeleton-loading 1.5s infinite;
}

/* Shimmer effect */
.skeleton-loader::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.4) 50%, 
        transparent 100%
    );
    animation: shimmer 2s infinite;
    pointer-events: none;
}

[data-theme="dark"] .skeleton-loader::before {
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.1) 50%, 
        transparent 100%
    );
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .skeleton-card-container {
        margin: 0.5rem 0;
    }
    
    .skeleton-line {
        height: 0.875rem;
    }
    
    .skeleton-avatar {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    .skeleton-button {
        height: 2rem;
        width: 4rem;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .skeleton-line,
    .skeleton-avatar,
    .skeleton-button,
    .skeleton-image {
        background: #d1d5db;
        border: 1px solid #9ca3af;
    }
    
    [data-theme="dark"] .skeleton-line,
    [data-theme="dark"] .skeleton-avatar,
    [data-theme="dark"] .skeleton-button,
    [data-theme="dark"] .skeleton-image {
        background: #4b5563;
        border: 1px solid #6b7280;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .skeleton-line,
    .skeleton-avatar,
    .skeleton-button,
    .skeleton-image {
        animation: none;
        background: #f1f5f9;
    }
    
    [data-theme="dark"] .skeleton-line,
    [data-theme="dark"] .skeleton-avatar,
    [data-theme="dark"] .skeleton-button,
    [data-theme="dark"] .skeleton-image {
        background: #334155;
    }
    
    .skeleton-loader::before {
        display: none;
    }
}

/* Loading states */
.skeleton-loader.loading {
    pointer-events: none;
}

.skeleton-loader.loaded {
    opacity: 0;
    transform: scale(0.95);
    transition: all 0.3s ease;
}

/* Custom sizes */
.skeleton-loader[data-size="sm"] .skeleton-line {
    height: 0.75rem;
}

.skeleton-loader[data-size="lg"] .skeleton-line {
    height: 1.25rem;
}

.skeleton-loader[data-size="xl"] .skeleton-line {
    height: 1.5rem;
}

/* Custom widths */
.skeleton-loader[data-width="xs"] .skeleton-line {
    width: 25%;
}

.skeleton-loader[data-width="sm"] .skeleton-line {
    width: 50%;
}

.skeleton-loader[data-width="md"] .skeleton-line {
    width: 75%;
}

.skeleton-loader[data-width="lg"] .skeleton-line {
    width: 90%;
}

.skeleton-loader[data-width="full"] .skeleton-line {
    width: 100%;
}
</style>

<script>
// Skeleton Loader JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Global skeleton functions
    window.SkeletonLoader = {
        // Show skeleton
        show: function(container, type = 'card', lines = 3) {
            const skeleton = document.createElement('div');
            skeleton.className = 'skeleton-loader';
            skeleton.setAttribute('data-type', type);
            skeleton.setAttribute('data-lines', lines);
            skeleton.setAttribute('data-animated', 'true');
            
            // Generate content based on type
            skeleton.innerHTML = this.generateContent(type, lines);
            
            container.appendChild(skeleton);
            return skeleton;
        },
        
        // Hide skeleton
        hide: function(skeleton) {
            if (skeleton) {
                skeleton.classList.add('loaded');
                setTimeout(() => {
                    if (skeleton.parentElement) {
                        skeleton.parentElement.removeChild(skeleton);
                    }
                }, 300);
            }
        },
        
        // Show multiple skeletons
        showMultiple: function(container, count, type = 'card', lines = 3) {
            const skeletons = [];
            for (let i = 0; i < count; i++) {
                skeletons.push(this.show(container, type, lines));
            }
            return skeletons;
        },
        
        // Hide multiple skeletons
        hideMultiple: function(skeletons) {
            skeletons.forEach(skeleton => this.hide(skeleton));
        },
        
        // Show skeleton for specific content
        showFor: function(container, type = 'card') {
            const originalContent = container.innerHTML;
            container.innerHTML = '';
            container.setAttribute('data-original-content', originalContent);
            
            const skeleton = this.show(container, type);
            skeleton.setAttribute('data-original-content', originalContent);
            
            return skeleton;
        },
        
        // Restore original content
        restore: function(skeleton) {
            const originalContent = skeleton.getAttribute('data-original-content');
            const container = skeleton.parentElement;
            
            if (container && originalContent) {
                this.hide(skeleton);
                setTimeout(() => {
                    container.innerHTML = originalContent;
                }, 300);
            }
        },
        
        // Generate content based on type
        generateContent: function(type, lines) {
            switch (type) {
                case 'card':
                    return this.generateCardContent();
                case 'text':
                    return this.generateTextContent(lines);
                case 'avatar':
                    return this.generateAvatarContent();
                case 'button':
                    return this.generateButtonContent();
                case 'image':
                    return this.generateImageContent();
                case 'list':
                    return this.generateListContent(lines);
                default:
                    return this.generateCardContent();
            }
        },
        
        generateCardContent: function() {
            return `
                <div class="skeleton-card-container">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="skeleton-avatar w-12 h-12"></div>
                        <div class="flex-1 space-y-2">
                            <div class="skeleton-line h-4 w-3/4"></div>
                            <div class="skeleton-line h-3 w-1/2"></div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="skeleton-line h-4 w-full"></div>
                        <div class="skeleton-line h-4 w-5/6"></div>
                        <div class="skeleton-line h-4 w-4/6"></div>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="skeleton-line h-3 w-1/4"></div>
                        <div class="skeleton-button h-8 w-20"></div>
                    </div>
                </div>
            `;
        },
        
        generateTextContent: function(lines) {
            let content = '<div class="skeleton-text-container space-y-2">';
            for (let i = 0; i < lines; i++) {
                const width = i === lines - 1 ? 'w-full' : 
                             i === lines - 2 ? 'w-5/6' : 
                             i === lines - 3 ? 'w-4/6' : 'w-3/4';
                content += `<div class="skeleton-line h-4 ${width}"></div>`;
            }
            content += '</div>';
            return content;
        },
        
        generateAvatarContent: function() {
            return '<div class="skeleton-avatar w-12 h-12"></div>';
        },
        
        generateButtonContent: function() {
            return '<div class="skeleton-button h-10 w-24"></div>';
        },
        
        generateImageContent: function() {
            return '<div class="skeleton-image w-full h-48"></div>';
        },
        
        generateListContent: function(lines) {
            let content = '<div class="skeleton-list-container space-y-3">';
            for (let i = 0; i < lines; i++) {
                content += `
                    <div class="flex items-center space-x-3 p-3 bg-white dark:bg-hcp-secondary-800 rounded-lg border border-hcp-secondary-200 dark:border-hcp-secondary-700">
                        <div class="skeleton-avatar w-10 h-10"></div>
                        <div class="flex-1 space-y-2">
                            <div class="skeleton-line h-4 w-3/4"></div>
                            <div class="skeleton-line h-3 w-1/2"></div>
                        </div>
                        <div class="skeleton-button h-6 w-16"></div>
                    </div>
                `;
            }
            content += '</div>';
            return content;
        }
    };
    
    // Auto-hide skeletons when content loads
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const skeleton = entry.target;
                const container = skeleton.parentElement;
                
                // Check if content has loaded
                if (container && container.children.length > 1) {
                    setTimeout(() => {
                        SkeletonLoader.hide(skeleton);
                    }, 500);
                }
            }
        });
    });
    
    // Observe all skeleton loaders
    document.querySelectorAll('.skeleton-loader').forEach(skeleton => {
        observer.observe(skeleton);
    });
    
    // Initialize existing skeleton loaders
    document.querySelectorAll('.skeleton-loader').forEach(skeleton => {
        const type = skeleton.getAttribute('data-type') || 'card';
        const lines = parseInt(skeleton.getAttribute('data-lines')) || 3;
        
        skeleton.innerHTML = SkeletonLoader.generateContent(type, lines);
    });
});

// Example usage:
// const skeleton = SkeletonLoader.show(document.getElementById('content'), 'card', 3);
// setTimeout(() => SkeletonLoader.hide(skeleton), 2000);
</script> 
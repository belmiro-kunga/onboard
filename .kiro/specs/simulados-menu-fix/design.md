# Design Document

## Overview

This design document outlines the solution for two issues in the admin panel:

1. The "Simulados" menu is not appearing in the admin panel navigation
2. There's an error in the quizzes index view due to an undefined `$stats` variable

The solution will ensure that the Simulados menu is properly displayed in both the desktop and mobile views of the admin panel, and will fix the error in the quizzes index view.

## Architecture

The application follows a standard Laravel MVC architecture:

- **Views**: Blade templates for rendering the UI
- **Controllers**: Handle requests and return responses
- **Routes**: Define the URL endpoints and map them to controller actions
- **Models**: Represent the data and business logic

For the admin panel, there are two layout files being used:
- `resources/views/layouts/admin.blade.php`: The main admin layout with sidebar navigation
- `resources/views/components/layouts/admin.blade.php`: A component-based admin layout with top navigation

## Components and Interfaces

### Admin Layout Components

1. **Sidebar Navigation (layouts/admin.blade.php)**
   - Contains collapsible menu items for various admin sections
   - Uses Alpine.js for interactive dropdown menus
   - Already has the Simulados menu item, but it might not be visible due to styling or JavaScript issues

2. **Top Navigation (components/layouts/admin.blade.php)**
   - Contains horizontal menu items for various admin sections
   - Missing the Simulados menu item, which needs to be added

3. **Mobile Navigation**
   - Responsive menu for mobile devices
   - Missing the Simulados menu item in the mobile view

### Admin Controllers

1. **SimuladoController**
   - Handles CRUD operations for simulados
   - Routes are already defined in web.php

2. **QuizController**
   - Handles CRUD operations for quizzes
   - The index method needs to be updated to provide the `$stats` variable to the view

## Data Models

No changes to data models are required for these fixes.

## Error Handling

The error in the quizzes index view is due to an undefined `$stats` variable. This needs to be fixed in the QuizController to ensure that the `$stats` array is properly defined and passed to the view.

## Testing Strategy

1. **Manual Testing**
   - Verify that the Simulados menu appears in the desktop navigation
   - Verify that the Simulados menu appears in the mobile navigation
   - Verify that the quizzes index page loads without errors
   - Verify that the statistics are correctly displayed on the quizzes index page

2. **Browser Compatibility**
   - Test on Chrome, Firefox, and Safari
   - Test on mobile devices to ensure responsive design works correctly

## Implementation Plan

### Fix 1: Add Simulados Menu to Top Navigation

1. Update `resources/views/components/layouts/admin.blade.php` to include the Simulados menu item in the top navigation.

### Fix 2: Add Simulados Menu to Mobile Navigation

1. Update the mobile menu in `resources/views/layouts/admin.blade.php` to include the Simulados menu item.

### Fix 3: Fix Undefined $stats Variable

1. Locate the QuizController's index method
2. Ensure that the `$stats` variable is properly defined and passed to the view
3. Update the view to handle cases where `$stats` might not be defined (add fallback values)

## Diagrams

### Admin Panel Navigation Structure

```
Admin Panel
├── Desktop View
│   ├── Top Navigation (components/layouts/admin.blade.php)
│   │   ├── Dashboard
│   │   ├── Quizzes
│   │   ├── Simulados (to be added)
│   │   ├── Usuários
│   │   └── Relatórios
│   └── Sidebar Navigation (layouts/admin.blade.php)
│       ├── Dashboard
│       ├── Usuários
│       ├── Módulos (collapsible)
│       ├── Quizzes (collapsible)
│       ├── Simulados (collapsible)
│       ├── Certificados
│       ├── Relatórios
│       └── Configurações
└── Mobile View
    └── Mobile Menu
        ├── Dashboard
        ├── Usuários
        ├── Quizzes
        ├── Simulados (to be added)
        └── Relatórios
```
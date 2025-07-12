# Theme System Documentation

## Overview

This document provides an overview of the theme system implemented in the application. The system includes multiple themes, day/night mode based on client's local time, and accessibility features.

## Features

1. **Multiple Themes**:
   - Light: Default light theme
   - Dark: Dark theme for reduced eye strain
   - Children: Bright, colorful theme with rounded corners and larger text for children
   - Youth: Modern, vibrant theme with a dark sidebar for teenagers and young adults
   - Adults: Professional, clean theme with subtle rounded corners for adults

2. **Automatic Theme Switching**:
   - System: Follows the user's system preference (light/dark)
   - Auto: Switches between light and dark based on the time of day (light during day, dark during night)

3. **Accessibility Features**:
   - Font Size: Normal, Large, Extra Large
   - Contrast: Normal, High Contrast

## Components

### ThemeSwitcher Component

The `ThemeSwitcher` component provides a dropdown interface for users to select their preferred theme, font size, and contrast settings.

**Location**: `resources/js/components/ThemeSwitcher.vue`

**Usage**:
```vue
<template>
  <ThemeSwitcher />
</template>

<script setup>
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
</script>
```

### useAppearance Composable

The `useAppearance` composable manages the theme state and provides functions to update the theme, font size, and contrast settings.

**Location**: `resources/js/composables/useAppearance.ts`

**Usage**:
```typescript
import { useAppearance } from '@/composables/useAppearance';

const {
  appearance,
  fontSize,
  contrast,
  updateAppearance,
  updateFontSize,
  updateContrast
} = useAppearance();

// Update theme
updateAppearance('dark');

// Update font size
updateFontSize('large');

// Update contrast
updateContrast('high');
```

## CSS Classes

The theme system uses CSS classes to apply different styles based on the selected theme:

- `.dark`: Dark theme
- `.theme-children`: Children theme
- `.theme-youth`: Youth theme
- `.theme-adults`: Adults theme
- `.font-size-large`: Large font size
- `.font-size-x-large`: Extra large font size
- `.high-contrast`: High contrast mode

## Implementation Details

### Theme Initialization

The theme is initialized in `resources/js/app.ts` using the `initializeTheme` function from the `useAppearance` composable:

```typescript
import { initializeTheme } from './composables/useAppearance';

// Initialize theme
initializeTheme();
```

### Theme Storage

The theme preferences are stored in:
- `localStorage` for client-side persistence
- Cookies for server-side rendering

### Automatic Theme Switching

The automatic theme switching is implemented in the `updateTheme` function in the `useAppearance` composable. It checks the current time and sets the theme accordingly:

- Between 7 AM and 7 PM: Light theme
- Between 7 PM and 7 AM: Dark theme

## Testing

To test the theme system:

1. **Theme Switching**:
   - Click on the theme switcher button in the navbar
   - Select different themes from the dropdown
   - Verify that the theme changes accordingly

2. **Automatic Theme Switching**:
   - Select "Auto" from the theme dropdown
   - Change your system time to test day/night switching
   - Verify that the theme changes based on the time of day

3. **Accessibility Features**:
   - Select different font sizes from the dropdown
   - Verify that the text size changes accordingly
   - Select high contrast mode
   - Verify that the contrast increases

## Troubleshooting

If the theme system is not working correctly:

1. Check the browser console for errors
2. Verify that the `initializeTheme` function is called in `app.ts`
3. Clear browser cache and localStorage
4. Ensure that the CSS classes are properly defined in `app.css`

## Future Improvements

Potential improvements to the theme system:

1. Add more themes
2. Allow users to create custom themes
3. Implement theme transitions
4. Add more accessibility features (e.g., reduced motion, dyslexia-friendly font)

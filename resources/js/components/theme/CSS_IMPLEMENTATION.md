# CSS Implementation Guide for Theme System

## Overview

This guide explains how to implement and extend the theme system in the application. The theme system uses CSS variables to define colors, spacing, and other design elements, making it easy to create consistent and customizable interfaces.

## Theme Structure

The theme system consists of:

1. **Base Variables**: Defined in `:root` selector
2. **Dark Theme**: Defined in `.dark` selector
3. **Age-Specific Themes**: 
   - Children: `.theme-children`
   - Youth: `.theme-youth`
   - Adults: `.theme-adults`
4. **Accessibility Features**:
   - Font sizes: `.font-size-large`, `.font-size-x-large`
   - Contrast: `.high-contrast`

## Theme Variables

Here are the main CSS variables available in all themes:

```
--background: #ffffff;                /* Background color for the page */
--foreground: #0a0a0a;                /* Text color for the page */
--card: #ffffff;                      /* Background color for cards */
--card-foreground: #0a0a0a;           /* Text color for cards */
--popover: #ffffff;                   /* Background color for popovers */
--popover-foreground: #0a0a0a;        /* Text color for popovers */
--primary: #171717;                   /* Primary brand color */
--primary-foreground: #fafafa;        /* Text color on primary color */
--secondary: #ebebeb;                 /* Secondary color */
--secondary-foreground: #171717;      /* Text color on secondary color */
--muted: #f5f5f5;                     /* Muted background color */
--muted-foreground: #737373;          /* Text color on muted background */
--accent: #f5f5f5;                    /* Accent color */
--accent-foreground: #171717;         /* Text color on accent color */
--destructive: #ef4444;               /* Color for destructive actions */
--destructive-foreground: #fafafa;    /* Text color on destructive color */
--border: #ececec;                    /* Border color */
--input: #e5e5e5;                     /* Input border color */
--ring: #0a0a0a;                      /* Focus ring color */
--radius: 0.5rem;                     /* Border radius for components */
```

## Using Theme Variables

To use theme variables in your CSS:

```css
.my-component {
    background-color: var(--background);
    color: var(--foreground);
    border: 1px solid var(--border);
    border-radius: var(--radius);
}

.my-button {
    background-color: var(--primary);
    color: var(--primary-foreground);
}
```

## Theme Implementation

The theme system is implemented in `resources/css/app.css` and uses the following structure:

```css
:root {
    /* Light theme variables */
    --background: #ffffff;
    --foreground: #0a0a0a;
    /* Other variables... */
}

.dark {
    /* Dark theme variables */
    --background: #0a0a0a;
    --foreground: #fafafa;
    /* Other variables... */
}

.theme-children {
    /* Children theme variables */
    --background: #f0f9ff;
    --foreground: #0c4a6e;
    /* Other variables... */
    
    /* Larger font and rounded corners for children */
    font-size: 110%;
    --radius: 1rem;
}

.theme-youth {
    /* Youth theme variables */
    --background: #fafafa;
    --foreground: #1e293b;
    /* Other variables... */
}

.theme-adults {
    /* Adults theme variables */
    --background: #f8fafc;
    --foreground: #1e293b;
    /* Other variables... */
}
```

## Accessibility Features

### Font Sizes

Font sizes are implemented using CSS classes:

```css
.font-size-large {
    font-size: 112.5%; /* 1.125rem for base 16px */
}

.font-size-x-large {
    font-size: 125%; /* 1.25rem for base 16px */
}
```

### Contrast

High contrast mode is implemented using a CSS class:

```css
.high-contrast {
    --foreground: #000000;
    --background: #ffffff;
    --primary: #000000;
    --primary-foreground: #ffffff;
    --secondary: #000000;
    --secondary-foreground: #ffffff;
    /* Other high contrast adjustments... */
}

.dark.high-contrast {
    --foreground: #ffffff;
    --background: #000000;
    /* Dark mode high contrast adjustments... */
}
```

## Theme Switching

Theme switching is handled by the `useAppearance` composable in `resources/js/composables/useAppearance.ts`. This composable:

1. Manages the current theme state
2. Provides functions to update the theme
3. Applies the appropriate CSS classes to the document

```typescript
// Example of how the theme is applied
export function updateTheme(value: Appearance, fontSize: FontSize = 'normal', contrast: Contrast = 'normal') {
    // Remove existing theme classes
    document.documentElement.classList.remove('dark', 'theme-children', 'theme-youth', 'theme-adults');
    
    // Apply the selected theme
    if (value === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (value === 'children') {
        document.documentElement.classList.add('theme-children');
    } else if (value === 'youth') {
        document.documentElement.classList.add('theme-youth');
    } else if (value === 'adults') {
        document.documentElement.classList.add('theme-adults');
    }
    
    // Apply font size
    document.documentElement.classList.remove('font-size-large', 'font-size-x-large');
    if (fontSize === 'large') {
        document.documentElement.classList.add('font-size-large');
    } else if (fontSize === 'x-large') {
        document.documentElement.classList.add('font-size-x-large');
    }
    
    // Apply contrast
    document.documentElement.classList.remove('high-contrast');
    if (contrast === 'high') {
        document.documentElement.classList.add('high-contrast');
    }
}
```

## Adding a New Theme

To add a new theme:

1. Define the theme variables in `resources/css/app.css`:
   ```css
   .theme-new-theme {
       --background: #f9f9f9;
       --foreground: #333333;
       /* Define all necessary variables */
   }
   ```

2. Update the `useAppearance.ts` composable to include the new theme:
   ```typescript
   // In resources/js/composables/useAppearance.ts
   type ThemeType = 'light' | 'dark' | 'children' | 'youth' | 'adults' | 'new-theme';

   // Update the updateTheme function
   export function updateTheme(value: Appearance, fontSize: FontSize = 'normal', contrast: Contrast = 'normal') {
       // ...existing code...

       // Add your new theme
       if (value === 'new-theme') {
           document.documentElement.classList.add('theme-new-theme');
       }
   }

   // Update the themeOptions array in ThemeSwitcher.vue
   const themeOptions = [
       // ...existing options...
       { value: 'new-theme', label: 'New Theme', icon: YourIcon },
   ];
   ```

## Best Practices

1. **Use CSS Variables**: Always use CSS variables for theme-related styles to ensure consistency.
2. **Test All Themes**: When adding new components, test them in all themes to ensure they look good.
3. **Consider Accessibility**: Ensure your components work well with different font sizes and contrast settings.
4. **Use Semantic Colors**: Use semantic color variables (like `--primary`, `--accent`) rather than specific colors.
5. **Provide Sufficient Contrast**: Ensure text has sufficient contrast against its background in all themes.

## Troubleshooting

- **Theme Not Applying**: Make sure the correct CSS class is being added to the document element.
- **Inconsistent Colors**: Check that you're using the theme variables consistently.
- **Dark Mode Issues**: Ensure dark mode styles are properly defined for all components.
- **Accessibility Problems**: Test with different font sizes and contrast settings.

## Resources

- [CSS Custom Properties (Variables)](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)
- [Color Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [Web Content Accessibility Guidelines (WCAG)](https://www.w3.org/WAI/standards-guidelines/wcag/)

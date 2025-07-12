import { onMounted, onUnmounted, ref } from 'vue';

// Extended appearance types to include new themes
type ThemeType = 'light' | 'dark' | 'children' | 'youth' | 'adults';
type Appearance = ThemeType | 'system' | 'auto';

// Font size options for accessibility
type FontSize = 'normal' | 'large' | 'x-large';

// Contrast options for accessibility
type Contrast = 'normal' | 'high';

export function updateTheme(value: Appearance, fontSize: FontSize = 'normal', contrast: Contrast = 'normal') {
    if (typeof window === 'undefined') {
        return;
    }

    // Remove all theme classes first
    document.documentElement.classList.remove('dark', 'theme-children', 'theme-youth', 'theme-adults');

    // Remove font size classes
    document.documentElement.classList.remove('font-size-large', 'font-size-x-large');

    // Remove contrast classes
    document.documentElement.classList.remove('high-contrast');

    // Apply font size
    if (fontSize === 'large') {
        document.documentElement.classList.add('font-size-large');
    } else if (fontSize === 'x-large') {
        document.documentElement.classList.add('font-size-x-large');
    }

    // Apply contrast
    if (contrast === 'high') {
        document.documentElement.classList.add('high-contrast');
    }

    // Handle auto mode (based on time of day)
    if (value === 'auto') {
        const currentHour = new Date().getHours();
        // Night mode between 7 PM (19) and 7 AM (7)
        const isDark = currentHour >= 19 || currentHour < 7;
        document.documentElement.classList.toggle('dark', isDark);
        return;
    }

    // Handle system preference
    if (value === 'system') {
        const mediaQueryList = window.matchMedia('(prefers-color-scheme: dark)');
        const systemTheme = mediaQueryList.matches ? 'dark' : 'light';
        document.documentElement.classList.toggle('dark', systemTheme === 'dark');
        return;
    }

    // Handle specific themes
    if (value === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (value === 'children') {
        document.documentElement.classList.add('theme-children');
    } else if (value === 'youth') {
        document.documentElement.classList.add('theme-youth');
    } else if (value === 'adults') {
        document.documentElement.classList.add('theme-adults');
    }
}

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const mediaQuery = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.matchMedia('(prefers-color-scheme: dark)');
};

// Get stored theme preferences
const getStoredAppearance = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('appearance') as Appearance | null;
};

const getStoredFontSize = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('fontSize') as FontSize | null;
};

const getStoredContrast = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('contrast') as Contrast | null;
};

const handleSystemThemeChange = () => {
    const currentAppearance = getStoredAppearance();
    const currentFontSize = getStoredFontSize();
    const currentContrast = getStoredContrast();

    updateTheme(
        currentAppearance || 'system',
        currentFontSize || 'normal',
        currentContrast || 'normal'
    );
};

export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Initialize theme from saved preferences or defaults
    const savedAppearance = getStoredAppearance();
    const savedFontSize = getStoredFontSize();
    const savedContrast = getStoredContrast();

    // Set up system theme change listener
    mediaQuery()?.addEventListener('change', handleSystemThemeChange);

    // Clear any existing interval (just in case)
    if (autoThemeInterval !== null) {
        clearInterval(autoThemeInterval);
        autoThemeInterval = null;
    }

    // Apply the theme and set up interval if needed
    if (savedAppearance === 'auto') {
        // Apply theme immediately based on current time
        updateTheme('auto', savedFontSize || 'normal', savedContrast || 'normal');

        // Set up interval to check time every hour
        autoThemeInterval = window.setInterval(() => {
            updateTheme('auto', savedFontSize || 'normal', savedContrast || 'normal');
        }, 60 * 60 * 1000); // Check every hour
    } else {
        // Apply the theme directly
        updateTheme(
            savedAppearance || 'system',
            savedFontSize || 'normal',
            savedContrast || 'normal'
        );
    }
}

// Reactive state
const appearance = ref<Appearance>('system');
const fontSize = ref<FontSize>('normal');
const contrast = ref<Contrast>('normal');
// Track the auto theme interval
let autoThemeInterval: number | null = null;

export function useAppearance() {
    onMounted(() => {
        // Load saved preferences
        const savedAppearance = localStorage.getItem('appearance') as Appearance | null;
        const savedFontSize = localStorage.getItem('fontSize') as FontSize | null;
        const savedContrast = localStorage.getItem('contrast') as Contrast | null;

        if (savedAppearance) {
            appearance.value = savedAppearance;
        }

        if (savedFontSize) {
            fontSize.value = savedFontSize;
        }

        if (savedContrast) {
            contrast.value = savedContrast;
        }

        // Set up auto theme interval if needed
        if (appearance.value === 'auto' && autoThemeInterval === null) {
            autoThemeInterval = window.setInterval(() => {
                updateTheme('auto', fontSize.value, contrast.value);
            }, 60 * 60 * 1000); // Check every hour
        }
    });

    // Clean up interval when component is unmounted
    onUnmounted(() => {
        if (autoThemeInterval !== null) {
            clearInterval(autoThemeInterval);
            autoThemeInterval = null;
        }
    });

    // Update theme appearance
    function updateAppearance(value: Appearance) {
        appearance.value = value;

        // Store in localStorage for client-side persistence
        localStorage.setItem('appearance', value);

        // Store in cookie for SSR
        setCookie('appearance', value);

        // Clear existing auto theme interval if it exists
        if (autoThemeInterval !== null) {
            clearInterval(autoThemeInterval);
            autoThemeInterval = null;
        }

        // Set up interval for auto mode
        if (value === 'auto') {
            // Apply theme immediately based on current time
            updateTheme(value, fontSize.value, contrast.value);

            // Set up interval to check time every hour
            autoThemeInterval = window.setInterval(() => {
                updateTheme('auto', fontSize.value, contrast.value);
            }, 60 * 60 * 1000); // Check every hour
        } else {
            // Apply the theme with current accessibility settings
            updateTheme(value, fontSize.value, contrast.value);
        }
    }

    // Update font size
    function updateFontSize(value: FontSize) {
        fontSize.value = value;

        // Store in localStorage
        localStorage.setItem('fontSize', value);

        // Store in cookie for SSR
        setCookie('fontSize', value);

        // Apply the theme with updated font size
        updateTheme(appearance.value, value, contrast.value);
    }

    // Update contrast
    function updateContrast(value: Contrast) {
        contrast.value = value;

        // Store in localStorage
        localStorage.setItem('contrast', value);

        // Store in cookie for SSR
        setCookie('contrast', value);

        // Apply the theme with updated contrast
        updateTheme(appearance.value, fontSize.value, value);
    }

    return {
        appearance,
        fontSize,
        contrast,
        updateAppearance,
        updateFontSize,
        updateContrast
    };
}

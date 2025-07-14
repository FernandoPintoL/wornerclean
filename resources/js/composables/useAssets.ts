import { usePage } from '@inertiajs/vue3';

export function useAssets() {
    const page = usePage();

    const asset = (path: string): string => {
        const baseUrl = page.props.ziggy.url || '';

        // Asegurarse de que el path comience con /
        const normalizedPath = path.startsWith('/') ? path : `/${path}`;

        return `${baseUrl}${normalizedPath}`;
    };

    return {
        asset
    };
}

import { usePage } from '@inertiajs/vue3';

export function transf(key: string, replacements: Record<string, string> = {}): string {
    const page = usePage();
    const translations = (page.props.translations || {}) as Record<string, string>;
    
    let translation = translations[key] || key;

    Object.keys(replacements).forEach((r) => {
        translation = translation.replace(`:${r}`, replacements[r]);
    });

    return translation;
}

/**
 * Short alias for transf
 */
export const __ = transf;

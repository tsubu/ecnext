import { usePage } from '@inertiajs/react';

/**
 * A simple translation helper for React components.
 * Usage: 
 *   const { __ } = useTranslation();
 *   return <div>{__('Order History')}</div>;
 */
export const useTranslation = () => {
    const { props } = usePage();
    const translations = props.translations || {};

    const __ = (key, replace = {}) => {
        let translation = translations[key] || key;

        Object.keys(replace).forEach((r) => {
            translation = translation.replace(`:${r}`, replace[r]);
        });

        return translation;
    };

    return { __ };
};

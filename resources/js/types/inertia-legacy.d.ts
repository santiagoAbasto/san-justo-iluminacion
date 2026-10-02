import '@inertiajs/core';
import '@inertiajs/react';
import type { Page } from '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps {
        [key: string]: any;
    }
}

declare module '@inertiajs/react' {
    function useForm(...args: any[]): any;
    function usePage(): Page<Record<string, any>>;
}

import 'ziggy-js';

declare global {
    function route(
        name: string,
        params?: any,
        absolute?: boolean
    ): string;
}

export {};

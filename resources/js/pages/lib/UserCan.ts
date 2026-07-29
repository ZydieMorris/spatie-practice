import { usePage } from '@inertiajs/vue3';

export function UserCan() {
    const page = usePage();

    const can = (permission: string): boolean => {
        const permissions = page.props.auth.permissions as string[];

        return permissions.includes(permission);
    };

    return {
        can,
    };
}
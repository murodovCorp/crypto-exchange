import React from 'react';
import {Head} from '@inertiajs/inertia-react';

export default function Guest({ children, title }) {
    return (
        <div className="bg-white">
            <div className="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
                <Head title={title} />
                { title && <h2 className="text-2xl font-bold tracking-tight text-gray-900">{title}</h2>}
                {children}
            </div>
        </div>
    );
}

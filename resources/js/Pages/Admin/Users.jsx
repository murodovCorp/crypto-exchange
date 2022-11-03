import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';
import Table from "@/Components/Admin/Users/Table";
import {Inertia} from "@inertiajs/inertia";

export default function Users(props) {

    function handleSubmit(e) {
        e.preventDefault()
        Inertia.get(route('search'))
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
        >
            <Head title="Пользователи" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-12">
                    <div className="bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <form onSubmit={handleSubmit}>
                                <div className="form-group">
                                    <input
                                        type="text"
                                        name="query"
                                        className="form-control"
                                        placeholder="Search..."
                                        value={props.query}
                                    />
                                    <button type="submit">Искать</button>
                                </div>
                            </form>
                            <Table
                                models={props?.users}
                                isEdit={false}
                                isCreate={false}
                                Title='Пользователи'
                                Description='Список всех пользователей.'
                            />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

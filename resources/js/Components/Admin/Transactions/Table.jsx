import React, {useState} from 'react';
import Pagination from "@/Components/Pagination";
import _ from "lodash";
import StatusModal from "@/Components/Admin/Transactions/StatusModal";

export default function Table({
                                  models = [],
                                  statuses = [],
                                  isEdit = true,
                                  isCreate = true,
                                  Title = '',
                                  Description = ''
                              }) {

    const [values, setValues] = useState({
        id: null,
        isOpen: false,
        status: '',
        message: '',
    })

    function statusClick(id, message, status) {
        setValues(v => ({
            id,
            isOpen: true,
            status,
            message,
        }))

        return values
    }

    return (
        <div className="px-4 sm:px-6 lg:px-8">
            <div className="sm:flex sm:items-center">
                <div className="sm:flex-auto">
                    <h1 className="text-xl font-semibold text-gray-900">{Title}</h1>
                    <p className="mt-2 text-sm text-gray-700">
                        {Description}
                    </p>
                </div>
                {isCreate && <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <button
                        type="button"
                        className="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                    >
                        Добавить
                    </button>
                </div>}
            </div>
            <div className="mt-8 flex flex-col">
                <div className="overflow-x-auto"/>
                <div className="-my-2 -mx-4 sm:-mx-6 lg:-mx-8 overflow-x-auto">
                    <div className="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div className="shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">

                            <StatusModal values={values} setValues={setValues} statuses={statuses}/>

                            <table className="min-w-full divide-y divide-gray-300">
                                <thead className="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        ID
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Пользователь
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Промокод
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Статус
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Отдаю -> Получаю
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Откуда -> Куда
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-2 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Сумма
                                    </th>
                                    <th scope="col" className="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        {isEdit && <span className="sr-only">Edit</span>}
                                    </th>
                                </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                {models?.data?.map((model, key) => {
                                    let name = model?.data?.name + ' / ' + model?.data?.surname + ' / ' + model?.data?.email;

                                    if (_.isEmpty(model?.data?.name) && _.isEmpty(model?.data?.surname) && _.isEmpty(model?.data?.email)) {
                                        name = 'Не задано'
                                    }

                                    let crypto = model?.data?.address + ' -> ' + model?.data?.card_number
                                    if (_.isEmpty(model?.data?.address) && _.isEmpty(model?.data?.card_number)) {
                                        crypto = 'Не задано' + ' -> ' + 'Не задано'
                                    }

                                    let cardNumber = model?.data?.card_number + ' -> ' + model?.data?.address
                                    if (_.isEmpty(model?.data?.card_number) && _.isEmpty(model?.data?.address)) {
                                        cardNumber = 'Не задано' + ' -> ' + 'Не задано'
                                    }

                                    let from_to = model?.type === 'UZS'
                                        ? cardNumber
                                        : crypto

                                    let id = model?.id;
                                    let statusColor = ''
                                    switch (model?.status) {
                                        case 'create':
                                            statusColor = 'text-cyan-600'
                                            break
                                        case 'error':
                                            statusColor = 'text-red-500'
                                            break
                                        case 'inProgress':
                                            statusColor = 'text-indigo-500'
                                            break
                                        case 'ready':
                                            statusColor = 'text-green-500'
                                            break

                                    }

                                    return <tr key={key}>
                                        <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {id}
                                        </td>
                                        <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {name}
                                        </td>
                                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {model?.promo_code ?? 'Не задано'}
                                        </td>
                                        <td className={`whitespace-nowrap px-3 py-4 text-sm text-gray-500 cursor-pointer ${statusColor}`}
                                            onClick={() => statusClick(id, model?.data?.message, model?.status)}
                                        >
                                            {statuses[model?.status] ?? 'Не задано'}
                                        </td>
                                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {model?.type ?? 'Не задано'} -> {model?.data?.asset ?? 'Не задано'}
                                            <span style={{paddingRight: '100px'}}/>
                                        </td>
                                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {from_to}
                                        </td>
                                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {model?.data?.amount ?? 'Не задано'}
                                        </td>

                                    </tr>
                                })}
                                </tbody>
                            </table>
                            <Pagination links={models.links}
                                        next_page_url={models?.next_page_url}
                                        prev_page_url={models?.prev_page_url}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

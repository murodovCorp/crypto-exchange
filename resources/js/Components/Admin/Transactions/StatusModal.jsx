import {Fragment, useRef, useState} from 'react'
import {Dialog, Transition} from '@headlessui/react'
import {ExclamationTriangleIcon} from '@heroicons/react/24/outline'
import {Inertia} from "@inertiajs/inertia";

export default function StatusModal({values, setValues, statuses}) {

    const cancelButtonRef = useRef(null)

    const [errorMessage, setErrorMessage] = useState('')

    function handleChange(e) {
        const key = e.target.id;
        const value = e.target.value
        setValues(values => ({
            ...values,
            [key]: value,
        }))
    }

    const [transaction, setTransaction] = useState({})

    function handleSubmit(e) {
        e.preventDefault()
        axios.post(route('transactions.statusChange', values.id), values).then(response => {
            setValues(values => ({
                ...values,
                isOpen: false,
            }))

            Inertia.get(route('transactions.index'))
        }).catch(error => {
            setErrorMessage(errorMessage => `Ошибка: ${error?.response?.data?.message}`)
        })

    }

    return (
        <Transition.Root show={values.isOpen ?? false} as={Fragment}>
            <Dialog as="div" className="relative z-10" initialFocus={cancelButtonRef} onClose={setValues}>
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
                </Transition.Child>

                <div className="fixed inset-0 z-10 overflow-y-auto">
                    <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enterTo="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-200"
                            leaveFrom="opacity-100 translate-y-0 sm:scale-100"
                            leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        >
                            <Dialog.Panel
                                className="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                <div className="sm:flex sm:items-start">
                                    <div
                                        className="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <ExclamationTriangleIcon className="h-6 w-6 text-red-600" aria-hidden="true"/>
                                    </div>
                                    <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <Dialog.Title as="h3" className="text-lg font-medium leading-6 text-gray-900">
                                            Изменить статус
                                        </Dialog.Title>
                                        <div className="mt-2">
                                            <p className="text-sm text-gray-500">
                                                Вы уверены что провели транзакцию или выявили ошибку в запросе и
                                                подтверждаете отправку уведомления пользователю ?
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div className="mt-2">
                                    <p className="text-sm text-red-600">
                                        {errorMessage}
                                    </p>
                                </div>
                                <div className="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <form className="space-y-8 divide-y divide-gray-200" onSubmit={handleSubmit}>
                                        <div className="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                                            <div className="space-y-6 pt-2 sm:space-y-5 sm:pt-2">
                                                <div className="space-y-6 sm:space-y-5">
                                                    <div
                                                        className="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                                        <label htmlFor="message"
                                                               className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                            Сообщение для пользователя
                                                        </label>
                                                        <div className="mt-1 sm:col-span-2 sm:mt-0">
                                                            <input
                                                                type="text"
                                                                name="message"
                                                                id="message"
                                                                required={true}
                                                                value={values.message}
                                                                onChange={handleChange}
                                                                autoComplete="given-name"
                                                                className="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div
                                                        className="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                                        <label htmlFor="status"
                                                               className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                            Статус
                                                        </label>
                                                        <div className="mt-1 sm:col-span-2 sm:mt-0">
                                                            <select
                                                                id="status"
                                                                name="status"
                                                                autoComplete="status"
                                                                value={values.status}
                                                                onChange={handleChange}
                                                                className="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                            >
                                                                {Object.keys(statuses).map((status, key) => (
                                                                    key !== 'create' && <option value={status}
                                                                                                key={key}>{statuses[status]}</option>
                                                                ))}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="float-right" style={{border: 'none'}}>
                                            <button
                                                type="button"
                                                className="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm"
                                                onClick={() => setValues(values => ({
                                                    ...values,
                                                    isOpen: false,
                                                }))}
                                                ref={cancelButtonRef}
                                            >
                                                Отменить
                                            </button>
                                            <button
                                                type="submit"
                                                className="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm mr-2"
                                            >
                                                Отправить
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition.Root>
    )
}

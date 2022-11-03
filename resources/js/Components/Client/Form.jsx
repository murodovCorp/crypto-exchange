import React from 'react';
import Select from "@/Components/Client/Select";
import _ from "lodash";
import {Inertia} from "@inertiajs/inertia";

export default function Form({values, setValues, cryptocurrencies, currencies}) {

    function handleAmount(e, data) {

        const key = e.target.id
        const value = e.target.value

        const priceUsd = _.find(data, {asset_id: values[key]})?.price_usd

        setValues(values => ({
            ...values,
            ['price_usd']: priceUsd,
            [key]: value,
        }))

    }

    function handleChange(e) {

        setValues(values => ({
            ...values,
            [e.target.id]: e.target.value,
        }))

    }

    function handleSubmit(e) {
        e.preventDefault()
        Inertia.post(route('client.process'))
    }

    return (
        <form className="space-y-8 divide-y divide-gray-200" onSubmit={handleSubmit}>
            <div className="flex justify-between">
                <h2 className="text-2xl font-bold tracking-tight text-gray-900 pr-2">Отдаёте</h2>
                <h2 className="text-2xl font-bold tracking-tight text-gray-900 pr-2">Получаете</h2>
            </div>

            <div className="grid grid-cols-6 gap-6">

                <div className="col-span-6 sm:col-span-3">
                    <Select items={cryptocurrencies} values={values} setValues={setValues} nameKey={'from'}/>
                </div>

                <div className="col-span-6 sm:col-span-3">
                    <input
                        type="text"
                        name="amount_from"
                        className="block w-full border border-gray-300 py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none"
                        placeholder="0.00"
                        aria-describedby="price-currency"
                        onChange={(event => handleAmount(event, cryptocurrencies))}
                        id="amount_from"
                        value={values.amount_from}
                        required
                    />
                </div>

                <div className="col-span-6 sm:col-span-3">
                    <Select items={currencies} values={values} setValues={setValues} nameKey={'to'}/>
                </div>

                <div className="col-span-6 sm:col-span-3">
                    <input
                        type="text"
                        name="amount_to"
                        className="block w-full border border-gray-300 py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none"
                        placeholder="0.00"
                        aria-describedby="price-currency"
                        onChange={(event => handleAmount(event, currencies))}
                        id="amount_to"
                        value={values.amount_to}
                        required
                    />
                </div>

            </div>

            <div className="grid grid-cols-6 gap-6 mt-6">

                <div className="col-span-6 sm:col-span-3">
                    <input
                        type="email"
                        name="email"
                        className="block w-full border border-gray-300 py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none"
                        placeholder="E-mail - example@gmail.com"
                        id="email"
                        onChange={handleChange}
                        value={values.email}
                        required
                    />
                </div>

                <div className="col-span-6 sm:col-span-3">
                    <input
                        type="text"
                        name="name"
                        className="block w-full border border-gray-300 py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none"
                        placeholder="Ф.И.О"
                        id="name"
                        onChange={handleChange}
                        value={values.name}
                        required
                    />
                </div>

                <div className="col-span-6 sm:col-span-3">
                    <input
                        type="text"
                        name="card_number"
                        className="block w-full border border-gray-300 py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none"
                        placeholder="Номер карты - 8600 1234 5678 9102"
                        id="card_number"
                        onChange={handleChange}
                        value={values.card_number}
                        required
                    />
                </div>

                <div className="col-span-6 sm:col-span-3">
                    <button
                        type="submit"
                        className="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Создать заявку
                    </button>
                </div>

            </div>
        </form>
    );
}

import React, {useState} from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import Card from '@/Components/Client/Card';
import _ from "lodash";
import Form from "@/Components/Client/Form";

export default function Main(props) {

    const [values, setValues] = useState({
        from: _.first(props?.cryptocurrencies)?.asset_id ?? 'BTC',
        to: _.first(props?.currencies)?.asset_id ?? 'UZS',
        amount_from: '',
        amount_to: '',
        price_usd: '',
        email: '',
        name: '',
        card_number: '',
    })

    return (
        <GuestLayout>
            <Form values={values} setValues={setValues} cryptocurrencies={props?.cryptocurrencies} currencies={props?.currencies}/>
            <div className="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <Card items={props?.cryptocurrencies} values={values}/>
            </div>
        </GuestLayout>
    );
}

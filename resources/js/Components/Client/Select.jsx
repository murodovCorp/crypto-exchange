import {Fragment, useState} from 'react'
import {Listbox, Transition} from '@headlessui/react'
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/react/20/solid'
import _ from "lodash";

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function Select({items, classes, values, setValues, nameKey}) {

    const changeValue = (value) => {

        const priceUsd = _.find(items, {asset_id: values[nameKey]})?.price_usd

        setValues(values => ({
            ...values,
            [nameKey]: value,
            ['price_usd']: priceUsd,
        }))
    }

    return (
        <Listbox value={values[nameKey]} onChange={changeValue}>
            {({open}) => {

                const changed = _.find(items, { asset_id: values[nameKey] })

                const imgSrc = changed?.id_icon
                    ? `https://s3.eu-central-1.amazonaws.com/bbxt-static-icons/type-id/png_512/${changed.id_icon?.replaceAll('-', '')}.png`
                    : changed?.imgUrl

                return (
                    <>
                        <div className={`relative ${classes} w-full`}>
                            <Listbox.Button
                                className="relative w-full cursor-default border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none sm:text-sm"
                            >
                    <span className="flex items-center">
                        <img
                            src={imgSrc}
                            alt="" className="h-6 w-6 flex-shrink-0"
                        />
                        <span className="ml-3 block truncate">{changed?.name}</span>
                    </span>
                                <span className="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                                >
                                <ChevronUpDownIcon className="h-5 w-5 text-gray-400" aria-hidden="true"/>
                        </span>
                            </Listbox.Button>
                            <Transition
                                show={open}
                                as={Fragment}
                                leave="transition ease-in duration-100"
                                leaveFrom="opacity-100"
                                leaveTo="opacity-0"
                            >
                                <Listbox.Options
                                    className="absolute z-10 mt-1 max-h-56 w-full overflow-auto bg-white py-1 text-base shadow-lg focus:outline-none sm:text-sm">
                                    {items.map((item) => {

                                        const src = item?.id_icon
                                            ? `https://s3.eu-central-1.amazonaws.com/bbxt-static-icons/type-id/png_512/${item?.id_icon?.replaceAll('-', '')}.png`
                                            : item?.imgUrl

                                        return (
                                            <Listbox.Option
                                                key={item?.asset_id}
                                                className={({active}) =>
                                                    classNames(
                                                        active ? 'text-white bg-indigo-600' : 'text-gray-900',
                                                        'relative cursor-default select-none py-2 pl-3 pr-9'
                                                    )
                                                }
                                                value={item?.asset_id}
                                            >
                                                {({selected, active}) => (
                                                    <>
                                                        <div className="flex items-center">
                                                            <img src={src}
                                                                 alt=""
                                                                 className="h-6 w-6 flex-shrink-0"
                                                            />
                                                            <span
                                                                className={classNames(selected ? 'font-semibold' : 'font-normal', 'ml-3 block truncate')}
                                                            >
                                                        {item?.name}
                                                    </span>
                                                        </div>

                                                        {selected ? (
                                                            <span
                                                                className={classNames(
                                                                    active ? 'text-white' : 'text-indigo-600',
                                                                    'absolute inset-y-0 right-0 flex items-center pr-4'
                                                                )}
                                                            >
                                                        <CheckIcon className="h-5 w-5" aria-hidden="true"/>
                                                    </span>
                                                        ) : null}
                                                    </>
                                                )}
                                            </Listbox.Option>
                                        )
                                    })}
                                </Listbox.Options>
                            </Transition>
                        </div>
                    </>
                )
            }}
        </Listbox>
    )
}

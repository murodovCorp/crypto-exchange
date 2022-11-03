import React from 'react';

export default function Card({ items, values }) {

    return (
        items?.map((item) => {

            const imgUrl = item?.id_icon
                ? `https://s3.eu-central-1.amazonaws.com/bbxt-static-icons/type-id/png_512/${item?.id_icon?.replaceAll('-', '')}.png`
                : item?.imgUrl

            return (
                <div
                    key={item?.asset_id}
                    className="group relative cursor-pointer p-10"
                    style={{ borderRadius: '10px', border: '1px solid #ccc' }}
                >
                    <div
                        style={{ background: '#fff' }}
                        className="min-h-60 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-60"
                    >
                        <img
                            src={imgUrl}
                            alt={item?.id_icon}
                            className="w-full lg:w-full"
                        />
                    </div>
                    <div className="pt-2 flex justify-between">
                        <div>
                            <h3 className="text-sm text-gray-700">
                                <b>{item?.name} ({item?.asset_id})</b>
                                <p className="text-sm font-medium text-gray-900 mt-2">
                                    { item?.price_usd } <b>$</b>
                                </p>
                            </h3>
                        </div>
                    </div>
                </div>
            )
        })
    );
}

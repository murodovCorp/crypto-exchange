import Pagination from "@/Components/Pagination";

export default function Table({
                                  models = [],
                                  isEdit = true,
                                  isCreate = true,
                                  Title = '',
                                  Description = ''
                              }) {

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
                            <table className="min-w-full divide-y divide-gray-300">
                                <thead className="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        ID
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Ф.И.О
                                    </th>
                                    <th scope="col"
                                        className="py-3.5 pl-3 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Email
                                    </th>
                                    <th scope="col" className="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        {isEdit && <span className="sr-only">Edit</span>}
                                    </th>
                                </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                {models?.data?.map((model, key) => {

                                    return <tr key={key}>
                                        <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {model?.id}
                                        </td>
                                        <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {model?.name}
                                        </td>
                                        <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {model?.email ?? 'Не задано'}
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

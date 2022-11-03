import React from 'react';
import {Link} from "@inertiajs/inertia-react";

const Pagination = ({links, prev_page_url, next_page_url}) => {
    return (
        <div className="flex bg-white rounded-lg font-[Poppins] p-5">
            <Link href={prev_page_url}
                  style={{pointerEvents: !prev_page_url ? 'none' : ''}}
                  className="h-12 border-2 border-r-0 border-indigo-600 px-4 rounded-l-lg hover:bg-indigo-600 hover:text-white cursor-pointer"
            >
                <svg className="w-6 h-6 fill-current"
                     style={{margin: '10px 0 0 0', pointerEvents: !prev_page_url ? 'none' : ''}} viewBox="0 0 20 20">
                    <path
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clipRule="evenodd"
                        fillRule="evenodd"
                    >
                    </path>
                </svg>
            </Link>
            {
                links.map((link, i) => (
                    !(link.label === '&laquo; Previous' || link.label === 'Next &raquo;') &&
                    <Link key={i} href={link.url}
                          className={`w-12 pt-1 text-center border-2 border-r-0 border-indigo-600 cursor-pointer ${link.active && 'bg-indigo-600 text-white'}`}
                          style={{ fontSize: '22px', fontWeight: '500', pointerEvents: link.active ? 'none' : ''}}
                    >
                        {link.label}
                    </Link>
                ))
            }
            <Link href={next_page_url}
                  style={{pointerEvents: !next_page_url ? 'none' : ''}}
                  className="border-2  border-indigo-600 px-4 rounded-r-lg hover:bg-indigo-600 hover:text-white cursor-pointer"
            >
                <svg className="w-6 h-6 fill-current" style={{margin: '10px 0 0 0'}} viewBox="0 0 20 20">
                    <path
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clipRule="evenodd"
                        fillRule="evenodd"
                    >
                    </path>
                </svg>
            </Link>
        </div>
    )
}

export default Pagination
